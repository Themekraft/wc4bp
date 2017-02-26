<?php
/**
 * Plugin Name: WC4BP -> WooCommerce BuddyPress Integration
 * Plugin URI:  http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * Description: Integrates a WooCommerce installation with a BuddyPress social network
 * Author:      WC4BP Integration Dev Team ;)
 * Version:     2.5
 * Licence: GPLv3
 * Text Domain: wc4bp
 * Domain Path: /languages
 *
 * @fs_premium_only /class/wc4bp-myaccount.php,
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

$GLOBALS['wc4bp_loader'] = new WC4BP_Loader();

class WC4BP_Loader {
	/**
	 * The plugin version
	 */
	const VERSION = '2.5';
	
	/**
	 * Minimum required WP version
	 */
	const MIN_WP = '4.0';
	
	/**
	 * Minimum required BP version
	 */
	const MIN_BP = '2.2';
	
	/**
	 * Minimum required woocommerce version
	 */
	const MIN_WOO = '2.4';
	
	/**
	 * Name of the plugin folder
	 */
	static $plugin_name;
	
	/**
	 * Can the plugin be executed
	 */
	static $active = false;
	
	/**
	 * @var Freemius
	 */
	public static $freemius;
	
	/**
	 * Initiate the class
	 *
	 * @package WooCommerce for BuddyPress
	 * @since 0.1-beta
	 */
	
	public function __construct() {
		self::$plugin_name = plugin_basename( __FILE__ );
		$this->constants();
		require_once plugin_dir_path( __FILE__ ) . 'class/class-tgm-plugin-activation.php';
		require_once plugin_dir_path( __FILE__ ) . 'class/wc4bp-base.php';
		require_once plugin_dir_path( __FILE__ ) . 'class/wc4bp-manager.php';
		require_once plugin_dir_path( __FILE__ ) . 'class/wc4bp-required.php';
		new WC4BP_Required();
		
		if ( wc4bp_Manager::is_woocommerce_active() && wc4bp_Manager::is_buddypress_active() ) {
			// Init Freemius.
			self::$freemius = $this->wc4bp_fs();
			
			new wc4bp_Manager();
			
			// Run the activation function
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			
			add_action( 'plugins_loaded', array( $this, 'update' ), 10 );
			add_action( 'plugins_loaded', array( $this, 'translate' ) );
			
			/**
			 * Deletes all data if plugin deactivated
			 */
			register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );
		}
	}
	
	// Create a helper function for easy SDK access.
	public function wc4bp_fs() {
		global $wc4bp_fs;
		
		if ( ! isset( $wc4bp_fs ) ) {
			// Include Freemius SDK.
			require_once WC4BP_ABSPATH_ADMIN_PATH . 'resources/freemius/start.php';
			
			$wc4bp_fs = fs_dynamic_init( array(
				'id'                  => '425',
				'slug'                => 'wc4bp',
				'type'                => 'plugin',
				'public_key'          => 'pk_71d28f28e3e545100e9f859cf8554',
				'is_premium'          => true,
				'has_premium_version' => false,
				'has_addons'          => true,
				'has_paid_plans'      => true,
				'menu'                => array(
					'slug'    => 'wc4bp-options-page',
					'support' => false,
				),
				// Set the SDK to work in a sandbox mode (for development & testing).
				// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
				'secret_key'          => 'sk_ccE(cjH4?%J)wXa@h2vV^g]jAeY$i',
			) );
		}
		
		return $wc4bp_fs;
	}
	
	/**
	 * Declare all constants
	 *
	 * @since    1.0
	 * @access    private
	 */
	private function constants() {
		define( 'WC4BP_PLUGIN', self::$plugin_name );
		define( 'WC4BP_VERSION', self::VERSION );
		define( 'WC4BP_FOLDER', plugin_basename( dirname( __FILE__ ) ) );
		define( 'WC4BP_ABSPATH', trailingslashit( str_replace( "\\", "/", WP_PLUGIN_DIR . '/' . WC4BP_FOLDER ) ) );
		define( 'WC4BP_URLPATH', trailingslashit( plugins_url( '/' . WC4BP_FOLDER ) ) );
		define( 'WC4BP_ABSPATH_CLASS_PATH', WC4BP_ABSPATH . 'class/' );
		define( 'WC4BP_ABSPATH_TEMPLATE_PATH', WC4BP_ABSPATH . 'templates/' );
		define( 'WC4BP_ABSPATH_ADMIN_PATH', WC4BP_ABSPATH . 'admin' . DIRECTORY_SEPARATOR );
		define( 'WC4BP_ABSPATH_ADMIN_VIEWS_PATH', WC4BP_ABSPATH_ADMIN_PATH . 'views' . DIRECTORY_SEPARATOR );
		define( 'WC4BP_CSS', WC4BP_URLPATH . '/admin/css/' );
		define( 'WC4BP_JS', WC4BP_URLPATH . '/admin/js/' );
	}
	
	/**
	 * @return Freemius
	 */
	public static function getFreemius() {
		return self::$freemius;
	}
	
	/**
	 * Load the language file
	 *
	 * @since    1.0
	 * @uses    load_plugin_textdomain()
	 */
	public function translate() {
		load_plugin_textdomain( 'wc4bp', false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
	}
	
	/*
	 *  Update function from version 1.3.8 to 1.4
	 */
	public function update() {
		if ( version_compare( WC4BP_VERSION, '1.4', '<' ) ) {
			
			$billing  = bp_get_option( 'wc4bp_billing_address_ids' );
			$shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
			
			$billing_changed  = false;
			$shipping_changed = false;
			
			if ( isset( $billing['address'] ) ) {
				$billing['address_1'] = $billing['address'];
				unset( $billing['address'] );
				$billing_changed = true;
			}
			
			if ( isset( $billing['address-2'] ) ) {
				$billing['address_2'] = $billing['address-2'];
				unset( $billing['address-2'] );
				$billing_changed = true;
			}
			
			if ( isset( $shipping['address'] ) ) {
				$shipping['address_1'] = $shipping['address'];
				unset( $shipping['address'] );
				$shipping_changed = true;
			}
			if ( isset( $shipping['address-2'] ) ) {
				$shipping['address_2'] = $shipping['address-2'];
				unset( $shipping['address-2'] );
				$shipping_changed = true;
			}
			
			if ( $billing_changed == true ) {
				bp_update_option( 'wc4bp_billing_address_ids', $billing );
			}
			
			if ( $shipping_changed == true ) {
				bp_update_option( 'wc4bp_shipping_address_ids', $shipping );
			}
		}
	}
	
	/**
	 * Generate the default data arrays
	 */
	public function activation() {
		if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$starter_plan_id ) ) {
			//Add all woo my account pages
			WC4BP_MyAccount::add_all_endpoints();
			flush_rewrite_rules();
		}
		include_once( dirname( __FILE__ ) . '/admin/wc4bp-activate.php' );
		wc4bp_activate();
	}
	
	/**
	 * Deletes all data if plugin deactivated
	 *
	 * @return void
	 */
	public function uninstall() {
		global $wpdb, $blog_id;
		if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$starter_plan_id ) ) {
			//delete woo my account pages
			WC4BP_MyAccount::remove_all_endpoints();
		}
		$wc4bp_options_delete = get_option( 'wc4bp_options_delete' );
		if ( $wc4bp_options_delete ) {
			include_once( dirname( __FILE__ ) . '/admin/wc4bp-activate.php' );
			wc4bp_cleanup();
		}
	}
}
