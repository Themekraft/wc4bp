<?php

/**
 * @package        WordPress
 * @subpackage     BuddyPress, WooCommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC4BP_Exception_Handler {
	private static $instance;
	private $exception_list_name = 'exceptions';
	private $internal_prefix = 'exception';
	private $exception_list;

	public function __construct() {
		/**
		 * Debug prefix.
		 *
		 * This prefix is used in the debug string to identify the plugin.
		 *
		 * @param string Default values is 'wc4bp'
		 */
		$prefix                    = apply_filters( 'wc4bp_exception_prefix', 'wc4bp' );
		$this->exception_list_name = $prefix . '_' . $this->exception_list_name;
		$this->internal_prefix     = $prefix . '_' . $this->internal_prefix;
		$this->load_exceptions_list();
	}

	public function error_handler( $number, $message, $file, $line ) {
		$trace          = new stdClass;
		$trace->number  = $number;
		$trace->message = $message;
		$trace->file    = $file;
		$trace->line    = $line;
		$this->save_exception( $trace );
	}

	public function save_exception( $trace ) {
		if ( ! empty( $trace ) ) {
			$this->exception_list[ time() ] = $trace;

			return true;
		} else {
			return false;
		}
	}

	public function register_exception() {
		if ( ! empty( $this->exception_list ) ) {
			return update_option( $this->exception_list_name, $this->exception_list );
		} else {
			return false;
		}
	}

	public function get_exception_list() {
		return $this->exception_list;
	}

	public function load_exceptions_list() {
		if ( empty( $this->exception_list ) ) {
			$register_exceptions = get_option( $this->exception_list_name );
			if ( ! empty( $register_exceptions ) ) {
				$this->exception_list = $register_exceptions;
			} else {
				$this->exception_list = array();
			}
		} else {
			$this->exception_list;
		}
	}

	public static function clean_exceptions() {
		$list_name = self::get_instance()->exception_list_name;

		return delete_option( $list_name );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return WC4BP_Exception_Handler A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}