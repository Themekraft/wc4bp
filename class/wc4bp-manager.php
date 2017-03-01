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
	public static $prefix;
	
	public function __construct() {
		//Load resources
		if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$professional_plan_id ) ) {
			require_once 'wc4bp-myaccount-content.php';
			self::$prefix = apply_filters( 'wc4bp_my_account_prefix', 'wc4pb' );
		}
		require_once 'wc4bp-myaccount.php';
		require_once 'wc4bp-woocommerce.php';
		require_once 'wc4bp-manage-admin.php';
		require_once 'wc4bp-redefine-functions.php';
		
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'bp_include', array( $this, 'includes' ), 10 );
	}
	
	public function init() {
		$cu = get_current_user_id();
		if ( $cu > 0 ) {
			if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$professional_plan_id ) ) {
				new WC4BP_MyAccount_Content();
			}
			new WC4BP_MyAccount();
			new wc4bp_Woocommerce();
			new wc4bp_Manage_Admin();
		}
	}
	
	public static function get_prefix() {
		return self::$prefix . '_';
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
		// core component
		require( WC4BP_ABSPATH . 'class/core/wc4bp-component.php' );
		
		global $bp;
		if ( ! isset( $bp->shop ) ) {
			$bp->shop = new WC4BP_Component();
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
}