<?php
/**
 * @package            WordPress
 * @subpackage         BuddyPress, Woocommerce
 * @author             Boris Glumpler
 * @copyright          2011, Themekraft
 * @link               https://github.com/Themekraft/BP-Shop-Integration
 * @license            http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC4BP_Component extends BP_Component {
	/**
	 * Holds the ID of the component
	 *
	 * @var        string
	 * @since   1.0
	 */
	public $id;
	public $template_directory;
	private $wc4bp_pages_options;
	private $wc4bp_options;
	
	/**
	 * Start the shop component creation process
	 *
	 * @since     1.0
	 */
	function __construct() {
		$this->id                  = wc4bp_Manager::get_shop_slug();
		$this->wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
		$this->wc4bp_options       = get_option( 'wc4bp_options' );
		if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
			/**
			 * Get the label for the BuddyPress Core
			 *
			 * @param String $var The current label.
			 */
			$title = apply_filters( 'wc4bp_shop_component_label', __( 'Shop', 'wc4bp' ) );
		} else {
			$title = __( 'Shop', 'wc4bp' );
		}
		parent::start( $this->id, $title, WC4BP_ABSPATH );
		$this->includes();
		add_action( 'bp_register_activity_actions', array( $this, 'register_activity_actions' ) );
		add_filter( 'bp_located_template', array( $this, 'wc4bp_members_load_template_filter' ), 10, 2 );
	}
	
	/**
	 * Include files
	 *
	 * @since     1.0
	 *
	 * @param array $includes
	 *
	 */
	function includes( $includes = array() ) {
		try {
			$includes = array(
				'wc4bp-helpers',
				'wc4bp-conditionals',
				'wc4bp-screen',
				'wc4bp-redirect',
				'wc4bp-deprecated',
				'wc4bp-sync',
			);
			foreach ( $includes as $file ) {
				require( WC4BP_ABSPATH . 'class/core/' . $file . '.php' );
			}
			if ( ! class_exists( 'BP_Theme_Compat' ) ) {
				require( WC4BP_ABSPATH . 'class/core/wc4bp-template-compatibility.php' );
			}
			new wc4bp_Sync();
			new wc4bp_redirect();
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	/**
	 * Register acctivity actions
	 *
	 * @since     1.0.4
	 */
	function register_activity_actions() {
		try {
			if ( ! bp_is_active( 'activity' ) ) {
				return;
			}
			bp_activity_set_action( $this->id, 'new_shop_review', __( 'New review created', 'wc4bp' ) );
			bp_activity_set_action( $this->id, 'new_shop_purchase', __( 'New purchase made', 'wc4bp' ) );
			do_action( 'wc4bp_register_activity_actions' );
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	/**
	 * Setup globals
	 *
	 * @since     1.0
	 *
	 * @param array      $globals
	 *
	 * @global    object $bp
	 */
	function setup_globals( $globals = array() ) {
		global $bp;
		try {
			$globals = array(
				'path'          => WC4BP_ABSPATH . 'core',
				'slug'          => wc4bp_Manager::get_shop_slug(),
				'has_directory' => false,
			);
			parent::setup_globals( $globals );
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	public function get_nav_item( $shop_link, $slug, $title, $screen_function = '' ) {
		$id              = str_replace( '-', '_', $slug );
		$screen_function = empty( $screen_function ) ? 'wc4bp_screen_' . $id : $screen_function;
		
		return array(
			'name'            => $title,
			'slug'            => $slug,
			'parent_url'      => $shop_link,
			'parent_slug'     => $this->slug,
			'screen_function' => apply_filters( 'wc4bp_screen_function', $screen_function, $id ),
			'position'        => 10,
			'item_css_id'     => 'shop-' . $id,
			'user_has_access' => bp_is_my_profile(),
		);
	}
	
	/**
	 * Setup BuddyBar navigation
	 *
	 * @since    1.0
	 *
	 * @param array     $main_nav
	 * @param array     $sub_nav
	 *
	 * @global   object $bp
	 */
	function setup_nav( $main_nav = array(), $sub_nav = array() ) {
		try {
			if ( ! function_exists( 'bp_get_settings_slug' ) ) {
				return;
			}
			if ( ! empty( $this->wc4bp_options['tab_activity_disabled'] ) ) {
				return;
			}
			$wc4bp_pages_options = array();
			if ( ! empty( $this->wc4bp_pages_options ) && is_string( $this->wc4bp_pages_options ) ) {
				$wc4bp_pages_options = json_decode( $this->wc4bp_pages_options, true );
			}
			// Add 'Shop' to the main navigation
			if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
				/**
				 * Get the label for the BuddyBar Navigation
				 *
				 * @param String $var The current label.
				 */
				$name = apply_filters( 'bp_shop_link_label', __( 'Shop', 'wc4bp' ) );
			} else {
				$name = __( 'Shop', 'wc4bp' );
			}
			$main_nav  = array(
				'name'                    => $name,
				'slug'                    => $this->slug,
				'position'                => 70,
				'screen_function'         => 'wc4bp_screen_plugins',
				'default_subnav_slug'     => 'home',
				'item_css_id'             => $this->id,
				'show_for_displayed_user' => false,
			);
			$shop_link = trailingslashit( bp_loggedin_user_domain() . $this->slug );
			
			$sub_nav = $this->get_endpoints( $sub_nav, $shop_link );
			
			// Add shop settings sub page
			if ( ! isset( $this->wc4bp_options['disable_shop_settings_tab'] ) ) {
				if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
					/**
					 * Get the label for the BuddyPress Navigation inside the settings
					 *
					 * @param String $var The current label.
					 */
					$name = apply_filters( 'bp_shop_settings_link_label', __( 'Shop', 'wc4bp' ) );
				} else {
					$name = __( 'Shop', 'wc4bp' );
				}
				$sub_nav[] = array(
					'name'            => $name,
					'slug'            => wc4bp_Manager::get_shop_slug(),
					'parent_url'      => trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() ),
					'parent_slug'     => bp_get_settings_slug(),
					'screen_function' => 'wc4bp_screen_settings',
					'position'        => 30,
					'item_css_id'     => 'shop-settings',
					'user_has_access' => bp_is_my_profile(),
				);
			}
			$position = 40;
			if ( isset( $wc4bp_pages_options['selected_pages'] ) && is_array( $wc4bp_pages_options['selected_pages'] ) ) {
				foreach ( $wc4bp_pages_options['selected_pages'] as $key => $attached_page ) {
					$position ++;
					$post      = get_post( $attached_page['page_id'] );
					$sub_nav[] = $this->get_nav_item( $shop_link, esc_html( $post->post_name ), $attached_page['tab_name'], 'wc4bp_screen_plugins' );
				}
			}
			$sub_nav = apply_filters( 'bp_shop_sub_nav', $sub_nav, $shop_link, $this->slug );
			do_action( 'bp_shop_setup_nav' );
			parent::setup_nav( $main_nav, $sub_nav );
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	public function get_admin_bar_item( $parent, $slug, $title ) {
		$id     = str_replace( '-', '_', $slug );
		$result = array(
			'parent' => 'my-account-' . $this->id,
			'id'     => 'my-account-' . $this->id . '-' . $id,
			'title'  => $title,
			'href'   => trailingslashit( $parent . $slug ),
		);
		
		return $result;
	}
	
	/**
	 * Set up the Toolbar
	 *
	 * @param array       $wp_admin_nav
	 *
	 * @return bool|void
	 * @global BuddyPress $bp The one true BuddyPress instance
	 */
	function setup_admin_bar( $wp_admin_nav = array() ) {
		try {
			global $bp;
			if ( ! empty( $this->wc4bp_options['tab_activity_disabled'] ) ) {
				return;
			}
			$wc4bp_pages_options = array();
			if ( ! empty( $this->wc4bp_pages_options ) && is_string( $this->wc4bp_pages_options ) ) {
				$wc4bp_pages_options = json_decode( $this->wc4bp_pages_options, true );
			}
			$wp_admin_nav = array();
			if ( is_user_logged_in() ) {
				$user_domain   = bp_loggedin_user_domain();
				$settings_link = trailingslashit( $user_domain . BP_SETTINGS_SLUG );
				if ( ! isset( $this->wc4bp_options['disable_shop_settings_tab'] ) ) {
					if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
						/**
						 * Get the label for the Setting inside BP
						 *
						 * @param String $var The current label.
						 */
						$title = apply_filters( 'bp_shop_settings_nav_link_label', __( 'Shop', 'wc4bp' ) );
					} else {
						$title = __( 'Shop', 'wc4bp' );
					}
					// Shop settings menu
					$wp_admin_nav[] = array(
						'parent' => 'my-account-settings',
						'id'     => 'my-account-settings-shop',
						'title'  => $title,
						'href'   => trailingslashit( $settings_link . wc4bp_Manager::get_shop_slug() ),
					);
				}
				$shop_link = trailingslashit( $user_domain . $this->id );
				if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
					/**
					 * Get the label for the admin bar
					 *
					 * @param String $var The current label.
					 */
					$title = apply_filters( 'bp_shop_nav_link_label', __( 'Shop', 'wc4bp' ) );
				} else {
					$title = __( 'Shop', 'wc4bp' );
				}
				// Shop menu items
				$wp_admin_nav[] = array(
					'parent' => $bp->my_account_menu_id,
					'id'     => 'my-account-' . $this->id,
					'title'  => $title,
					'href'   => trailingslashit( $shop_link ),
					'meta'   => array(
						'class' => 'menupop',
					),
				);
				$wp_admin_nav   = $this->get_endpoints( $wp_admin_nav, $shop_link, false );
				if ( isset( $wc4bp_pages_options['selected_pages'] ) && is_array( $wc4bp_pages_options['selected_pages'] ) ) {
					foreach ( $wc4bp_pages_options['selected_pages'] as $key => $attached_page ) {
						$wp_admin_nav[] = array(
							'parent' => 'my-account-' . $this->id,
							'id'     => 'my-account-' . $this->id . '-' . $attached_page['tab_slug'],
							'title'  => $attached_page['tab_name'],
							'href'   => trailingslashit( $shop_link . $attached_page['tab_slug'] ),
						);
					}
				}
				parent::setup_admin_bar( $wp_admin_nav );
			}
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	/**
	 * WC4BP template loader.
	 * @since 1.0
	 *
	 * @param $found_template
	 * @param $templates
	 *
	 * @return mixed
	 */
	function wc4bp_members_load_template_filter( $found_template, $templates ) {
		try {
			global $bp;
			if ( ! bp_is_current_component( wc4bp_Manager::get_shop_slug() ) ) {
				return $found_template;
			}
			$path                     = 'shop/member/plugin';
			$this->template_directory = apply_filters( 'wc4bp_members_get_template_directory', constant( 'WC4BP_ABSPATH_TEMPLATE_PATH' ) );
			bp_register_template_stack( array( $this, 'wc4bp_members_get_template_directory' ), 14 );
			if ( in_array( $bp->current_action, array_keys( wc4bp_Manager::available_endpoint() ), true ) ) {
				$found_template = locate_template( 'members/single/plugins.php', false, false );
				$cart_page_id   = wc_get_page_id( 'cart' );
				$cart_page      = get_post( $cart_page_id, ARRAY_A );
				$cart_slug      = $cart_page['post_name'];
				if ( 'home' === $bp->current_action ) {
					if ( isset( $this->wc4bp_options[ 'wc4bp_endpoint_' . $this->wc4bp_options['tab_shop_default'] ] ) || 'default' === $this->wc4bp_options['tab_shop_default'] ) {
						//Determine what is default
						if ( WC4BP_Loader::getFreemius()->is_plan_or_trial__premium_only( wc4bp_base::$professional_plan_id ) ) {
							$wc4bp_pages_options = array();
							$endpoints           = wc4bp_Manager::get_shop_endpoints( false );
							if ( isset( $endpoints['checkout'] ) ) {
								unset( $endpoints['checkout'] );
							}
							foreach ( $endpoints as $active_page_key => $active_page_name ) {
								if ( ! isset( $this->wc4bp_options[ 'tab_' . $active_page_key . '_disabled' ] ) ) {
									$wc4bp_pages_options[] = $active_page_key;
								}
							}
							$woo_endpoints = WC4BP_MyAccount::get_available_endpoints();
							foreach ( $woo_endpoints as $active_page_key => $active_page_name ) {
								if ( ! isset( $this->wc4bp_options[ 'wc4bp_endpoint_' . $active_page_key ] ) ) {
									$wc4bp_pages_options[] = $active_page_key;
								}
							}
							$custom_pages = get_option( 'wc4bp_pages_options' );
							if ( ! empty( $custom_pages ) && is_string( $custom_pages ) ) {
								$custom_pages_temp = json_decode( $custom_pages, true );
								if ( isset( $custom_pages_temp['selected_pages'] ) && is_array( $custom_pages_temp['selected_pages'] ) ) {
									foreach ( $custom_pages_temp['selected_pages'] as $key => $attached_page ) {
										$wc4bp_pages_options[] = $attached_page['tab_slug'];
									}
								}
							}
							if ( ! empty( $wc4bp_pages_options ) ) {
								$bp->current_action = $wc4bp_pages_options[0];
							} else {
								$bp->current_action = '';
							}
						} else {
							$bp->current_action = 'cart';
						}
					} else {
						$bp->current_action = $this->wc4bp_options['tab_shop_default'];
					}
				}
				$path = $this->get_endpoint_path( $bp->current_action );
			}
			add_action( 'bp_template_content',
				create_function( '', "bp_get_template_part( '" . $path . "' );" )
			);
			
			return apply_filters( 'wc4bp_members_load_template_filter_founded', $found_template );
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	public function get_endpoint_path( $endpoint ) {
		global $bp;
		switch ( $endpoint ) {
			case 'cart':
				$path = 'shop/member/cart';
				break;
			case 'checkout':
				$path = 'shop/member/checkout';
				break;
			case 'track':
				$path = 'shop/member/track';
				break;
			case 'orders':
				$page                = 'orders';
				$bp_action_variables = $bp->action_variables;
				if ( ! empty( $bp_action_variables ) ) {
					foreach ( $bp_action_variables as $var ) {
						if ( 'view-order' === $var ) {
							$page = 'view-order';
							break;
						}
					}
				}
				$path = 'shop/member/' . $page;
				break;
			case 'downloads':
				$path = 'shop/member/downloads';
				break;
			case 'edit-account':
				$path = 'shop/member/edit-account';
				break;
			case 'edit-address':
				$path = 'shop/member/edit-address';
				break;
			case 'payment-methods':
				$path = 'shop/member/payment-methods';
				break;
			default:
				$path = 'shop/member/plugin';
				break;
			
		}
		
		return apply_filters( 'wc4bp_load_template_path', $path, $this->template_directory );
	}
	
	/**
	 * Get the WC4BP template directory
	 *
	 * @since   0.1 beta
	 *
	 * @return string
	 */
	public function wc4bp_members_get_template_directory() {
		return $this->template_directory;
	}
	
	/**
	 * @param      $sub_nav
	 * @param      $parent
	 * @param bool $is_tabs
	 *
	 * @return array
	 */
	public function get_endpoints( $sub_nav, $parent, $is_tabs = true ) {
		$endpoints       = wc4bp_Manager::available_endpoint();
		$item_function   = ( $is_tabs ) ? 'get_nav_item' : 'get_admin_bar_item';
		$shop_endpoints  = wc4bp_Manager::get_shop_endpoints( false );
		$my_account_tabs = WC4BP_MyAccount::get_available_endpoints();
		foreach ( $endpoints as $key => $title ) {
			if ( array_key_exists( $key, $shop_endpoints ) ) {
				if ( ! isset( $this->wc4bp_options[ 'tab_' . $key . '_disabled' ] ) ) {
					switch ( $key ) {
						case 'checkout':
							global $woocommerce;
							if ( isset( $_GET['change_payment_method'] ) ) {
								$sub_nav[] = $this->$item_function( $parent, $key, $title );
							}
							// Add the checkout nav item, if cart empty do not add.
							/** @var WC_Session_Handler $wc_session_data */
							$wc_session_data = $woocommerce->session;
							if ( ! empty( $wc_session_data ) ) {
								$session_cart = $wc_session_data->get( 'cart' );
								if ( ! is_admin() && ! empty( $session_cart ) ) {
									$sub_nav[] = $this->$item_function( $parent, $key, $title );
								}
							}
							break;
						case 'cart':
						case 'history':
						case 'track':
							$sub_nav[] = $this->$item_function( $parent, $key, $title );
							break;
					}
				}
			} elseif ( array_key_exists( $key, $my_account_tabs ) ) {
				if ( empty( $this->wc4bp_options[ 'wc4bp_endpoint_' . $key ] ) ) {
					$sub_nav[] = $this->$item_function( $parent, $key, $title );
				}
			}
		}
		
		return $sub_nav;
	}
}
