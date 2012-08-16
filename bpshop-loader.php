<?php
/**
 * Plugin Name: WooCommerce for Buddypress
 * Plugin URI:  https://github.com/Themekraft/WooCommerce-for-Buddypress
 * Description: Integrates a WooCommerce installation with a BuddyPress social network
 * Author:      BP Shop Dev Team
 * Version:     1.0.5
 * Author URI:  https://github.com/Themekraft/WooCommerce-for-Buddypress
 * Network:     true
 * 
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

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class BPSHOP_Loader
{
	/**
	 * The plugin version
	 */
	const VERSION 	= '1.0.5';
	
	/**
	 * Minimum required WP version
	 */
	const MIN_WP 	= '3.4.1';
	
	/**
	 * Minimum required BP version
	 */
	const MIN_BP 	= '1.6.1';

	/**
	 * Minimum required woocommerce version
	 */
	const MIN_WOO 	= '1.6.3';

	/**
	 * Name of the plugin folder
	 */
	static $plugin_name;

	/**
	 * Can the plugin be executed
	 */
	static $active = false;
	
	/**
	 * PHP5 constructor
	 * 
	 * @since 	1.0
	 * @access 	public
	 * @uses	plugin_basename()
	 * @uses	register_activation_hook()
	 * @uses	register_uninstall_hook()
	 * @uses	add_action()
	 */
	public function init() {
		self::$plugin_name = plugin_basename( __FILE__ );

		self::constants();
		
		register_activation_hook( self::$plugin_name, array( __CLASS__, 'activate'  ) );
		register_uninstall_hook(  self::$plugin_name, array( __CLASS__, 'uninstall'	) );
		
		add_action( 'init', 			array( __CLASS__, 'translate' 			), 10 );
		add_action( 'plugins_loaded', 	array( __CLASS__, 'check_requirements' 	), 0  );
		add_action( 'bp_include', 		array( __CLASS__, 'start' 				), 10 );
	}

	/**
	 * Load all BP related files
	 * 
	 * Attached to bp_include. Stops the plugin if certain conditions are not met.
	 * 
	 * @since 	1.0
	 * @access 	public
	 */
	public function start() {
		if( self::$active === false )
			return false;
		
		// core component
		require( BPSHOP_ABSPATH .'core/bpshop-component.php' );
	}
	
	/**
	 * Check for required versions
	 * 
	 * Checks for WP, BP, PHP and woocommerce versions
	 * 
	 * @since 	1.0
	 * @access 	private
	 * @global 	string 	$wp_version 	Current WordPress version
	 * @return 	boolean
	 */
	public function check_requirements() {		
		global $wp_version;

		$error = false;
		
		// BuddyPress checks
		if( ! defined( 'BP_VERSION' ) )	{
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop needs BuddyPress to be installed. <a href="%s">Download it now</a>!\', "bpshop" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
			$error = true;
		}
		elseif( version_compare( BP_VERSION, self::MIN_BP, '>=' ) == false )
		{
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop works only under BuddyPress %s or higher. <a href="%s">Upgrade now</a>!\', "bpshop" ) . \'</strong></p></div>\', BPSHOP_Loader::MIN_BP, admin_url("update-core.php") );' ) );
			$error = true;
		}
		
		// Woocommerce checks
		if( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop needs WooCommerce to be installed. <a href="%s">Download it now</a>!\', "bpshop" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
			$error = true;
		}		
		elseif( version_compare( WOOCOMMERCE_VERSION, self::MIN_WOO, '>=' ) == false ) {
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop works only under WooCommerce %s or higher. <a href="%s">Upgrade now</a>!\', "bpshop" ) . \'</strong></p></div>\', BPSHOP_Loader::MIN_WOO, admin_url("update-core.php") );' ) );
			$error = true;
		}
		
		// WordPress check
		if( version_compare( $wp_version, self::MIN_WP, '>=' ) == false ) {
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop works only under WordPress %s or higher. <a href="%s">Upgrade now</a>!\', "bpshop" ) . \'</strong></p></div>\', BPSHOP_Loader::MIN_WP, admin_url("update-core.php") );' ) );
			$error = true;
		}
		
		self::$active = ( ! $error ) ? true : false;
	}

	/**
	 * Load the language file
	 * 
	 * @since 	1.0
	 * @uses 	load_plugin_textdomain()
	 */
	public function translate()	{
		load_plugin_textdomain( 'bpshop', false, dirname( self::$plugin_name ) .'/languages/' );
	}

	/**
	 * Runs when the plugin is first activated
	 * 
	 * @since 	1.0
	 */
	public function activate() {
		include_once( dirname( __FILE__ ) .'/admin/bpshop-activate.php' );
		bpshop_activate();
	}
	
	/**
	 * Runs when the plugin is uninstalled
	 * 
	 * @since 	1.0
	 */
	public function uninstall()	{
		include_once( dirname( __FILE__ ) .'/admin/bpshop-activate.php' );
		bpshop_cleanup();
	}
		
	/**
	 * Declare all constants
	 * 
	 * @since 	1.0
	 * @access 	private
	 */
	private function constants() {
		define( 'BPSHOP_PLUGIN', 	self::$plugin_name );
		define( 'BPSHOP_VERSION',	self::VERSION );
		define( 'BPSHOP_FOLDER',	plugin_basename( dirname( __FILE__ ) ) );
		define( 'BPSHOP_ABSPATH',	trailingslashit( str_replace( "\\", "/", WP_PLUGIN_DIR .'/'. BPSHOP_FOLDER ) ) );
		define( 'BPSHOP_URLPATH',	trailingslashit( plugins_url( '/'. BPSHOP_FOLDER ) ) );
	}
}

// Get it on!!
BPSHOP_Loader::init();

/**
 * The functions below do not have any filters, but can be redefined
 * Needs to happen here, so as not to cause any errors
 * Changing these functions ensures that the correct JS is being loaded
 * 
 * @todo	Write a fix to use filters rather than redeclaring these functions
 * 			which could potentially create conflicts with other plugins
 */

if( ! function_exists( 'is_checkout' ) ) :
/**
 * Check if we're on a checkout page
 * 
 * @since 	1.0.5
 */
function is_checkout() {
	if( bp_is_current_component( 'shop' ) && bp_is_action_variable( 'checkout' ) )
		return true;
	
	return false;
}
endif;

if( ! function_exists( 'is_cart' ) ) :
/**
 * Check if we're on a cart page
 * 
 * @since 	1.0.5
 */
function is_cart() {
	if( bp_is_current_component( 'shop' ) && ! bp_action_variables() )
		return true;
	
	return false;
}
endif;

if( ! function_exists( 'is_account_page' ) ) :
/**
 * Check if we're on an account page
 * 
 * @since 	1.0.5
 */
function is_account_page() {
	if( bp_is_current_component( 'shop' ) && bp_is_action_variable( 'history' ) )
		return true;
	
	return false;
}
endif;