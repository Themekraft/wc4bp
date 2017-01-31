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
	
	public function __construct() {
		//Load resources
		require_once 'wc4bp-myaccount.php';
		new WC4BP_MyAccount();
		
		require_once 'wc4bp-woocommerce.php';
		new wc4bp_Woocommerce();
		
		require_once 'wc4bp-myaccount-content.php';
		new WC4BP_MyAccount_Content();
		
		require_once 'wc4bp-manage-admin.php';
		new wc4bp_Manage_Admin();
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