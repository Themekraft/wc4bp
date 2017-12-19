<?php
	/**
	 * @package     Freemius Migration
	 * @copyright   Copyright (c) 2016, Freemius, Inc.
	 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
	 * @since       1.0.3
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( class_exists( 'FS_Client_Migration_Abstract_v1' ) ) {
		return;
	}

	abstract class FS_Client_Migration_Abstract_v1 {
		/**
		 * @var \Freemius Freemius instance manager.
		 */
		protected $_fs;

		/**
		 * @var string Store URL.
		 */
		protected $_store_url;

		/**
		 * @var string Product ID.
		 */
		protected $_product_id;

		/**
		 * @var string License Key.
		 */
		protected $_license_key;

		/**
		 * @var FS_Client_License_Abstract_v1
		 */
		protected $_license_accessor;

		/**
		 * @var string Migration namespace.
		 */
		protected $_namespace;

		/**
		 * @param string                        $namespace        Migration namespace (e.g. EDD, WC)
		 * @param Freemius                      $freemius
		 * @param string                        $store_url        Store URL.
		 * @param string                        $product_id       Product ID.
		 * @param FS_Client_License_Abstract_v1 $license_accessor License accessor.
		 */
		protected function init(
			$namespace,
			Freemius $freemius,
			$store_url,
			$product_id,
			FS_Client_License_Abstract_v1 $license_accessor
		) {
			$this->_namespace        = strtolower( $namespace );
			$this->_fs               = $freemius;
			$this->_store_url        = $store_url;
			$this->_product_id       = $product_id;
			$this->_license_accessor = $license_accessor;
			$this->_license_key      = $license_accessor->get();

			/**
			 * If no license is set it might be one of the following:
			 *  1. User purchased module directly from Freemius.
			 *  2. User did purchase from store, but has never activated the license on this site.
			 *  3. User got access to the code without ever purchasing.
			 *
			 * In case it's reason #2 or if the license key is wrong, the migration will not work.
			 * Since we do want to support store licenses, hook to Freemius `after_install_failure`
			 * event. That way, if a license activation fails, try activating the license on store
			 * first, and if works, migrate to Freemius right after.
			 */
			$this->_fs->add_filter( 'after_install_failure', array( &$this, 'try_migrate_on_activation' ), 10, 2 );

			if ( ! empty( $this->_license_key ) ) {
				if ( ! defined( 'DOING_AJAX' ) ) {
					$this->non_blocking_license_migration();
				}
			}
		}

		/**
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.3
		 *
		 * @return string
		 */
		protected function get_migration_endpoint() {
			return sprintf(
				'%s/fs-api/%s/migrate-license.json',
				$this->_store_url,
				$this->_namespace
			);
		}

		/**
		 * The license migration script.
		 *
		 * IMPORTANT:
		 *  You should use your own function name, and be sure to replace it throughout this file.
		 *
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @param bool $redirect
		 *
		 * @return bool
		 */
		protected function do_license_migration( $redirect = false ) {
			$install_details = $this->_fs->get_opt_in_params();

			// Override is_premium flat because it's a paid license migration.
			$install_details['is_premium'] = true;
			// The plugin is active for sure and not uninstalled.
			$install_details['is_active']      = true;
			$install_details['is_uninstalled'] = false;

			// Clean unnecessary arguments.
			unset( $install_details['return_url'] );
			unset( $install_details['account_url'] );


			// Call the custom license and account migration endpoint.
			$transient_key = 'fs_license_migration_' . $this->_product_id . '_' . md5( $this->_license_key );
			$response      = get_transient( $transient_key );

			if ( false === $response ) {
				$response = wp_remote_post(
					$this->get_migration_endpoint(),
					array_merge( $install_details, array(
						'timeout'   => 15,
						'sslverify' => false,
						'body'      => json_encode( array_merge( $install_details, array(
							'module_id'   => $this->_product_id,
							'license_key' => $this->_license_key,
							'url'         => home_url()
						) ) ),
					) )
				);

				// Cache result (5-min).
				set_transient( $transient_key, $response, 5 * MINUTE_IN_SECONDS );
			}

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				$error_message = $response->get_error_message();

				return ( is_wp_error( $response ) && ! empty( $error_message ) ) ?
					$error_message :
					__( 'An error occurred, please try again.' );

			} else {
				$response = json_decode( wp_remote_retrieve_body( $response ) );

				if ( ! is_object( $response ) ||
				     ! isset( $response->success ) ||
				     true !== $response->success
				) {
					if ( isset( $response->error ) ) {
						switch ( $response->error->code ) {
							case 'invalid_license_key':
								// Invalid license key.
								break;
							case 'invalid_download_id':
								// Invalid download ID.
								break;
							default:
								// Unexpected error.
								break;
						}
					} else {
						// Unexpected error.
					}

					// Failed to pull account information.
					return false;
				}

				// Delete transient on successful migration.
				delete_transient( $transient_key );

				$this->_fs->setup_account(
					new FS_User( $response->data->user ),
					new FS_Site( $response->data->install ),
					$redirect
				);

				return true;
			}
		}

		/**
		 * Get current request full URL.
		 *
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.3
		 *
		 * @return string
		 */
		private function get_current_url() {
			$host = $_SERVER['HTTP_HOST'];
			$uri  = $_SERVER['REQUEST_URI'];
			$port = $_SERVER['SERVER_PORT'];
			$port = ( ( ! WP_FS__IS_HTTPS && $port == '80' ) || ( WP_FS__IS_HTTPS && $port == '443' ) ) ? '' : ':' . $port;

			return ( WP_FS__IS_HTTPS ? 'https' : 'http' ) . "://{$host}{$port}{$uri}";
		}

		/**
		 * Initiate a non-blocking HTTP POST request to the same URL
		 * as the current page, with the addition of "fsm_{namespace}_{product_id}"
		 * param in the query string that is set to a unique migration
		 * request identifier, making sure only one request will make
		 * the migration.
		 *
		 * @todo     Test 2 threads in parallel and make sure that `add_transient()` works as expected.
		 *
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @return bool Is successfully spawned the migration request.
		 */
		protected function spawn_license_migration() {
			#region Make sure only one request handles the migration (prevent race condition)

			// Generate unique md5.
			$migration_uid = md5( rand() . microtime() );

			$loaded_migration_uid = false;

			/**
			 * Use `add_transient()` instead of `set_transient()` because
			 * we only want that one request will succeed writing this
			 * option to the storage.
			 */
			$transient_key = "fsm_{$this->_namespace}_{$this->_product_id}";
			if ( $this->add_transient( $transient_key, $migration_uid, MINUTE_IN_SECONDS ) ) {
				$loaded_migration_uid = $this->get_transient( $transient_key );
			}

			if ( $migration_uid !== $loaded_migration_uid ) {
				return false;
			}

			#endregion

			$migration_url = add_query_arg(
				"fsm_{$this->_namespace}_{$this->_product_id}",
				$migration_uid,
				$this->get_current_url()
			);

			// Add cookies to trigger request with same user access permissions.
			$cookies = array();
			foreach ( $_COOKIE as $name => $value ) {
				$cookies[] = new WP_Http_Cookie( array(
					'name'  => $name,
					'value' => $value
				) );
			}

			wp_remote_post(
				$migration_url,
				array(
					'timeout'   => 0.01,
					'blocking'  => false,
					'sslverify' => false,
					'cookies'   => $cookies,
				)
			);

			return true;
		}

		/**
		 * Run non blocking migration if all of the following (AND condition):
		 *  1. Has API connectivity to api.freemius.com
		 *  2. User isn't yet identified with Freemius.
		 *  3. Freemius is in "activation mode".
		 *  4. It's a plugin version upgrade.
		 *  5. It's the first installation of the context plugin that have Freemius integrated with.
		 *
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @param bool $is_blocking       Special argument for testing. When false, will initiate the migration in the
		 *                                same HTTP request.
		 *
		 * @return string|bool
		 */
		protected function non_blocking_license_migration( $is_blocking = false ) {
			if ( ! $this->_fs->has_api_connectivity() ) {
				// No connectivity to Freemius API, it's up to you what to do.
				return 'no_connectivity';
			}

			if ( $this->_fs->is_registered() ) {
				// User already identified by the API.
				return 'user_registered';
			}

			if ( ! $this->_fs->is_activation_mode() ) {
				// Plugin isn't in Freemius activation mode.
				return 'not_in_activation';
			}
			if ( ! $this->_fs->is_plugin_upgrade_mode() ) {
				// Plugin isn't in plugin upgrade mode.
				return 'not_in_upgrade';
			}

			if ( ! $this->_fs->is_first_freemius_powered_version() ) {
				// It's not the 1st version of the plugin that runs with Freemius.
				return 'freemius_installed_before';
			}

			$key = "fsm_{$this->_namespace}_{$this->_product_id}";

			$migration_uid = $this->get_transient( $key );
			$in_migration  = ! empty( $_REQUEST[ $key ] );

			if ( ! $is_blocking && ! $in_migration ) {
				// Initiate license migration in a non-blocking request.
				return $this->spawn_license_migration();
			} else {
				if ( $is_blocking ||
				     ( ! empty( $_REQUEST[ $key ] ) &&
				       $migration_uid === $_REQUEST[ $key ] &&
				       'POST' === $_SERVER['REQUEST_METHOD'] )
				) {
					$success = $this->do_license_migration();

					if ( $success ) {
						$this->_fs->set_plugin_upgrade_complete();

						return 'success';
					}
				}
			}

			return 'failed';
		}

		/**
		 * Try to platform's activate license via the store.
		 *
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @param string $license_key
		 *
		 * @return bool
		 */
		abstract protected function activate_store_license( $license_key );

		/**
		 * If installation failed due to license activation on Freemius try to
		 * activate the license on store first, and if successful, migrate the license
		 * with a blocking request.
		 *
		 * This method will only be triggered upon failed module installation.
		 *
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @param object $response Freemius installation request result.
		 * @param array  $args     Freemius installation request arguments.
		 *
		 * @return object|string
		 */
		public function try_migrate_on_activation( $response, $args ) {
			if ( empty( $args['license_key'] ) || 32 !== strlen( $args['license_key'] ) ) {
				// No license key provided (or invalid length), ignore.
				return $response;
			}

			if ( ! $this->_fs->has_api_connectivity() ) {
				// No connectivity to Freemius API, it's up to you what to do.
				return $response;
			}

			$license_key = $args['license_key'];

			if ( ( is_object( $response->error ) && 'invalid_license_key' === $response->error->code ) ||
			     ( is_string( $response->error ) && false !== strpos( strtolower( $response->error ), 'license' ) )
			) {
				if ( $this->activate_store_license( $license_key ) ) {
					// Successfully activated license on store, try to migrate to Freemius.
					if ( $this->do_license_migration( true ) ) {
						/**
						 * If successfully migrated license and got to this point (no redirect),
						 * it means that it's an AJAX installation (opt-in), therefore,
						 * override the response with the after connect URL.
						 */
						return $this->_fs->get_after_activation_url( 'after_connect_url' );
					}
				}
			}

			return $response;
		}

		#region Database Transient

		/**
		 * Very similar to the WP transient mechanism.
		 *
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @param string $transient
		 *
		 * @return mixed
		 */
		private function get_transient( $transient ) {
			$transient_option  = '_fs_transient_' . $transient;
			$transient_timeout = '_fs_transient_timeout_' . $transient;

			$timeout = get_option( $transient_timeout );

			if ( false !== $timeout && $timeout < time() ) {
				delete_option( $transient_option );
				delete_option( $transient_timeout );
				$value = false;
			} else {
				$value = get_option( $transient_option );
			}

			return $value;
		}

		/**
		 * Not like `set_transient()`, this function will only ADD
		 * a transient if it's not yet exist.
		 *
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @param string $transient
		 * @param mixed  $value
		 * @param int    $expiration
		 *
		 * @return bool TRUE if successfully added a transient.
		 */
		private function add_transient( $transient, $value, $expiration = 0 ) {
			$transient_option  = '_fs_transient_' . $transient;
			$transient_timeout = '_fs_transient_timeout_' . $transient;

			$current_value = $this->get_transient( $transient );

			if ( false === $current_value ) {
				$autoload = 'yes';
				if ( $expiration ) {
					$autoload = 'no';
					add_option( $transient_timeout, time() + $expiration, '', 'no' );
				}

				return add_option( $transient_option, $value, '', $autoload );
			} else {
				// If expiration is requested, but the transient has no timeout option,
				// delete, then re-create the timeout.
				if ( $expiration ) {
					if ( false === get_option( $transient_timeout ) ) {
						add_option( $transient_timeout, time() + $expiration, '', 'no' );
					}
				}
			}

			return false;
		}

		#endregion
	}