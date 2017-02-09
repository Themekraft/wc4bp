<?php
/**
 * Main file to handle the admin pages
 *
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

/**
 * Class wc4bp_admin handle the admin pages
 */
class wc4bp_admin {
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wc4bp_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'wc4bp_register_admin_settings' ) );
		
		require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-pages.php' );
		require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-sync.php' );
		require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-delete.php' );
		require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-ajax.php' );
		new wc4bp_admin_ajax();
	}
	
	/**
	 * Adding the Admin Page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
	public function wc4bp_admin_menu() {
		add_menu_page( __( 'WooCommerce for BuddyPress', 'wc4bp' ), 'WC4BP Settings', 'manage_options', 'wc4bp-options-page', array( $this, 'wc4bp_screen' ) );
		do_action( 'wc4bp_add_submenu_page' );
		
		$admin_sync    = new wc4bp_admin_sync();
		$wc4bp_options = get_option( 'wc4bp_options' );
		if ( ! isset( $wc4bp_options['tab_sync_disabled'] ) ) {
			add_submenu_page( 'wc4bp-options-page', __( 'WC4BP Profile Fields Sync', 'wc4bp' ), 'Profile Fields Sync', 'manage_options', 'wc4bp-options-page-sync', array( $admin_sync, 'wc4bp_screen_sync' ) );
		}
		
		$admin_pages = new wc4bp_admin_pages();
		add_submenu_page( 'wc4bp-options-page', __( 'WC4BP Integrate Pages', 'wc4bp' ), 'Integrate Pages', 'manage_options', 'wc4bp-options-page-pages', array( $admin_pages, 'wc4bp_screen_pages' ) );
		
		$admin_delete = new wc4bp_admin_delete();
		add_submenu_page( 'wc4bp-options-page', __( 'Delete', 'wc4bp' ), 'Delete', 'manage_options', 'wc4bp-options-page-delete', array( $admin_delete, 'wc4bp_screen_delete' ) );
	}
    
	/**
	 * The Admin Page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
	public function wc4bp_screen() {
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_screen.php' );
	}
	
	/**
	 * Register the admin settings
	 *
	 * @author Sven Lehnert
	 * @package TK Loop Designer
	 * @since 1.0
	 */
	public function wc4bp_register_admin_settings() {
		
		register_setting( 'wc4bp_options', 'wc4bp_options' );
		// Settings fields and sections
		add_settings_section( 'section_general', '', '', 'wc4bp_options' );
		add_settings_section( 'section_general2', '', '', 'wc4bp_options' );
		
		add_settings_field( 'tabs_shop', __( '<b>Shop Settings</b>', 'wc4bp' ), array( $this, 'wc4bp_shop_tabs' ), 'wc4bp_options', 'section_general' );
		add_settings_field( 'tabs_enable', __( '<b>Remove My Account Tabs</b>', 'wc4bp' ), array( $this, 'wc4bp_my_account_tabs_enable' ), 'wc4bp_options', 'section_general' );
		add_settings_field( 'tabs_disabled', __( '<b>Remove Shop Tabs</b>', 'wc4bp' ), array( $this, 'wc4bp_shop_tabs_disable' ), 'wc4bp_options', 'section_general' );
		add_settings_field( 'profile sync', __( '<b>Turn off the profile sync</b>', 'wc4bp' ), array( $this, 'wc4bp_turn_off_profile_sync' ), 'wc4bp_options', 'section_general' );
		add_settings_field( 'overwrite', __( '<b>Overwrite the Content of your Shop Home/Main Tab</b>', 'wc4bp' ), array( $this, 'wc4bp_overwrite_default_shop_home_tab' ), 'wc4bp_options', 'section_general' );
		add_settings_field( 'template', __( '<b>Change the page template to be used for the attached pages.</b>', 'wc4bp' ), array( $this, 'wc4bp_page_template' ), 'wc4bp_options', 'section_general' );
		
	}
	
	/**
	 * Shop settings view
	 */
	public function wc4bp_shop_tabs() {
		$wc4bp_options = get_option( 'wc4bp_options' );
		
		$tab_activity_disabled = 0;
		if ( isset( $wc4bp_options['tab_activity_disabled'] ) ) {
			$tab_activity_disabled = $wc4bp_options['tab_activity_disabled'];
		}
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_shop_tabs.php' );
	}
	
	/**
	 * Tun off woo my account tabs view
	 */
	public function wc4bp_my_account_tabs_enable() {
		$wc4bp_options = get_option( 'wc4bp_options' );
		$end_points    = wc_get_account_menu_items();
		
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_my_account_tabs.php' );
	}
	
	/**
	 * Do you want to use the WordPress Customizer? This is the option to turn on/off the WordPress Customizer Support.
	 *
	 * @author Sven Lehnert
	 * @since 1.0
	 */
	public function wc4bp_shop_tabs_disable() {
		$wc4bp_options = get_option( 'wc4bp_options' );
		
		$tab_cart_disabled = 0;
		if ( isset( $wc4bp_options['tab_cart_disabled'] ) ) {
			$tab_cart_disabled = $wc4bp_options['tab_cart_disabled'];
		}
		
		$tab_checkout_disabled = 0;
		if ( isset( $wc4bp_options['tab_checkout_disabled'] ) ) {
			$tab_checkout_disabled = $wc4bp_options['tab_checkout_disabled'];
		}
		
		$tab_history_disabled = 0;
		if ( isset( $wc4bp_options['tab_history_disabled'] ) ) {
			$tab_history_disabled = $wc4bp_options['tab_history_disabled'];
		}
		
		$tab_track_disabled = 0;
		if ( isset( $wc4bp_options['tab_track_disabled'] ) ) {
			$tab_track_disabled = $wc4bp_options['tab_track_disabled'];
		}
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_shop_disable.php' );
	}
	
	/**
	 * Tun off profile tabs view
	 */
	public function wc4bp_turn_off_profile_sync() {
		$wc4bp_options = get_option( 'wc4bp_options' );
		
		$tab_sync_disabled = 0;
		if ( isset( $wc4bp_options['tab_sync_disabled'] ) ) {
			$tab_sync_disabled = $wc4bp_options['tab_sync_disabled'];
		}
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_profile_sync.php' );
		
		include_once( WC4BP_ABSPATH_ADMIN_PATH . 'wc4bp-activate.php' );
		if ( isset( $tab_sync_disabled ) && true == $tab_sync_disabled ) {
			wc4bp_cleanup();
		} else {
			wc4bp_activate();
		}
		
		
	}
	
	/**
	 * View to select the tabs to use as home
	 */
	public function wc4bp_overwrite_default_shop_home_tab() {
		$wc4bp_options       = get_option( 'wc4bp_options' );
		$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
		
		$woo_my_account = WC4BP_MyAccount::get_active_endpoints();
		if ( ! empty( $woo_my_account ) ) {
			foreach ( $woo_my_account as $active_page_key => $active_page_name ) {
				$wc4bp_pages_options["selected_pages"][ 'wc4bp_' . $active_page_key ] = array(
					'tab_name' => $active_page_name
				);
			}
		}
		
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_shop_home.php' );
	}
	
	/**
	 * View to select the page view template by default
	 */
	public function wc4bp_page_template() {
		$wc4bp_options = get_option( 'wc4bp_options' );
		
		$page_template = '';
		if ( ! empty( $wc4bp_options['page_template'] ) ) {
			$page_template = $wc4bp_options['page_template'];
		}
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_page_template.php' );
		
		submit_button();
	}
}