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
	
	public static function is_woocommerce_active() {
		if ( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration needs WooCommerce to be installed. <a href="%s">Download it now</a>!\', " wc4bp" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
			
			return false;
		}
		
		return true;
	}
	
	
}