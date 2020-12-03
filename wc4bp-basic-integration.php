<?php
/**
 * Plugin Name: WC4BP -> WooCommerce BuddyPress Integration
 * Plugin URI: https://themekraft.com/woocommerce-buddypress-integration/
 * Description: Integrates a WooCommerce installation with a BuddyPress social network
 * Author: ThemeKraft
 * Author URI: https://themekraft.com/products/woocommerce-buddypress-integration/
 * Version: 3.3.11
 * Licence: GPLv3
 * Text Domain: wc4bp
 * Domain Path: /languages
 * Svn: wc4bp
 *
 *****************************************************************************
 * WC requires at least: 3.5.0
 * WC tested up to: 4.0.1
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'wc4bp-exception-handler.php';

if ( ! class_exists( 'WC4BP_Loader' ) ) {
	class WC4BP_Loader {
		/**
		 * The plugin version
		 */
		const VERSION = '3.3.11';

		/**
		 * Minimum required WP version
		 */
		const MIN_WP = '4.9';

		/**
		 * Minimum required BP version
		 */
		const MIN_BP = '2.2';

		/**
		 * Minimum required woocommerce version
		 */
		const MIN_WOO = '3.4';

		/**
		 * Name of the plugin folder
		 */
		static $plugin_name;

		/**
		 * Can the plugin be executed
		 */
		static $active = false;

		/**
		 * @var Freemius
		 */
		public static $freemius;

		/**
		 * Is true when the plugin is the premium version
		 *
		 * @var bool
		 */
		private static $is_pro;

		/**
		 * Initiate the class
		 *
		 * @package WooCommerce for BuddyPress
		 * @since   0.1-beta
		 */

		public function __construct() {
			try {
				self::$plugin_name = __FILE__;
				self::$is_pro      = ( strpos( self::$plugin_name, 'premium' ) !== false );
				$this->constants();
				require_once dirname( __FILE__ ) . '/class/includes/class-request-helper.php';
				require_once dirname( __FILE__ ) . '/class/includes/wc4bp_requirements.php';
				require_once dirname( __FILE__ ) . '/class/includes/class-tgm-plugin-activation.php';
				require_once dirname( __FILE__ ) . '/class/wc4bp-base.php';
				require_once dirname( __FILE__ ) . '/class/wc4bp-manager.php';
				require_once dirname( __FILE__ ) . '/class/wc4bp-required-php.php';
				require_once dirname( __FILE__ ) . '/class/wc4bp-required.php';
				require_once dirname( __FILE__ ) . '/class/wc4bp-upgrade.php';

				// Init Freemius.
				self::$freemius = $this->wc4bp_fs();
				self::$freemius->set_basename( true, __FILE__ );
				/**
				 * Execute on freemius load to notify the addons
				 */
				do_action( 'wc4bp_core_fs_loaded' );
				$requirements = new WC4BP_Required_PHP( 'wc4bp' );
				if ( $requirements->satisfied() ) {
					new WC4BP_Required();
					if ( wc4bp_Manager::is_woocommerce_active() && ( wc4bp_Manager::is_buddypress_active() || wc4bp_Manager::is_buddyboss_theme_active() ) ) {
						//Adding edd migration code
						new wc4bp_Manager();
						new WC4BP_Upgrade( plugin_basename( dirname( __FILE__ ) ) );
						// Run the activation function
						register_activation_hook( __FILE__, array( $this, 'activation' ) );
						/**
						 * Deletes all data if plugin deactivated
						 */
						register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

						add_action( 'plugins_loaded', array( $this, 'update' ), 10 );
						add_action( 'plugins_loaded', array( $this, 'wc4bp_translate' ) );

						self::getFreemius()->add_action( 'after_uninstall', array( $this, 'uninstall_cleanup' ) );
					}
				} else {
					$faux_plugin = new WP_Faux_Plugin( __( 'WC4BP -> WooCommerce BuddyPress Integration', 'wc4bp' ), $requirements->getResults() );
					$faux_plugin->show_result( plugin_basename( __FILE__ ) );
				}
			}
			catch ( Exception $exception ) {
				self::get_exception_handler()->save_exception( $exception->getTrace() );
			}
		}

		public function get_version() {
			return self::VERSION;
		}

		/**
		 * Create a helper function for easy Freemius SDK access.
		 *
		 * @return Freemius
		 */
		public function wc4bp_fs() {
			global $wc4bp_fs;
			try {
				if ( ! isset( $wc4bp_fs ) ) {
					// Include Freemius SDK.
					require_once WC4BP_ABSPATH_CLASS_PATH . 'includes/freemius/start.php';

					$wc4bp_fs = fs_dynamic_init( array(
						'id'                  => '425',
						'slug'                => 'wc4bp',
						'type'                => 'plugin',
						'public_key'          => 'pk_71d28f28e3e545100e9f859cf8554',
						'is_premium'          => true,
						'premium_suffix'      => 'premium',
						'has_premium_version' => true,
						'has_addons'          => true,
						'has_paid_plans'      => true,
						'trial'               => array(
							'days'               => 14,
							'is_require_payment' => false,
						),
						'has_affiliation'     => 'all',
						'menu'                => array(
							'slug'    => 'wc4bp-options-page',
							'support' => false,
						)
					) );
				}
			}
			catch ( Exception $exception ) {
				self::get_exception_handler()->save_exception( $exception->getTrace() );
			}

			return $wc4bp_fs;
		}

		/**
		 * Declare all constants
		 *
		 * @since     1.0
		 * @access    private
		 */
		private function constants() {
			define( 'WC4BP_VERSION', self::VERSION );
			define( 'WC4BP_FOLDER', plugin_basename( dirname( __FILE__ ) ) );
			define( 'WC4BP_ABSPATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
			define( 'WC4BP_URLPATH', trailingslashit( plugins_url( '/' . WC4BP_FOLDER ) ) );
			define( 'WC4BP_ABSPATH_CLASS_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR );
			define( 'WC4BP_ABSPATH_PATCH_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'patch' . DIRECTORY_SEPARATOR );
			define( 'WC4BP_ABSPATH_TEMPLATE_PATH', WC4BP_ABSPATH . 'templates' . DIRECTORY_SEPARATOR );
			define( 'WC4BP_ABSPATH_ADMIN_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR );
			define( 'WC4BP_ABSPATH_ADMIN_VIEWS_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR );
			define( 'WC4BP_CSS', WC4BP_URLPATH . 'admin/css/' );
			define( 'WC4BP_JS', WC4BP_URLPATH . 'admin/js/' );
			define( 'WC4BP_IMAGES', WC4BP_URLPATH . 'admin/images/' );
		}

		/**
		 * @return Freemius
		 */
		public static function getFreemius() {
			return self::$freemius;
		}

		/**
		 * Load the language file
		 *
		 * @since    1.0
		 * @uses     load_plugin_textdomain()
		 */
		public function wc4bp_translate() {
			load_plugin_textdomain( 'wc4bp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/*
		 *  Update function from version 1.3.8 to 1.4
		 */
		public function update() {
			try {
				if ( version_compare( WC4BP_VERSION, '1.4', '<' ) ) {

					$billing  = bp_get_option( 'wc4bp_billing_address_ids' );
					$shipping = bp_get_option( 'wc4bp_shipping_address_ids' );

					$billing_changed  = false;
					$shipping_changed = false;

					if ( isset( $billing['address'] ) ) {
						$billing['address_1'] = $billing['address'];
						unset( $billing['address'] );
						$billing_changed = true;
					}

					if ( isset( $billing['address-2'] ) ) {
						$billing['address_2'] = $billing['address-2'];
						unset( $billing['address-2'] );
						$billing_changed = true;
					}

					if ( isset( $shipping['address'] ) ) {
						$shipping['address_1'] = $shipping['address'];
						unset( $shipping['address'] );
						$shipping_changed = true;
					}
					if ( isset( $shipping['address-2'] ) ) {
						$shipping['address_2'] = $shipping['address-2'];
						unset( $shipping['address-2'] );
						$shipping_changed = true;
					}

					if ( true === $billing_changed ) {
						bp_update_option( 'wc4bp_billing_address_ids', $billing );
					}

					if ( true === $shipping_changed ) {
						bp_update_option( 'wc4bp_shipping_address_ids', $shipping );
					}
				}
			}
			catch ( Exception $exception ) {
				self::get_exception_handler()->save_exception( $exception->getTrace() );
			}
		}

		/**
		 * Generate the default data arrays
		 */
		public function activation() {
			try {
				if ( ! self::$is_pro ) {
					$t = self::$plugin_name;
					deactivate_plugins( 'wc4bp/wc4bp-basic-integration', true );
				}
				WC4BP_MyAccount::clean_my_account_cached();
				include_once( dirname( __FILE__ ) . '/class/core/wc4bp-sync.php' );
				include_once( dirname( __FILE__ ) . '/admin/wc4bp-activate.php' );
				wc4bp_activate();
			}
			catch ( Exception $exception ) {
				self::get_exception_handler()->save_exception( $exception->getTrace() );
			}
		}

		/**
		 * Deletes all data if plugin deactivated
		 *
		 * @return void
		 */
		public function deactivation() {
			try {
				$wc4bp_options_delete = get_option( 'wc4bp_options_delete' );
				if ( $wc4bp_options_delete ) {
					include_once( dirname( __FILE__ ) . '/class/core/wc4bp-sync.php' );
					wc4bp_Sync::clean_xprofield_fields_cached();
					WC4BP_MyAccount::clean_my_account_cached();
					self::uninstall_cleanup();
				}
			}
			catch ( Exception $exception ) {
				self::get_exception_handler()->save_exception( $exception->getTrace() );
			}
		}

		/**
		 * @return WC4BP_Exception_Handler
		 */
		public static function get_exception_handler() {
			return WC4BP_Exception_Handler::get_instance();
		}

		/**
		 * Clean the related plugins data when it is uninstall
		 */
		public function uninstall_cleanup() {
			try {
				// Removes all data from the database
				delete_option( 'wc4bp_installed' );
				delete_option( 'wc4bp_installed_date' );
				delete_option( 'wc4bp_shipping_address_ids' );
				delete_option( 'wc4bp_billing_address_ids' );
				delete_option( 'wc4bp_options' );
				delete_option( 'woocommerce-buddypress-integration' );
				delete_option( 'wc4bp-basic-integration' );
				delete_option( 'wc4bp_api_manager_instance' );
				delete_option( 'wc4bp_api_manager_deactivate_checkbox' );
				delete_option( 'wc4bp_api_manager_activated' );
				delete_option( 'wc4bp_api_manager_version' );
				delete_option( 'wc4bp_api_manager_checkbox' );
				delete_option( 'wc4bp_options_sync' );
				delete_option( 'wc4bp_options_tabs' );
				delete_option( 'wc4bp_pages_options' );
				delete_option( 'wc4bp_options_delete' );
			}
			catch ( Exception $exception ) {
				self::get_exception_handler()->save_exception( $exception->getTrace() );
			}
		}
	}

//Entry point for this plugins
	$GLOBALS['wc4bp_loader'] = new WC4BP_Loader();
}
