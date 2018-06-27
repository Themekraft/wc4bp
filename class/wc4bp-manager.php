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

	/**
	 * Shop default slug
	 *
	 * @var String
	 */
	public static $shop_slug = 'shop';

	/**
	 * Shop default label
	 * @var string
	 */
	public static $shop_label;


	public function __construct() {
		try {
			self::$shop_label = __( 'Shop', 'wc4bp' );
			//Load resources
			require_once 'wc4bp-activity-stream.php';
			require_once 'wc4bp-myaccount-content.php';
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
				$wc_path = WooCommerce::instance()->plugin_path();
				include_once( $wc_path . '/includes/class-wc-frontend-scripts.php' );
				WC_Frontend_Scripts::init();
				new WC4BP_Activity_Stream();
				new WC4BP_MyAccount_Content();
				new wc4bp_Woocommerce();
				new WC4BP_MyAccount();
				new wc4bp_Manage_Admin();
				new WC4BP_Status();
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	/**
	 * Get endpoint prefix
	 *
	 * @return string
	 */
	public static function get_prefix() {
		$prefix = self::get_cached_option_or_default( 'my_account_prefix', 'prefix' );

		return $prefix . '_';
	}

	/**
	 * Get store slug
	 *
	 * @return string
	 */
	public static function get_shop_slug() {
		$slug = self::get_cached_option_or_default( 'tab_my_account_shop_url', 'shop_slug' );

		/**
		 * Get the store slug to use in the url
		 *
		 * @since 3.0.0
		 *
		 * @param String $var The current slug.
		 */
		return apply_filters( 'wc4bp_shop_slug', $slug );
	}

	/**
	 * Get the shop label option
	 *
	 * @return bool|String
	 */
	public static function get_shop_label() {
		$label = self::get_cached_option_or_default( 'tab_my_account_shop_label', 'shop_label' );

		return $label;
	}

	public static function get_cached_option_or_default( $option, $default_var ) {
		if ( empty( $option ) || empty( $default_var ) ) {
			return false;
		}
		$val = wp_cache_get( 'wc4bp_cache_' . $option, 'wc4bp' );
		if ( false === $val ) {
			$wc4bp_options = get_option( 'wc4bp_options' );
			if ( ! empty( $wc4bp_options[ $option ] ) ) {
				$val = $wc4bp_options[ $option ];
			} else {
				$val = self::$$default_var;
			}
			wp_cache_add( 'wc4bp_cache_' . $option, $val, 'wc4bp' );
		}

		return $val;
	}

	public static function del_cached_option_or_default( $option ) {
		return wp_cache_delete( 'wc4bp_cache_' . $option, 'wc4bp' );
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
	 * @since     1.0
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

	public static function get_shop_endpoints( $include_home = true ) {
		if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
			$shop_tabs = array(
				/**
				 * String used to identify the shop link label.
				 *
				 * @param string  By default Shop localized string.
				 */
				'home'     => apply_filters( 'bp_shop_link_label', __( 'Shop', 'wc4bp' ) ),
				/**
				 * String used to identify the cart link label.
				 *
				 * @param string  By default Shopping Cart localized string.
				 */
				'cart'     => apply_filters( 'bp_cart_link_label', __( 'Shopping Cart', 'wc4bp' ) ),
				/**
				 * String used to identify the checkout link label.
				 *
				 * @param string  By default Checkout localized string.
				 */
				'checkout' => apply_filters( 'bp_checkout_link_label', __( 'Checkout', 'wc4bp' ) ),
				/**
				 * String used to identify the track link label.
				 *
				 * @param string  By default Track localized string.
				 */
				'track'    => apply_filters( 'bp_track_link_label', __( 'Track your order', 'wc4bp' ) ),
			);
		} else {
			$shop_tabs = array(
				'home'     => __( 'Shop', 'wc4bp' ),
				'cart'     => __( 'Shopping Cart', 'wc4bp' ),
				'checkout' => __( 'Checkout', 'wc4bp' ),
				'track'    => __( 'Track your order', 'wc4bp' ),
			);
		}
		if ( ! $include_home ) {
			unset( $shop_tabs['home'] );
		}

		return $shop_tabs;
	}

	public static function available_endpoint() {
		$shop_tabs    = self::get_shop_endpoints();
		$account_tabs = WC4BP_MyAccount::get_available_endpoints();
		$result       = array_merge( $shop_tabs, $account_tabs );

		return $result;
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 *
	 * @return bool
	 */
	public static function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}

		return false;
	}

	public static function assets_path( $name, $extension = 'js' ) {
		$base_path         = ( $extension == 'js' ) ? WC4BP_JS : WC4BP_CSS;
		$join_ext_and_name = ( ! defined( SCRIPT_DEBUG ) ) ? '.min.' : '.';

		return $base_path . $name . $join_ext_and_name . $extension;
	}
}
