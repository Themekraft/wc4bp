<?php
/**
 * Main file to handle the admin pages
 *
 * @package        WordPress
 * @subpackage     BuddyPress, WooCommerce
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
	private $wc4bp_options;

	public function __construct() {
		try {
			parent::__construct();
			$this->wc4bp_options = get_option( 'wc4bp_options' );
			add_action( 'admin_menu', array( $this, 'wc4bp_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'wc4bp_register_admin_settings' ) );

			require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-pages.php' );
			require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-sync.php' );
			require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-delete.php' );
			require_once( WC4BP_ABSPATH_ADMIN_PATH . 'admin-ajax.php' );
			new wc4bp_admin_ajax();
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
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
		/**
		 * SubMenu Page added
		 */
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
		try {
			$active_tab = Request_Helper::simple_get( 'tab', 'sanitize_text_field', 'generic' );
			switch ( $active_tab ) {
				case 'generic':
					include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_screen.php' );
					break;
				case 'page-sync':
					$admin_sync = new wc4bp_admin_sync();
					$admin_sync->wc4bp_screen_sync( $active_tab, $this->wc4bp_options );
					break;
				case 'integrate-pages':
					$admin_pages = new wc4bp_admin_pages();
					$admin_pages->wc4bp_screen_pages( $active_tab );
					break;
				case 'delete':
					$admin_delete = new wc4bp_admin_delete();
					$admin_delete->wc4bp_screen_delete( $active_tab );
					break;
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
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
		try {
			register_setting( 'wc4bp_options_delete', 'wc4bp_options_delete' );
			register_setting( 'wc4bp_options_pages', 'wc4bp_options_pages' );
			register_setting( 'wc4bp_options', 'wc4bp_options' );
			register_setting( 'wc4bp_options_sync', 'wc4bp_options_sync' );

			add_settings_section( 'section_general', '', '', 'wc4bp_options' );
			add_settings_section( 'section_general2', '', '', 'wc4bp_options' );

			add_settings_field( 'tabs_shop', __( '<b>Shop Settings</b>', 'wc4bp' ), array( $this, 'wc4bp_shop_tabs' ), 'wc4bp_options', 'section_general' );
			add_settings_field( 'tabs_enable', __( '<b>Remove Shop Tabs</b>', 'wc4bp' ), array( $this, 'wc4bp_my_account_tabs_enable' ), 'wc4bp_options', 'section_general' );
			add_settings_field( 'profile sync', __( '<b>Turn off the Profile Sync</b>', 'wc4bp' ), array( $this, 'wc4bp_turn_off_profile_sync' ), 'wc4bp_options', 'section_general' );
            add_settings_field( 'thank_you_page', __( '<b>Default Thank You Page</b>', 'wc4bp' ), array( $this, 'wc4bp_overwrite_default_thank_you_page' ), 'wc4bp_options', 'section_general' );
			add_settings_field( 'overwrite', __( '<b>Default Shop Tab</b>', 'wc4bp' ), array( $this, 'wc4bp_overwrite_default_shop_home_tab' ), 'wc4bp_options', 'section_general' );


		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	/**
	 * Shop settings view
	 */
	public function wc4bp_shop_tabs() {
		try {
			$wc4bp_options                = $this->wc4bp_options;
			$tab_activity_disabled        = 0;
			$disable_shop_settings_tab    = 0;
			$tab_my_account_disabled      = 0;
			$disable_woo_profile_override = 0;
			$tab_my_account_shop_label    = wc4bp_Manager::$shop_label;
			$tab_my_account_shop_url      = wc4bp_Manager::$shop_slug;
			if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
				if ( isset( $wc4bp_options['tab_my_account_shop_url'] ) ) {
					$tab_my_account_shop_url                  = empty( $wc4bp_options['tab_my_account_shop_url'] ) ? wc4bp_Manager::$shop_slug : sanitize_title( $wc4bp_options['tab_my_account_shop_url'] );
					$wc4bp_options['tab_my_account_shop_url'] = $tab_my_account_shop_url;
					wc4bp_Manager::del_cached_option_or_default( 'tab_my_account_shop_url' );
					update_option( 'wc4bp_options', $wc4bp_options );
				}
				if ( isset( $wc4bp_options['tab_my_account_shop_label'] ) ) {
					$tab_my_account_shop_label                  = empty( $wc4bp_options['tab_my_account_shop_label'] ) ? wc4bp_Manager::$shop_label : sanitize_text_field( $wc4bp_options['tab_my_account_shop_label'] );
					$wc4bp_options['tab_my_account_shop_label'] = $tab_my_account_shop_label;
					wc4bp_Manager::del_cached_option_or_default( 'tab_my_account_shop_label' );
					update_option( 'wc4bp_options', $wc4bp_options );
				}
			}
			if ( isset( $wc4bp_options['tab_activity_disabled'] ) ) {
				$tab_activity_disabled = 1;
			}
			if ( isset( $wc4bp_options['disable_shop_settings_tab'] ) ) {
				$disable_shop_settings_tab = 1;
			}
			if ( isset( $wc4bp_options['tab_my_account_disabled'] ) ) {
				$tab_my_account_disabled = 1;
			}
			if ( isset( $wc4bp_options['disable_woo_profile_override'] ) ) {
				$disable_woo_profile_override = 1;
			}
			if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
				//Get all actives tabs and custom pages
				$wc4bp_pages_options = $this->get_pages_option();
				// If all the tabs are disabled and there is not custom pages, Turn off 'Shop'
				if ( is_array( $wc4bp_pages_options ) && count( $wc4bp_pages_options ) === 0 ) {
					$tab_activity_disabled                  = 1;
					$wc4bp_options['tab_activity_disabled'] = 1;
					update_option( 'wc4bp_options', $wc4bp_options );
				} else {
					if ( isset( $wc4bp_options['tab_activity_disabled'] ) ) {
						$tab_activity_disabled = $wc4bp_options['tab_activity_disabled'];
					}
					if ( isset( $wc4bp_options['disable_shop_settings_tab'] ) ) {
						$disable_shop_settings_tab = $wc4bp_options['disable_shop_settings_tab'];
					}
					if ( isset( $wc4bp_options['tab_my_account_disabled'] ) ) {
						$tab_my_account_disabled = $wc4bp_options['tab_my_account_disabled'];
					}
					if ( isset( $wc4bp_options['disable_woo_profile_override'] ) ) {
						$disable_woo_profile_override = $wc4bp_options['disable_woo_profile_override'];
					}
				}
			}
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_shop_tabs.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	/**
	 * Turn off woo my account tabs view
	 */
	public function wc4bp_my_account_tabs_enable() {
		try {
			echo '<div class="wc4bp-tabs-order">';
			$wc4bp_options = $this->wc4bp_options;
			$tabs_array    = array();

			$tabs_array['tab_cart_disabled']['label']         = __( 'Cart', 'wc4bp' );
			$tabs_array['tab_cart_disabled']['user_label']    = isset( $this->wc4bp_options['user_label']['tab_cart_disabled'] ) ? $this->wc4bp_options['user_label']['tab_cart_disabled'] : $tabs_array['tab_cart_disabled']['label'];
			$tabs_array['tab_cart_disabled']['enable']        = 0;
			$tabs_array['tab_cart_disabled']['name']          = 'wc4bp_options[tab_cart_disabled]';
			$tabs_array['tab_cart_disabled']['name_position'] = 'wc4bp_options[position][tab_cart_disabled]';
			$tabs_array['tab_cart_disabled']['name_label']    = 'wc4bp_options[user_label][tab_cart_disabled]';
			$tabs_array['tab_cart_disabled']['position']      = '0';

			$tabs_array['tab_checkout_disabled']['label']         = __( 'Checkout', 'wc4bp' );
			$tabs_array['tab_checkout_disabled']['user_label']    = isset( $this->wc4bp_options['user_label']['tab_checkout_disabled'] ) ? $this->wc4bp_options['user_label']['tab_checkout_disabled'] : $tabs_array['tab_checkout_disabled']['label'];
			$tabs_array['tab_checkout_disabled']['enable']        = 0;
			$tabs_array['tab_checkout_disabled']['name']          = 'wc4bp_options[tab_checkout_disabled]';
			$tabs_array['tab_checkout_disabled']['name_position'] = 'wc4bp_options[position][tab_checkout_disabled]';
			$tabs_array['tab_checkout_disabled']['name_label']    = 'wc4bp_options[user_label][tab_checkout_disabled]';
			$tabs_array['tab_checkout_disabled']['position']      = '1';

			$tabs_array['tab_track_disabled']['label']         = __( 'Track my order', 'wc4bp' );
			$tabs_array['tab_track_disabled']['user_label']    = isset( $this->wc4bp_options['user_label']['tab_track_disabled'] ) ? $this->wc4bp_options['user_label']['tab_track_disabled'] : $tabs_array['tab_track_disabled']['label'];
			$tabs_array['tab_track_disabled']['enable']        = 0;
			$tabs_array['tab_track_disabled']['name']          = 'wc4bp_options[tab_track_disabled]';
			$tabs_array['tab_track_disabled']['name_position'] = 'wc4bp_options[position][tab_track_disabled]';
			$tabs_array['tab_track_disabled']['name_label']    = 'wc4bp_options[user_label][tab_track_disabled]';
			$tabs_array['tab_track_disabled']['position']      = '2';

			$i = 3;
			foreach ( WC4BP_MyAccount::get_available_endpoints() as $end_point_key => $end_point_name ) {
				if ( WC4BP_Loader::getFreemius()->is__premium_only() || WC4BP_Loader::getFreemius()->is_trial() ) {
					$tabs_array[ $end_point_key ]['position']   = isset( $this->wc4bp_options['position'][ 'wc4bp_endpoint_' . $end_point_key ] ) ? $this->wc4bp_options['position'][ 'wc4bp_endpoint_' . $end_point_key ] : $i;
					$tabs_array[ $end_point_key ]['enable']     = isset( $this->wc4bp_options[ 'wc4bp_endpoint_' . $end_point_key ] );
					$tabs_array[ $end_point_key ]['user_label'] = isset( $this->wc4bp_options['user_label'][ 'wc4bp_endpoint_' . $end_point_key ] ) ? $this->wc4bp_options['user_label'][ 'wc4bp_endpoint_' . $end_point_key ] : $end_point_name;
				} else {
					$tabs_array[ $end_point_key ]['position']   = $i;
					$tabs_array[ $end_point_key ]['enable']     = 0;
					$tabs_array[ $end_point_key ]['user_label'] = $end_point_name;
				}
				$tabs_array[ $end_point_key ]['label']         = $end_point_name;
				$tabs_array[ $end_point_key ]['name']          = 'wc4bp_options[wc4bp_endpoint_' . $end_point_key . ']';
				$tabs_array[ $end_point_key ]['name_position'] = 'wc4bp_options[position][wc4bp_endpoint_' . $end_point_key . ']';
				$tabs_array[ $end_point_key ]['name_label']    = 'wc4bp_options[user_label][wc4bp_endpoint_' . $end_point_key . ']';
				$i ++;
			}

			if ( WC4BP_Loader::getFreemius()->is__premium_only() || WC4BP_Loader::getFreemius()->is_trial() ) {
				$tabs_array['tab_cart_disabled']['enable'] = isset( $wc4bp_options['tab_cart_disabled'] );
				if ( isset( $wc4bp_options['position']['tab_cart_disabled'] ) ) {
					$tabs_array['tab_cart_disabled']['position'] = $wc4bp_options['position']['tab_cart_disabled'];
				}
				$tabs_array['tab_checkout_disabled']['enable'] = isset( $wc4bp_options['tab_checkout_disabled'] );
				if ( isset( $wc4bp_options['position']['tab_checkout_disabled'] ) ) {
					$tabs_array['tab_checkout_disabled']['position'] = $wc4bp_options['position']['tab_checkout_disabled'];
				}
				$tabs_array['tab_track_disabled']['enable'] = isset( $wc4bp_options['tab_track_disabled'] );
				if ( isset( $wc4bp_options['position']['tab_track_disabled'] ) ) {
					$tabs_array['tab_track_disabled']['position'] = $wc4bp_options['position']['tab_track_disabled'];
				}
			}

			uasort( $tabs_array, array( $this, 'compare_tabs' ) );

			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_my_account_tabs.php' );
			echo '</div>';
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function compare_tabs( $a, $b ) {
		if ( $a['position'] == $b['position'] ) {
			return 0;
		}

		return ( $a['position'] < $b['position'] ) ? - 1 : 1;
	}

	/**
	 * Tun off profile tabs view
	 */
	public function wc4bp_turn_off_profile_sync() {
		try {
			$wc4bp_options = $this->wc4bp_options;

			$tab_sync_disabled = 0;
			if ( isset( $wc4bp_options['tab_sync_disabled'] ) ) {
				$tab_sync_disabled = $wc4bp_options['tab_sync_disabled'];
			}
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_profile_sync.php' );
			include_once( WC4BP_ABSPATH_CLASS_PATH . '/core/wc4bp-sync.php' );
			include_once( WC4BP_ABSPATH_ADMIN_PATH . 'wc4bp-activate.php' );
			if ( isset( $tab_sync_disabled ) && true == $tab_sync_disabled ) {
				wc4bp_cleanup();
			} else {
				wc4bp_activate();
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	/**
	 * View to select the tabs to use as home
	 */
	public function wc4bp_overwrite_default_shop_home_tab() {
		try {
			$wc4bp_options         = $this->wc4bp_options;
			$custom_pages          = get_option( 'wc4bp_pages_options' );
			$wc4bp_pages_options   = array();
			$tab_activity_disabled = 0;
			if ( ! empty( $custom_pages ) && is_string( $custom_pages ) ) {
				$custom_pages_temp = json_decode( $custom_pages, true );
				if ( isset( $custom_pages_temp['selected_pages'] ) && is_array( $custom_pages_temp['selected_pages'] ) ) {
					foreach ( $custom_pages_temp['selected_pages'] as $key => $attached_page ) {
						$wc4bp_pages_options['selected_pages'][ $attached_page['tab_slug'] ] = array(
							'tab_name' => $attached_page['tab_name'],
						);
					}
				}
			}

			if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
				$woo_my_account = WC4BP_MyAccount::get_active_endpoints();
				if ( ! empty( $woo_my_account ) ) {
					foreach ( $woo_my_account as $active_page_key => $active_page_name ) {
						$wc4bp_pages_options['selected_pages'][ $active_page_key ] = array(
							'tab_name' => $active_page_name,
						);
					}
				}
				// Add the shop tab to the array
				if ( empty( $wc4bp_options['tab_cart_disabled'] ) ) {
					$wc4bp_pages_options['selected_pages']['cart'] = array(
						'tab_name' => __( 'Cart', 'wc4bp' ),
					);
				}
				if ( empty( $wc4bp_options['tab_checkout_disabled'] ) ) {
					$wc4bp_pages_options['selected_pages']['checkout'] = array(
						'tab_name' => __( 'Checkout', 'wc4bp' ),
					);
				}
				if ( empty( $wc4bp_options['tab_track_disabled'] ) ) {
					$wc4bp_pages_options['selected_pages']['track'] = array(
						'tab_name' => __( 'Track my order', 'wc4bp' ),
					);
				}
				//If wc4bp['tab_shop_default'] is empty add a default value to avoid offset warning
				if ( ! isset( $wc4bp_options['tab_shop_default'] ) ) {
					$wc4bp_options['tab_shop_default'] = 'default';
				} else {
					if ( ! empty( $wc4bp_pages_options['selected_pages'] ) ) {
						if ( ! array_key_exists( $wc4bp_options['tab_shop_default'], $wc4bp_pages_options['selected_pages'] ) ) {
							$wc4bp_options['tab_shop_default'] = 'default';
						}
					} else {
						$wc4bp_options['tab_shop_default'] = 'default';
					}
				}
			} else {
				$wc4bp_options['tab_shop_default'] = 'default';
			}

			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_shop_home.php' );

			submit_button();
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

    public function wc4bp_overwrite_default_thank_you_page() {
        try {
            $wc4bp_options         = $this->wc4bp_options;
            $custom_pages          = get_option( 'wc4bp_pages_options' );
            $wc4bp_pages_options   = array();
            if ( ! empty( $custom_pages ) && is_string( $custom_pages ) ) {
                $custom_pages_temp = json_decode( $custom_pages, true );
                if ( isset( $custom_pages_temp['selected_pages'] ) && is_array( $custom_pages_temp['selected_pages'] ) ) {
                    $wc4bp_pages_options['selected_pages'][ 'default' ]= array( 'tab_name' => __( 'Default', 'wc4bp' )  );
                    foreach ( $custom_pages_temp['selected_pages'] as $key => $attached_page ) {
                            $wc4bp_pages_options['selected_pages'][ $attached_page['page_id'] ] = array(
                            'tab_name' => $attached_page['tab_name'],
                        );
                    }
                }
            }
            if ( ! isset( $wc4bp_options['thank_you_page'] ) ) {
                $wc4bp_options['thank_you_page'] = 'default';
            }

            include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_thank_you_page.php' );

        } catch ( Exception $exception ) {
            WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
        }
    }

	/**
	 * Return array with all the  actives tabs and custom pages.
	 * @return array
	 */
	public function get_pages_option() {
		try {
			$wc4bp_options         = $this->wc4bp_options;
			$custom_pages          = get_option( 'wc4bp_pages_options' );
			$wc4bp_pages_options   = array();
			$tab_activity_disabled = 0;

			//Add the customs page to the array
			if ( ! empty( $custom_pages ) && is_string( $custom_pages ) ) {
				$custom_pages_temp = json_decode( $custom_pages, true );
				if ( isset( $custom_pages_temp['selected_pages'] ) && is_array( $custom_pages_temp['selected_pages'] ) ) {

					foreach ( $custom_pages_temp['selected_pages'] as $key => $attached_page ) {
						$wc4bp_pages_options['selected_pages'][ $attached_page['tab_slug'] ] = array(
							'tab_name' => $attached_page['tab_name'],
						);

					}
				}
			}

			//Add the actives my account pages to the option array
			$woo_my_account = WC4BP_MyAccount::get_active_endpoints();
			if ( ! empty( $woo_my_account ) ) {
				foreach ( $woo_my_account as $active_page_key => $active_page_name ) {
					$wc4bp_pages_options['selected_pages'][ $active_page_key ] = array(
						'tab_name' => $active_page_name,
					);
				}
			}
			// Add the shop tabs to the array
			if ( empty( $wc4bp_options['tab_cart_disabled'] ) ) {
				$wc4bp_pages_options['selected_pages']['cart'] = array(
					'tab_name' => __( 'Cart', 'wc4bp' ),
				);
			}
			if ( empty( $wc4bp_options['tab_checkout_disabled'] ) ) {
				$wc4bp_pages_options['selected_pages']['checkout'] = array(
					'tab_name' => __( 'Checkout', 'wc4bp' ),
				);
			}
			if ( empty( $wc4bp_options['tab_track_disabled'] ) ) {
				$wc4bp_pages_options['selected_pages']['track'] = array(
					'tab_name' => __( 'Track my order', 'wc4bp' ),
				);
			}

			return $wc4bp_pages_options;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

			return array();
		}
	}
}
