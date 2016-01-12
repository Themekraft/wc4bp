<?php
/**
 * Plugin Name: WC4BP -> WooCommerce BuddyPress Integration
 * Plugin URI:  http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * Description: Integrates a WooCommerce installation with a BuddyPress social network
 * Author:      WC4BP Integration Dev Team ;)
 * Version:     2.3.4
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

 // Needs to be rewritetn in Otto style ;-)
 if( ! defined( 'BP_VERSION' )){
	add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration needs BuddyPress to be installed. <a href="%s">Download it now</a>!\', " wc4bp" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
	return;
}

$GLOBALS['wc4bp_loader'] = new WC4BP_Loader();

class WC4BP_Loader {
	/**
	 * The plugin version
	 */
	const VERSION 	= '2.3.4';

    /**
	 * Minimum required WP version
	 */
	const MIN_WP 	= '4.0';

	/**
	 * Minimum required BP version
	 */
	const MIN_BP 	= '2.2';

	/**
	 * Minimum required woocommerce version
	 */
	const MIN_WOO 	= '2.4';

	/**
	 * Name of the plugin folder
	 */
	static $plugin_name;

	/**
	 * Can the plugin be executed
	 */
	static $active = false;

	/**
	 * Initiate the class
	 *
	 * @package WooCommerce for BuddyPress
	 * @since 0.1-beta
	 */

	public function __construct() {
		self::$plugin_name = plugin_basename( __FILE__ );

    add_action('bp_include'						, array($this, 'check_requirements') , 0);

		// Run the activation function
		register_activation_hook( __FILE__          , array( $this, 'activation' ));

		$this->constants();

    add_action('admin_enqueue_scripts'          , array($this, 'wc4bp_admin_js')    , 10 );

    add_action('plugins_loaded'					, array($this, 'update'     )       , 10 );
		add_action('plugins_loaded'					, array($this, 'translate'  ));
		add_action('bp_include'						, array($this, 'includes'   )       , 10 );

        /**
        * Deletes all data if plugin deactivated
        */
		register_deactivation_hook( __FILE__        , array( $this, 'uninstall' ));

	}

	/**
	 * Load all BP related files
	 *
	 * Attached to bp_include. Stops the plugin if certain conditions are not met.
	 *
	 * @since 	1.0
	 * @access 	public
	 */
	public function includes() {
		if( self::$active === false )
			return false;

		// core component
		require( WC4BP_ABSPATH .'core/wc4bp-component.php' );

		if (is_admin()){

			// API License Key Registration Form
            require_once( plugin_dir_path( __FILE__ ) . 'admin/admin.php');
            require_once( plugin_dir_path( __FILE__ ) . 'admin/admin-sync.php');
            require_once( plugin_dir_path( __FILE__ ) . 'admin/admin-pages.php');
            require_once( plugin_dir_path( __FILE__ ) . 'admin/admin-delete.php');
            require_once( plugin_dir_path( __FILE__ ) . 'admin/admin-ajax.php');

		}

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
		if( ! defined( 'BP_VERSION' )) {
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration needs BuddyPress to be installed. <a href="%s">Download it now</a>!\', " wc4bp" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
			$error = true;
		} elseif( version_compare( BP_VERSION, self::MIN_BP, '>=' ) == false ) {
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration works only under BuddyPress %s or higher. <a href="%s">Upgrade now</a>!\', " wc4bp" ) . \'</strong></p></div>\', WC4BP_Loader::MIN_BP, admin_url("update-core.php") );' ) );
			$error = true;
		}

		if( defined( 'BP_VERSION' )) {
			if(function_exists('bp_is_active')) {
				if(! bp_is_active('settings') && ! bp_is_active('xprofile') ) {
					add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration works only with the BuddyPress Extended Profiles and Account Settings Component activated <a href="%s">Activate now</a>!\', " wc4bp" ) . \'</strong></p></div>\', admin_url("options-general.php?page=bp-components") );' ) );
					$error = true;
				}

				if( ! bp_is_active('settings') && bp_is_active('xprofile') ) {
					add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration works only with the BuddyPress Account Settings Component activated <a href="%s">Activate now</a>!\', " wc4bp" ) . \'</strong></p></div>\', admin_url("options-general.php?page=bp-components") );' ) );
					$error = true;
				}

				if( bp_is_active('settings') && ! bp_is_active('xprofile')  ) {
					add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration works only with the BuddyPress Extended Profiles Component activated <a href="%s">Activate now</a>!\', " wc4bp" ) . \'</strong></p></div>\', admin_url("options-general.php?page=bp-components") );' ) );
					$error = true;
				}
			}
		}
		// Woocommerce checks
		if( $check_wc ) :
			if( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
				add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration needs WooCommerce to be installed. <a href="%s">Download it now</a>!\', " wc4bp" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
				$error = true;
			}
			elseif( version_compare( WOOCOMMERCE_VERSION, self::MIN_WOO, '>=' ) == false ) {
				add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration works only under WooCommerce %s or higher. <a href="%s">Upgrade now</a>!\', " wc4bp" ) . \'</strong></p></div>\', WC4BP_Loader::MIN_WOO, admin_url("update-core.php") );' ) );
				$error = true;
			}
		endif;

		// WordPress check
		if( version_compare( $wp_version, self::MIN_WP, '>=' ) == false ) {
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration works only under WordPress %s or higher. <a href="%s">Upgrade now</a>!\', " wc4bp" ) . \'</strong></p></div>\', WC4BP_Loader::MIN_WP, admin_url("update-core.php") );' ) );
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
		load_plugin_textdomain( 'wc4bp', false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
	}

	/**
	 * Declare all constants
	 *
	 * @since 	1.0
	 * @access 	private
	 */
	private function constants() {
        define( 'WC4BP_PLUGIN'                  , 	self::$plugin_name );
		define( 'WC4BP_VERSION'                 ,	self::VERSION );
		define( 'WC4BP_FOLDER'                  ,	plugin_basename( dirname( __FILE__ ) ) );
		define( 'WC4BP_ABSPATH'                 ,	trailingslashit( str_replace( "\\", "/", WP_PLUGIN_DIR .'/'. WC4BP_FOLDER ) ) );
		define( 'WC4BP_URLPATH'                 ,	trailingslashit( plugins_url( '/'. WC4BP_FOLDER ) ) );
		define( 'WC4BP_ABSPATH_TEMPLATE_PATH'   ,   WC4BP_ABSPATH . 'templates/');
	}

	/**
	 * Generate the default data arrays
	 */
	public function activation() {
        include_once( dirname( __FILE__ ) .'/admin/wc4bp-activate.php' );
		wc4bp_activate();
	}

    /*
     *  Update function from version 1.3.8 to 1.4
     */
    public function update(){
        if(version_compare(WC4BP_VERSION, '1.4', '<')) {

            $billing  = bp_get_option( 'wc4bp_billing_address_ids'  );
            $shipping = bp_get_option( 'wc4bp_shipping_address_ids' );

            $billing_changed = false;
            $shipping_changed = false;

            if( isset($billing['address']) ) {
                $billing['address_1'] = $billing['address'];
                unset($billing['address']);
                $billing_changed = true;
            }

            if( isset($billing['address-2']) ) {
                $billing['address_2'] = $billing['address-2'];
                unset($billing['address-2']);
                $billing_changed = true;
            }

            if( isset($shipping['address']) ) {
                $shipping['address_1'] = $shipping['address'];
                unset($shipping['address']);
                $shipping_changed = true;
            }
            if( isset($shipping['address-2']) ) {
                $shipping['address_2'] = $shipping['address-2'];
                unset($shipping['address-2']);
                $shipping_changed = true;
            }

            if ($billing_changed == true)
                bp_update_option('wc4bp_billing_address_ids', $billing);

            if ($shipping_changed == true)
                bp_update_option('wc4bp_shipping_address_ids', $shipping);
        }
    }

	/**
	 * Deletes all data if plugin deactivated
	 * @return void
	 */
	public function uninstall() {
		global $wpdb, $blog_id;

        $wc4bp_options_delete = get_option('wc4bp_options_delete');

        if($wc4bp_options_delete == 'delete') {
            include_once(dirname(__FILE__) . '/admin/wc4bp-activate.php');
            wc4bp_cleanup();
        }
	}

	/**
	 * Enqueue admin JS and CSS
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.0
	 */

	public function wc4bp_admin_js(){

		add_thickbox();
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-effects-core');

        wp_enqueue_script('wc4bp_admin_js', plugins_url('/admin/js/admin.js', __FILE__), array('jquery','jquery-ui-core','jquery-ui-widget', 'jquery-ui-tabs'));
        wp_enqueue_style('wc4bp_admin_css', plugins_url('/admin/css/admin.css', __FILE__));

    }
}

