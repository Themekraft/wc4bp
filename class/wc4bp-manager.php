<?php

/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wc4bp_Manager {

	/**
	 * Prefix used to mark the pages for my account
	 *
	 * @var String
	 */
	public static $prefix = 'wc4bp';

	public function __construct() {
		try {
			//Load resources
			if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$starter_plan_id ) ) {
				require_once 'wc4bp-myaccount-content.php';
			}
			require_once 'wc4bp-myaccount.php';
			require_once 'wc4bp-myaccount-private.php';
			require_once 'wc4bp-woocommerce.php';
			require_once 'wc4bp-manage-admin.php';
			require_once 'wc4bp-redefine-functions.php';
			require_once 'wc4bp-status.php';

			add_action( 'init', array( $this, 'init' ) );
			add_action( 'bp_include', array( $this, 'includes' ), 10 );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}


	public function init() {
		try {
			new WC4BP_MyAccount_Private();
			$cu = get_current_user_id();
			if ( $cu > 0 ) {
				if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$starter_plan_id ) ) {
					new WC4BP_MyAccount_Content();
				}
				new wc4bp_Woocommerce();
				new WC4BP_MyAccount();
				new wc4bp_Manage_Admin();
				new WC4BP_Status();
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public static function get_prefix() {
		$prefix = wp_cache_get( 'wc4bp_my_account_prefix', 'wc4bp' );
		if ( false === $prefix ) {
			$wc4bp_options = get_option( 'wc4bp_options' );
			if ( ! empty( $wc4bp_options['my_account_prefix'] ) ) {
				$prefix = $wc4bp_options['my_account_prefix'];
			} else {
				$prefix = self::$prefix;
			}
			wp_cache_add( 'wc4bp_my_account_prefix', $prefix, 'wc4bp' );
		}

		return $prefix . '_';
	}

	/**
	 * Add admin notices to single site or multisite
	 *
	 * @param        $message
	 * @param string $type
	 */
	public static function admin_notice( $message, $type = 'error' ) {
		if ( is_multisite() ) {
			add_action( 'network_admin_notices', function () use ( $message, $type ) {
				echo '<div class="' . esc_attr( $type ) . '"><b>WC4BP -> WooCommerce BuddyPress Integration</b>: ' . $message . '</div>';
			} );
		} else {
			add_action( 'admin_notices', function () use ( $message, $type ) {
				echo '<div class="' . esc_attr( $type ) . '"><b>WC4BP -> WooCommerce BuddyPress Integration</b>: ' . $message . '</div>';
			} );
		}
	}

	public static function get_suffix() {
		return self::$prefix;
	}

	/**
	 * Load all BP related files and admin
	 *
	 * Attached to bp_include. Stops the plugin if certain conditions are not met.
	 *
	 * @since    1.0
	 * @access    public
	 */
	public function includes() {
		try {
			// core component
			require( WC4BP_ABSPATH . 'class/core/wc4bp-component.php' );

			global $bp;
			if ( ! isset( $bp->shop ) ) {
				$bp->shop = new WC4BP_Component();
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public static function load_plugins_dependency() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	public static function is_woocommerce_active() {
		self::load_plugins_dependency();

		return is_plugin_active( 'woocommerce/woocommerce.php' );
	}

	public static function is_buddypress_active() {
		self::load_plugins_dependency();

		return is_plugin_active( 'buddypress/bp-loader.php' );
	}

	public static function is_current_active() {
		self::load_plugins_dependency();

		return is_plugin_active( 'wc4bp/wc4bp-basic-integration.php' );
	}

	public static function available_endpoint() {
		$shop_tabs    = array( 'home', 'cart', 'checkout', 'history', 'track' );
		$account_tabs = WC4BP_MyAccount::get_available_endpoints();
		$result       = array_merge( $shop_tabs, array_keys( $account_tabs ) );

		return $result;
	}
}