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

	if ( defined( 'DOING_CRON' ) ) {
		return;
	}

	require_once dirname( __FILE__ ) . '/class-fs-client-license-abstract.php';
	require_once dirname( __FILE__ ) . '/class-fs-edd-client-migration.php';

	/**
	 * You should use your own unique CLASS name, and be sure to replace it
	 * throughout this file. For example, if your product's name is "Awesome Product"
	 * then you can rename it to "Awesome_Product_EDD_License_Key".
	 */
	class WC4BP_EDD_License_Key extends FS_Client_License_Abstract_v1 {
		/**
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.3
		 *
		 * @return string
		 */
		function get() {
			// You should adjust this to load the license key of your EDD download.
			return trim( get_option( 'edd_wc4bp_license_key' ) );
		}

		/**
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.3
		 *
		 * @param string $license_key
		 *
		 * @return bool True if successfully updated.
		 */
		function set( $license_key ) {
			// You should adjust this to update the license key of your EDD download.
			return update_option( 'edd_wc4bp_license_key', $license_key );
		}
	}

	new FS_EDD_Client_Migration_v1(
		// This should be replaced with your custom Freemius shortcode.
		WC4BP_Loader::getFreemius(),

		// This should point to your EDD store root URL.
		'https://themekraft.com',

		// The EDD download ID of your product.
		'15105',

		new WC4BP_EDD_License_Key()
	);