/**
 * Load the WooCommerce API Manager functions and classes
 */

function wc4bp_plugin_file() {
    return __FILE__;
}

function wc4bp_plugin_url() {
    return plugins_url( '/', __FILE__ );
}

function wc4bp_plugin_path() {
    return plugin_dir_path( __FILE__ );
}

function wc4bp_plugin_name() {
    return untrailingslashit( plugin_basename( __FILE__ ) );
}

require_once( plugin_dir_path( __FILE__ ) . 'includes/resources/api-manager/api-manager.php');

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
    $wc4bp_options			= get_option( 'wc4bp_options' );

	if( is_user_logged_in() && ! isset($wc4bp_options['tab_cart_disabled'] )) :

        if( bp_is_current_component( 'shop' ) && (bp_is_action_variable( 'checkout' ) || bp_is_action_variable( 'cart' ) )) :
			return true;
        endif;
	else :
        return is_page( wc_get_page_id( 'checkout' ) ) ? true : false;
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
    $wc4bp_options			= get_option( 'wc4bp_options' );

    if( is_user_logged_in() && ! isset($wc4bp_options['tab_cart_disabled'] )) :
		if( bp_is_current_component( 'shop' ) && ! bp_action_variables() ) :
			return true;
		endif;
	else :
		if( is_page( wc_get_page_id( 'cart' ) ) ) :
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
		if( bp_is_current_component( 'shop' ) && bp_is_action_variable( 'checkout' ) ) :
    		return true;
		endif;
    else :
        return is_page( wc_get_page_id( 'myaccount' ) ) || apply_filters( 'woocommerce_is_account_page', false ) ? true : false;
    endif;

	return false;
}
endif;

if ( ! function_exists( 'is_order_received_page' ) ) {

    /**
     * is_order_received_page - Returns true when viewing the order received page.
     *
     * @access public
     * @return bool
     */
    function is_order_received_page() {
        global $wp;

        if( is_user_logged_in() ) :
            if( bp_is_current_component( 'shop' ) && (bp_is_action_variable( 'checkout' ) || bp_is_action_variable( 'cart' ) )) :
                return true;
            endif;
        else :
            if( is_page( wc_get_page_id( 'checkout' ) ) && isset( $wp->query_vars['order-received'] ) ):
                return true;
            endif;
        endif;

        return false;


    }
}
