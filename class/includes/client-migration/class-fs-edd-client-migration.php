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

	if ( class_exists( 'FS_EDD_Client_Migration_v1' ) ) {
		return;
	}

	// Include abstract class.
	require_once dirname( __FILE__ ) . '/class-fs-client-migration-abstract.php';

	/**
	 * Class My_EDD_Freemius_Migration
	 */
	class FS_EDD_Client_Migration_v1 extends FS_Client_Migration_Abstract_v1 {
		/**
		 *
		 * @param Freemius                      $freemius
		 * @param string                        $edd_store_url        Your EDD store URL.
		 * @param int                           $edd_download_id      The context EDD download ID (from your store).
		 * @param FS_Client_License_Abstract_v1 $edd_license_accessor License accessor.
		 */
		public function __construct(
			Freemius $freemius,
			$edd_store_url,
			$edd_download_id,
			FS_Client_License_Abstract_v1 $edd_license_accessor
		) {
			$this->init(
				'edd',
				$freemius,
				$edd_store_url,
				$edd_download_id,
				$edd_license_accessor
			);
		}

		/**
		 * Try to activate EDD license.
		 *
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @param string $license_key License key.
		 *
		 * @return bool
		 */
		function activate_store_license( $license_key ) {
			// Call the custom API.
			$response = wp_remote_post(
				$this->_store_url,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => array(
						'edd_action' => 'activate_license',
						'license'    => $license_key,
						'item_id'    => $this->_product_id,
						'url'        => home_url()
					)
				)
			);

			// Make sure the response came back okay.
			if ( is_wp_error( $response ) ) {
				return false;
			}

			// Decode the license data.
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( 'valid' === $license_data->license ) {
				$this->_license_accessor->set( $license_key );
				$this->_license_key = $license_key;
			} else {
				return false;
			}

			return true;
		}
	}
