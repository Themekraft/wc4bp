<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class BPSHOP_Loader
{
	/**
	 * The plugin version
	 */
	const VERSION 	= '1.1';

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

		// register_activation_hook( self::$plugin_name, array( __CLASS__, 'activate'  ) );
		// register_deactivation_hook(  self::$plugin_name, array( __CLASS__, 'uninstall'	) );

		add_action( 'plugins_loaded', 	array( __CLASS__, 'translate' 			));
		add_action( 'bp_include', 		array( __CLASS__, 'check_requirements' 	),  0 );
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
	 * Checks for WP, BP, PHP and Woocommerce versions
	 *
	 * @since 	1.0
	 * @access 	private
	 * @global 	string 	$wp_version 	Current WordPress version
	 * @return 	boolean
	 */
	public function check_requirements() {
		global $wp_version, $wpdb;

		$error = $check_wc = false;

		// only check WC on the main site on MS installations
		$check_wc = true;
		if( is_multisite() ) :
			$check_wc = false;
			if( defined( 'BLOG_ID_CURRENT_SITE' ) && $wpdb->blogid != BLOG_ID_CURRENT_SITE )
				$check_wc = true;
		endif;
		
		// BuddyPress checks
		if( ! defined( 'BP_VERSION' )){ 
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop needs BuddyPress to be installed. <a href="%s">Download it now</a>!\', "bpshop" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
			$error = true;
		}
		elseif( version_compare( BP_VERSION, self::MIN_BP, '>=' ) == false )
		{
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop works only under BuddyPress %s or higher. <a href="%s">Upgrade now</a>!\', "bpshop" ) . \'</strong></p></div>\', BPSHOP_Loader::MIN_BP, admin_url("update-core.php") );' ) );
			$error = true;
		}
		if( defined( 'BP_VERSION' )){ 
			if(function_exists('bp_is_active')){
				if(!bp_is_active('settings')){
					add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop works only with the BuddyPress Account Settings Component activated <a href="%s">Activate now</a>!\', "bpshop" ) . \'</strong></p></div>\', admin_url("options-general.php?page=bp-components") );' ) );
					$error = true;	
				}
			}
		}
		// Woocommerce checks
		if( $check_wc ) :
			if( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
				add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop needs WooCommerce to be installed. <a href="%s">Download it now</a>!\', "bpshop" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
				$error = true;
			}
			elseif( version_compare( WOOCOMMERCE_VERSION, self::MIN_WOO, '>=' ) == false ) {
				add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop works only under WooCommerce %s or higher. <a href="%s">Upgrade now</a>!\', "bpshop" ) . \'</strong></p></div>\', BPSHOP_Loader::MIN_WOO, admin_url("update-core.php") );' ) );
				$error = true;
			}
		endif;

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
		load_plugin_textdomain( 'bpshop', false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
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
		define('BPSHOP_ABSPATH_TEMPLATE_PATH', BPSHOP_ABSPATH . 'templates/');
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
	if( is_user_logged_in() ) :
		if( bp_is_current_component( 'shop' ) && bp_is_action_variable( 'checkout' ) ) :
			return true;
		endif;
	else :
		if( is_page( woocommerce_get_page_id( 'checkout' ) ) || is_page( woocommerce_get_page_id( 'pay' ) ) ) :
			return true;
		endif;
	endif;

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
	if( is_user_logged_in() ) :
		if( bp_is_current_component( 'shop' ) && ! bp_action_variables() ) :
			return true;
		endif;
	else :
		if( is_page( woocommerce_get_page_id( 'cart' ) ) ) :
			return true;
		endif;
	endif;

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
	if( is_user_logged_in() ) :
		if( bp_is_current_component( 'shop' ) && bp_is_action_variable( 'history' ) ) :
			return true;
		endif;
	else :
		if( is_page( woocommerce_get_page_id( 'myaccount' ) ) || is_page( woocommerce_get_page_id( 'edit_address' ) ) || is_page( woocommerce_get_page_id( 'view_order' ) ) || is_page( woocommerce_get_page_id( 'change_password' ) ) ) :
			return true;
		endif;
	endif;

	return false;
}
endif;