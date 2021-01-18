<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, WooCommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

class wc4bp_admin_ajax extends wc4bp_base {

	public function __construct() {
		add_action( 'wp_ajax_wc4bp_edit_entry', array( $this, 'wc4bp_edit_entry' ) );
		add_action( 'wp_ajax_nopriv_wc4bp_edit_entry', array( $this, 'wc4bp_edit_entry' ) );

		add_action( 'wp_ajax_wc4bp_add_page', array( $this, 'wc4bp_add_page' ) );
		add_action( 'wp_ajax_nopriv_wc4bp_add_page', array( $this, 'wc4bp_add_page' ) );

		add_action( 'wp_ajax_wc4bp_delete_page', array( $this, 'wc4bp_delete_page' ) );
		add_action( 'wp_ajax_nopriv_wc4bp_delete_page', array( $this, 'wc4bp_delete_page' ) );

		add_action( 'wp_ajax_wc4bp_shop_profile_sync_ajax', array( $this, 'wc4bp_shop_profile_sync_ajax' ) );
	}


	public function wc4bp_edit_entry() {
		try {
			wc4bp_admin_pages::wc4bp_add_edit_entry_form_call( 'edit' );
			die();
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_shop_profile_sync_ajax() {
		try {
			$wc4bp_page  = Request_Helper::get_post_param( 'wc4bp_page' );
			$update_type = Request_Helper::get_post_param( 'update_type' );
			$number      = 20;
			$paged       = ! empty( $wc4bp_page ) ? intval( $wc4bp_page ) : 1;
			$offset      = ( $paged - 1 ) * $number;
			$query       = get_users( '&offset=' . $offset . '&number=' . $number );

			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'sync/html_admin_sync_shop_profile_sync_ajax.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_change_xprofile_visibility_by_user_ajax( $user_id ) {
		try {
			if ( bp_is_active( 'xprofile' ) ) {
				// get the corresponding  wc4bp fields
				$ids                    = wc4bp_Sync::wc4bp_get_xprofield_fields_ids();
				$shipping               = $ids['shipping'];
				$billing                = $ids['billing'];
				$visibility_level_param = Request_Helper::get_post_param( 'visibility_level' );
				if ( ! empty( $visibility_level_param ) ) {
					foreach ( $shipping as $key => $field_id ) {
						$visibility_level = apply_filters( 'wc4bp_xprofile_visibility', $visibility_level_param, $field_id );
						$visibility_level = apply_filters( 'wc4bp_xprofile_visibility_by_user', $visibility_level, $field_id, $user_id );
						xprofile_set_field_visibility_level( $field_id, $user_id, $visibility_level );
					}
					foreach ( $billing as $key => $field_id ) {
						$visibility_level = apply_filters( 'wc4bp_xprofile_visibility', $visibility_level_param, $field_id );
						$visibility_level = apply_filters( 'wc4bp_xprofile_visibility_by_user', $visibility_level, $field_id, $user_id );
						xprofile_set_field_visibility_level( $field_id, $user_id, $visibility_level );
					}
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_sync_from_admin( $user_id ) {
		try {
			if ( bp_is_active( 'xprofile' ) && class_exists( 'BP_XProfile_Group' ) ) {
				// get the profile fields
				$ids      = wc4bp_Sync::wc4bp_get_xprofield_fields_ids();
				$shipping = $ids['shipping'];
				$billing  = $ids['billing'];
				$groups   = BP_XProfile_Group::get( array(
					'fetch_fields' => true,
				) );
				if ( ! empty( $groups ) ) {
					foreach ( $groups as $group ) {
						if ( wc4bp_Sync::wc4bp_is_invalid_xprofile_group( $group ) ) {
							continue;
						}
						foreach ( $group->fields as $field ) {

							if ( isset( $billing['group_id'] ) ) {
								unset( $billing['group_id'] );
							}

							$billing_key  = array_search( $field->id, $billing, true );
							$shipping_key = array_search( $field->id, $shipping, true );
							if ( $shipping_key ) {
								$type       = 'shipping';
								$field_slug = $shipping_key;
							}
							if ( $billing_key ) {
								$type       = 'billing';
								$field_slug = $billing_key;
							}
							if ( isset( $field_slug ) ) {
								$user_meta = get_user_meta( $user_id, $type . '_' . $field_slug, true );
								xprofile_set_field_data( $field->id, $user_id, $user_meta );
							}
						}
					}
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_add_page( $wc4bp_page_id ) {
		try {
			$position    = Request_Helper::get_post_param( 'wc4bp_position' );
			$children    = Request_Helper::get_post_param( 'wc4bp_children' );
			$page_id     = Request_Helper::get_post_param( 'wc4bp_page_id' );
			$old_page_id = Request_Helper::get_post_param( 'wc4bp_old_page_id' );

			if ( empty( $page_id ) ) {
				return;
			}

			$tab_name = Request_Helper::get_post_param( 'wc4bp_tab_name' );
			if ( empty( $tab_name ) ) {
				$tab_name = get_the_title( $page_id );
			}

			$tab_slug = Request_Helper::get_post_param( 'wc4bp_tab_slug' );
			if ( empty( $tab_slug ) && ! empty( $tab_name ) ) {
				$post     = get_post( $page_id );
				$tab_slug = $post->post_name;
			}

			$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );

			if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
				$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
			}

			if ( ! empty( $old_page_id ) && ! empty( $wc4bp_pages_options['selected_pages'][ $old_page_id ] ) ) {
				unset( $wc4bp_pages_options['selected_pages'][ $old_page_id ] );
			}

			$wc4bp_pages_options['selected_pages'][ $page_id ]['tab_name'] = $tab_name;
			$wc4bp_pages_options['selected_pages'][ $page_id ]['tab_slug'] = $tab_slug;
			$wc4bp_pages_options['selected_pages'][ $page_id ]['position'] = $position;
			$wc4bp_pages_options['selected_pages'][ $page_id ]['children'] = $children;
			$wc4bp_pages_options['selected_pages'][ $page_id ]['page_id']  = $page_id;

			update_option( 'wc4bp_pages_options', wp_json_encode( $wc4bp_pages_options ) );
			header( 'Content-Type: application/json' );
			echo json_encode( $wc4bp_pages_options );
			die();
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	/**
	 * Ajax call back function to delete a form element
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
	public function wc4bp_delete_page() {
		try {
			$page_id = Request_Helper::get_post_param( 'wc4bp_tab_id' );

			if ( empty( $page_id ) ) {
				return;
			}

			$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
			if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
				$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
			}
			unset( $wc4bp_pages_options['selected_pages'][ $page_id ] );

			$wc4bp_options = get_option( 'wc4bp_options' );
			if ( ! empty( $wc4bp_options ) ) {
				if ( ! empty( $wc4bp_options ) && is_string( $wc4bp_options ) ) {
					$wc4bp_options = json_decode( $wc4bp_options, true );
				}
				//Check if the page to delete is the one defined as thank you page
				if ( isset( $wc4bp_options['thank_you_page'] ) && $wc4bp_options['thank_you_page'] == $page_id ) {
					//Then set the Woocommerce page as Thank you page.
					$wc4bp_options['thank_you_page'] = 'default';
				}
				//Check if the tab_shop_default is equal to the page to delete
				$page_name = get_post( $page_id );
				if ( $page_name && isset( $wc4bp_options['tab_shop_default'] ) ) {
					if ( $page_name->post_name == $wc4bp_options['tab_shop_default'] ) {
						//If the page to delete is set as default tab, then do this.
						$wc4bp_options['tab_shop_default'] = 'default';

					}
				}
			}
			update_option( 'wc4bp_options', wp_json_encode( $wc4bp_options ) );
			update_option( 'wc4bp_pages_options', wp_json_encode( $wc4bp_pages_options ) );
			header( 'Content-Type: application/json' );
			echo json_encode( $wc4bp_pages_options );
			die();
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

}
