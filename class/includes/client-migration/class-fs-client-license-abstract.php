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

	if ( class_exists( 'FS_Client_License_Abstract_v1' ) ) {
		return;
	}

	abstract class FS_Client_License_Abstract_v1 {
		/**
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @return string
		 */
		abstract function get();

		/**
		 * @author   Vova Feldman (@svovaf)
		 * @since    1.0.0
		 *
		 * @param string $license_key
		 *
		 * @return bool True if successfully updated.
		 */
		abstract function set( $license_key );
	}