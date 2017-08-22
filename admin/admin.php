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
class wc4bp_admin extends wc4bp_base {
	
	public static $slug = 'wc4bp-options-page';
	
	public function __construct() {
		parent::__construct();
		add_action( 'admin_menu', array( $this, 'wc4bp_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'wc4bp_register_admin_settings' ) );
		
		require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-pages.php' );
		require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-sync.php' );
		require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-delete.php' );
		require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-ajax.php' );
		new wc4bp_admin_ajax();
	}
	
	/**
	 * @return string
	 */
	public static function getSlug() {
		return self::$slug;
	}
	
	/**
	 * Adding the Admin Page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
	public function wc4bp_admin_menu() {
		add_menu_page( __( 'WooCommerce for BuddyPress', 'wc4bp' ), __( 'WC4BP Settings', 'wc4bp' ), 'manage_options', self::getSlug(), array( $this, 'wc4bp_screen' ) );
		do_action( 'wc4bp_add_submenu_page' );
	}
	
	/**
	 * The Admin Page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
	public function wc4bp_screen() {
		$active_tab = 'generic';
		if ( ! empty( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		}
		switch ( $active_tab ) {
			case 'generic';
				include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_screen.php' );
				break;
			case 'page-sync';
				$admin_sync    = new wc4bp_admin_sync();
				$wc4bp_options = get_option( 'wc4bp_options' );
				if ( ! isset( $wc4bp_options['tab_sync_disabled'] ) ) {
					$admin_sync->wc4bp_screen_sync( $active_tab );
				}
				break;
			case 'integrate-pages';
				$admin_pages = new wc4bp_admin_pages();
				$admin_pages->wc4bp_screen_pages( $active_tab );
				break;
			case 'delete';
				$admin_delete = new wc4bp_admin_delete();
				$admin_delete->wc4bp_screen_delete( $active_tab );
				break;
		}
	}
	
	/**
	 * Register the admin settings
	 *
	 * @author Sven Lehnert
	 * @package TK Loop Designer
	 * @since 1.0
	 */
	public function wc4bp_register_admin_settings() {
		register_setting( 'wc4bp_options_delete', 'wc4bp_options_delete' );
		register_setting( 'wc4bp_options_pages', 'wc4bp_options_pages' );
		register_setting( 'wc4bp_options', 'wc4bp_options' );
		register_setting( 'wc4bp_options_sync', 'wc4bp_options_sync' );
		
		add_settings_section( 'section_general', '', '', 'wc4bp_options' );
		add_settings_section( 'section_general2', '', '', 'wc4bp_options' );
		
		add_settings_field( 'tabs_shop', __( '<b>Shop Settings</b>', 'wc4bp' ), array( $this, 'wc4bp_shop_tabs' ), 'wc4bp_options', 'section_general' );
		add_settings_field( 'tabs_enable', __( '<b>Remove My Account Tabs</b>', 'wc4bp' ), array( $this, 'wc4bp_my_account_tabs_enable' ), 'wc4bp_options', 'section_general' );
		add_settings_field( 'tabs_disabled', __( '<b>Remove Shop Tabs</b>', 'wc4bp' ), array( $this, 'wc4bp_shop_tabs_disable' ), 'wc4bp_options', 'section_general' );
		add_settings_field( 'profile sync', __( '<b>Turn off the profile sync</b>', 'wc4bp' ), array( $this, 'wc4bp_turn_off_profile_sync' ), 'wc4bp_options', 'section_general' );
		add_settings_field( 'overwrite', __( '<b>Overwrite the Content of your Shop Home/Main Tab</b>', 'wc4bp' ), array( $this, 'wc4bp_overwrite_default_shop_home_tab' ), 'wc4bp_options', 'section_general' );
		
	}
	
	/**
	 * Shop settings view
	 */
	public function wc4bp_shop_tabs() {

		$wc4bp_options         = get_option( 'wc4bp_options' );
		$tab_activity_disabled = 0;
        if ( WC4BP_Loader::getFreemius()->is__premium_only() ) {
            //Get all actives tabs and custom pages
            $wc4bp_pages_options = $this->get_pages_option();
            // If all the tabs are disabled and there is not custom pages, Turn off 'Shop'
            if (is_array($wc4bp_pages_options) && count($wc4bp_pages_options) == 0) {
                $tab_activity_disabled = 1;
                $wc4bp_options['tab_activity_disabled'] = 1;
                update_option('wc4bp_options', $wc4bp_options);
            } else {

                if (isset($wc4bp_options['tab_activity_disabled'])) {
                    $tab_activity_disabled = $wc4bp_options['tab_activity_disabled'];
                }

            }
        }
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_shop_tabs.php' );
	}
	
	/**
	 * Tun off woo my account tabs view
	 */
	public function wc4bp_my_account_tabs_enable() {
		$wc4bp_options = get_option( 'wc4bp_options' );
		$end_points    = wc_get_account_menu_items();
		
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_my_account_tabs.php' );
	}
	
	/**
	 * Do you want to use the WordPress Customizer? This is the option to turn on/off the WordPress Customizer Support.
	 *
	 * @author Sven Lehnert
	 * @since 1.0
	 */
	public function wc4bp_shop_tabs_disable() {
		$wc4bp_options         = get_option( 'wc4bp_options' );
		$tab_cart_disabled     = 0;
		$tab_checkout_disabled = 0;
		$tab_history_disabled  = 0;
		$tab_track_disabled    = 0;
		if ( WC4BP_Loader::getFreemius()->is__premium_only() ) {
			
			if ( isset( $wc4bp_options['tab_cart_disabled'] ) ) {
				$tab_cart_disabled = $wc4bp_options['tab_cart_disabled'];
			}
			
			if ( isset( $wc4bp_options['tab_checkout_disabled'] ) ) {
				$tab_checkout_disabled = $wc4bp_options['tab_checkout_disabled'];
			}
			
			if ( isset( $wc4bp_options['tab_history_disabled'] ) ) {
				$tab_history_disabled = $wc4bp_options['tab_history_disabled'];
			}
			
			if ( isset( $wc4bp_options['tab_track_disabled'] ) ) {
				$tab_track_disabled = $wc4bp_options['tab_track_disabled'];
			}
		}
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_shop_disable.php' );
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
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_profile_sync.php' );
		
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
		$custom_pages       = get_option( 'wc4bp_pages_options' );
		$wc4bp_pages_options = array();
        $tab_activity_disabled = 0;
		if ( ! empty( $custom_pages ) && is_string( $custom_pages ) ) {
			$custom_pages_temp = json_decode( $custom_pages, true );
            if ( isset( $custom_pages_temp['selected_pages'] ) && is_array( $custom_pages_temp['selected_pages'] ) ) {

                foreach ( $custom_pages_temp['selected_pages'] as $key => $attached_page ) {
                    $wc4bp_pages_options["selected_pages"][$attached_page['tab_slug'] ] = array(
                        'tab_name' => $attached_page['tab_name']
                    );

                }
            }
		}
		
		if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$starter_plan_id ) ) {
			$woo_my_account = WC4BP_MyAccount::get_active_endpoints__premium_only();
			if ( ! empty( $woo_my_account ) ) {
				foreach ( $woo_my_account as $active_page_key => $active_page_name ) {
					$wc4bp_pages_options["selected_pages"][ wc4bp_Manager::get_prefix() . $active_page_key ] = array(
						'tab_name' => $active_page_name
					);
				}
			}
			// Add the shop tab to the array
            if (empty($wc4bp_options['tab_cart_disabled'])) {
                $wc4bp_pages_options["selected_pages"][ "cart"] = array(
                    'tab_name' => "Cart"
                );
            }
            if (empty($wc4bp_options['tab_checkout_disabled'])) {
                $wc4bp_pages_options["selected_pages"][ "checkout"] = array(
                    'tab_name' => "Checkout"
                );
            }
            if (empty($wc4bp_options['tab_history_disabled'])) {
                $wc4bp_pages_options["selected_pages"][ "history"] = array(
                    'tab_name' => "History"
                );
            }
            if (empty($wc4bp_options['tab_track_disabled'])) {
                $wc4bp_pages_options["selected_pages"][ "track"] = array(
                    'tab_name' => "Track my order"
                );
            }



		}
		
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_shop_home.php' );
		
		submit_button();
	}
	public function get_pages_option(){
        $wc4bp_options       = get_option( 'wc4bp_options' );
        $custom_pages       = get_option( 'wc4bp_pages_options' );
        $wc4bp_pages_options = array();
        $tab_activity_disabled = 0;
        if ( ! empty( $custom_pages ) && is_string( $custom_pages ) ) {
            $custom_pages_temp = json_decode( $custom_pages, true );
            if ( isset( $custom_pages_temp['selected_pages'] ) && is_array( $custom_pages_temp['selected_pages'] ) ) {

                foreach ( $custom_pages_temp['selected_pages'] as $key => $attached_page ) {
                    $wc4bp_pages_options["selected_pages"][$attached_page['tab_slug'] ] = array(
                        'tab_name' => $attached_page['tab_name']
                    );

                }
            }
        }

        if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$starter_plan_id ) ) {
            $woo_my_account = WC4BP_MyAccount::get_active_endpoints__premium_only();
            if ( ! empty( $woo_my_account ) ) {
                foreach ( $woo_my_account as $active_page_key => $active_page_name ) {
                    $wc4bp_pages_options["selected_pages"][ wc4bp_Manager::get_prefix() . $active_page_key ] = array(
                        'tab_name' => $active_page_name
                    );
                }
            }
            // Add the shop tab to the array
            if (empty($wc4bp_options['tab_cart_disabled'])) {
                $wc4bp_pages_options["selected_pages"][ "cart"] = array(
                    'tab_name' => "Cart"
                );
            }
            if (empty($wc4bp_options['tab_checkout_disabled'])) {
                $wc4bp_pages_options["selected_pages"][ "checkout"] = array(
                    'tab_name' => "Checkout"
                );
            }
            if (empty($wc4bp_options['tab_history_disabled'])) {
                $wc4bp_pages_options["selected_pages"][ "history"] = array(
                    'tab_name' => "History"
                );
            }
            if (empty($wc4bp_options['tab_track_disabled'])) {
                $wc4bp_pages_options["selected_pages"][ "track"] = array(
                    'tab_name' => "Track my order"
                );
            }


        }

        return $wc4bp_pages_options;
    }
}