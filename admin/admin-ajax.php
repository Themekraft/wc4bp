<?php
/**
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

class wc4bp_admin_ajax extends wc4bp_base {
	
	public function __construct() {
		add_action( 'wp_ajax_wc4bp_edit_entry', array( $this, 'wc4bp_edit_entry' ) );
		add_action( 'wp_ajax_nopriv_wc4bp_edit_entry', array( $this, 'wc4bp_edit_entry' ) );
		
		add_action( 'wp_ajax_wc4bp_add_page', array( $this, 'wc4bp_add_page' ) );
		add_action( 'wp_ajax_nopriv_wc4bp_add_page', array( $this, 'wc4bp_add_page' ) );
		
		add_action( 'wp_ajax_wc4bp_delete_page', array( $this, 'wc4bp_delete_page' ) );
		add_action( 'wp_ajax_nopriv_wc4bp_delete_page', array( $this, 'wc4bp_delete_page' ) );
		
		add_action( 'wp_ajax_wc4bp_shop_profile_sync_ajax', array( $this, 'wc4bp_shop_profile_sync_ajax' ) );
		add_action( 'wp_ajax_nopriv_wc4bp_shop_profile_sync_ajax', array( $this, 'wc4bp_shop_profile_sync_ajax' ) );

//		add_action( 'wp_ajax_wc4bp_thickbox_add_page', 'wc4bp_thickbox_add_page' );
//		add_action( 'wp_ajax_nopriv_wc4bp_thickbox_add_page', 'wc4bp_thickbox_add_page' );
	}
	
	
	/**
	 * Ajax call back function to add a page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
//	public function wc4bp_thickbox_add_page(){
//        wc4bp_admin_pages::wc4bp_add_edit_entry_form_call( 'edit' );
//        die();
//    }
	
	
	public function wc4bp_edit_entry() {
		wc4bp_admin_pages::wc4bp_add_edit_entry_form_call( 'edit' );
		die();
	}
	
	public function wc4bp_shop_profile_sync_ajax() {
		$update_type = sanitize_text_field( $_POST['update_type'] );
		
		$number = 20;
		$paged  = isset( $_POST['wc4bp_page'] ) ? intval( sanitize_text_field( $_POST['wc4bp_page'] ) ) : 1;
		$offset = ( $paged - 1 ) * $number;
		$query  = get_users( '&offset=' . $offset . '&number=' . $number );
		
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'sync/html_admin_sync_shop_profile_sync_ajax.php' );
	}
	
	public function wc4bp_change_xprofile_visibility_by_user_ajax( $user_id ) {
		// get the corresponding  wc4bp fields
		$shipping         = bp_get_option( 'wc4bp_shipping_address_ids' );
		$billing          = bp_get_option( 'wc4bp_billing_address_ids' );
		$visibility_level = sanitize_text_field( $_POST['visibility_level'] );
		
		foreach ( $shipping as $key => $field_id ) {
			xprofile_set_field_visibility_level( $field_id, $user_id, $visibility_level );
		}
		foreach ( $billing as $key => $field_id ) {
			xprofile_set_field_visibility_level( $field_id, $user_id, $visibility_level );
		}
	}
	
	public function wc4bp_sync_from_admin( $user_id ) {
		// get the profile fields
		$shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
		$billing  = bp_get_option( 'wc4bp_billing_address_ids' );
		
		$groups = BP_XProfile_Group::get( array(
			'fetch_fields' => true
		) );
		
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				if ( empty( $group->fields ) ) {
					continue;
				}
				
				foreach ( $group->fields as $field ) {
					
					$billing_key  = array_search( $field->id, $billing );
					$shipping_key = array_search( $field->id, $shipping );
					
					if ( $shipping_key ) {
						$type       = 'shipping';
						$field_slug = $shipping_key;
					}
					
					if ( $billing_key ) {
						$type       = 'billing';
						$field_slug = $billing_key;
					}
					
					if ( isset( $field_slug ) ) {
						xprofile_set_field_data( $field->id, $user_id, get_user_meta( $user_id, $type . '_' . $field_slug, true ) );
					}
				}
			}
		}
	}
	
	public function wc4bp_add_page( $wc4bp_page_id ) {
		$position = '';
		$children = '';
		if ( isset( $_POST['wc4bp_page_id'] ) ) {
			$page_id = sanitize_text_field( $_POST['wc4bp_page_id'] );
		}
		
		if ( empty( $page_id ) ) {
			return;
		}
		
		if ( ! empty( $_POST['wc4bp_tab_name'] ) ) {
			$tab_name = sanitize_text_field( $_POST['wc4bp_tab_name'] );
		} else {
			$tab_name = get_the_title( $page_id );
		}
		
		if ( isset( $_POST['wc4bp_position'] ) ) {
			$position = sanitize_text_field( $_POST['wc4bp_position'] );
		}
		
		if ( isset( $_POST['wc4bp_children'] ) ) {
			$children = sanitize_text_field( $_POST['wc4bp_children'] );
		}
		
		if ( isset( $_POST['wc4bp_tab_slug'] ) ) {
			$tab_slug = sanitize_text_field( $_POST['wc4bp_tab_slug'] );
		}
		
		if ( empty( $tab_slug ) && ! empty( $tab_name ) ) {
			$post     = get_post( $page_id );
			$tab_slug = $post->post_name;
		}
		
		$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
		
		if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
			$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
		}
		
		$wc4bp_pages_options['selected_pages'][ $page_id ]['tab_name'] = $tab_name;
		$wc4bp_pages_options['selected_pages'][ $page_id ]['tab_slug'] = $tab_slug;
		$wc4bp_pages_options['selected_pages'][ $page_id ]['position'] = $position;
		$wc4bp_pages_options['selected_pages'][ $page_id ]['children'] = $children;
		$wc4bp_pages_options['selected_pages'][ $page_id ]['page_id']  = $page_id;
		
		update_option( "wc4bp_pages_options", wp_json_encode( $wc4bp_pages_options ) );
		
		die();
	}
	
	/**
	 * Ajax call back function to delete a form element
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
	public function wc4bp_delete_page() {
		if ( isset( $_POST['wc4bp_tab_id'] ) ) {
			$page_id = sanitize_text_field( $_POST['wc4bp_tab_id'] );
		}
		
		if ( empty( $page_id ) ) {
			return;
		}
		
		$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
		if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
			$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
		}
		unset( $wc4bp_pages_options['selected_pages'][ $page_id ] );
		
		update_option( "wc4bp_pages_options", wp_json_encode( $wc4bp_pages_options ) );
		die();
	}
	
}
