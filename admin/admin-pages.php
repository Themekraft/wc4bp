<?php
/**
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

class wc4bp_admin_pages extends wc4bp_base {

	/**
	 * The Admin Page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
	public function wc4bp_screen_pages( $active_tab ) {
		try {
			$this->wc4bp_register_admin_pages_settings();
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_pages_screen_pages.php' );
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
	public function wc4bp_register_admin_pages_settings() {
		add_settings_section( 'section_general1', '', '', 'wc4bp_options_pages' );
		// Settings fields and sections
		add_settings_section( 'section_general', '', array( $this, 'wc4bp_shop_pages_add' ), 'wc4bp_options_pages' );
	}

	public function wc4bp_shop_pages_add() {
		try {
			$this->wc4bp_get_forms_table();
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_shop_pages_rename() {
		try {
			$options = get_option( 'wc4bp_options' );

			$shop_main_nav = '';
			if ( isset( $options['shop_main_nav'] ) ) {
				$shop_main_nav = $options['shop_main_nav'];
			}

			$cart_sub_nav = '';
			if ( isset( $options['cart_sub_nav'] ) ) {
				$cart_sub_nav = $options['cart_sub_nav'];
			}

			$history_sub_nav = '';
			if ( isset( $options['history_sub_nav'] ) ) {
				$history_sub_nav = $options['history_sub_nav'];
			}

			$track_sub_nav = '';
			if ( isset( $options['track_sub_nav'] ) ) {
				$track_sub_nav = $options['track_sub_nav'];
			}

			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'pages/html_admin_pages_shop_pages_rename.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}


	public function wc4bp_get_forms_table() {
		try {
			//$wc4bp_options			= get_option( 'wc4bp_options' );
			$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
			if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
				$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
			}

			// echo '<pre>';
			// print_r($wc4bp_pages_options);
			// echo '</pre>';
			?>
            <style type="text/css">
                .wc4bp_editinline {
                    color: blue;
                    cursor: pointer;
                }

                .wc4bp_deleteinline {
                    color: red;
                    cursor: pointer;
                }

                table #the-list tr .wc4bp-row-actions {
                    opacity: 0
                }

                table #the-list tr:hover .wc4bp-row-actions {
                    opacity: 1
                }

                table.wp-list-table th.manage-column {
                    width: auto;
                    padding: 20px 0px 20px 10px;
                }

            </style>
			<?php
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'pages/html_admin_pages_forms_table.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_thickbox_page_form() {
		try {
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'pages/html_admin_pages_thickbox.php' );
			//$options = get_option( 'wc4bp_options' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public static function wc4bp_add_edit_entry_form_call( $edit = '' ) {
		try {
			$wc4bp_page_id = '';
			$tab_name      = '';
			$position      = '';
			$main_nav      = '';

			$wc4bp_page_id = Request_Helper::get_post_param( 'wc4bp_page_id' );

			$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
			if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
				$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
			}

			$children = 0;
			$page_id  = '';

			if ( ! empty( $wc4bp_page_id ) ) {
				if ( isset( $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['tab_name'] ) ) {
					$tab_name = $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['tab_name'];
				}

				if ( isset( $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['children'] ) ) {
					$children = $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['children'];
				}

				if ( isset( $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['position'] ) ) {
					$position = $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['position'];
				}

				if ( isset( $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['page_id'] ) ) {
					$page_id = $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['page_id'];
				}

				if ( isset( $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['tab_slug'] ) ) {
					$tab_slug = $wc4bp_pages_options['selected_pages'][ $wc4bp_page_id ]['tab_slug'];
				}
			}

			$exclude              = array();
			$shop_page_id         = get_option( 'woocommerce_shop_page_id' );
			$cart_page_id         = get_option( 'woocommerce_cart_page_id' );
			$myaccount_page_id    = get_option( 'woocommerce_myaccount_page_id' );
			$checkout_page_id     = get_option( 'woocommerce_checkout_page_id' );
			$budypress_page_array = get_option( 'bp-pages' );
			if ( is_array( $budypress_page_array ) && count( $budypress_page_array ) ) {
				$exclude = array_merge( $budypress_page_array, $exclude );
			}
			if ( $shop_page_id !== false ) {
				$exclude[] = $shop_page_id;
			}
			if ( $cart_page_id !== false ) {
				$exclude[] = $cart_page_id;
			}
			if ( $myaccount_page_id !== false ) {
				$exclude[] = $myaccount_page_id;
			}
			if ( $checkout_page_id !== false ) {
				$exclude[] = $checkout_page_id;
			}
			$args = array(
				'echo'             => true,
				'sort_column'      => 'post_title',
				'show_option_none' => __( 'none', 'wc4bp' ),
				'name'             => 'wc4bp_page_id',
				'class'            => 'postform',
				'selected'         => $page_id,
				'exclude'          => join( ', ', $exclude )
			);
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'pages/html_admin_pages_edit_entry.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_add_edit_entry_form( $edit = '' ) {
		try {
			self::wc4bp_add_edit_entry_form_call( $edit );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
}