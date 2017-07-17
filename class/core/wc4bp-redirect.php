<?php
/**
 * @package        WordPress
 * @subpackage    BuddyPress, Woocommerce
 * @author        Boris Glumpler
 * @copyright    2011, Themekraft
 * @link        https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the redirect link
 *
 * @since 1.0.6
 */
function wc4bp_get_redirect_link( $id = false ) {
	global $current_user, $bp, $wp;
	
	if ( ! $id ) {
		return false;
	}
	
	$wc4bp_options = get_option( 'wc4bp_options' );
	if ( ! empty( $wc4bp_options['tab_activity_disabled'] ) ) {
		return false;
	}
	
	if ( ( ( isset( $wp->query_vars['name'] ) && $wp->query_vars['name'] == 'order-received' ) || isset( $wp->query_vars['order-received'] ) ) ) {
		return false;
	}
	
	$action       = bp_current_action();
	$current_user = wp_get_current_user();
	$userdata     = get_userdata( $current_user->ID );
	
	$wc4bp_options       = get_option( 'wc4bp_options' );
	$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
	if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
		$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
	}
	
	$my_account_page_id = get_option( 'woocommerce_myaccount_page_id' );
	$cart_page_id       = wc_get_page_id( 'cart' );
	$checkout_page_id   = wc_get_page_id( 'checkout' );
	$account_page_id    = wc_get_page_id( 'myaccount' );
	
	$granted_wc_page_id = array( $account_page_id, $my_account_page_id );
	if ( ! isset( $wc4bp_options['tab_checkout_disabled'] ) ) {
		$granted_wc_page_id[] = $checkout_page_id;
	}
	if ( ! isset( $wc4bp_options['tab_cart_disabled'] ) ) {
		$granted_wc_page_id[] = $cart_page_id;
	}
	
	$link = false;
	if ( in_array( $id, $granted_wc_page_id ) ) {
		$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/';
		switch ( $id ) {
			case $cart_page_id:
				if ( ! isset( $wc4bp_options['tab_cart_disabled'] ) ) {
					$link .= 'cart/';
				}
				break;
			
			case $checkout_page_id:
				if ( ! isset( $wc4bp_options['tab_checkout_disabled'] ) && is_object( WC()->cart ) && ! WC()->cart->is_empty() ) {
					$link .= 'checkout/';
				} else if ( ! isset( $wc4bp_options['tab_checkout_disabled'] ) && ! is_object( WC()->cart ) ) {
					$link .= 'home/';
				}
				$checkout_page_id = wc_get_page_id( 'checkout' );
				$checkout_page    = get_post( $checkout_page_id );
				$url              = get_bloginfo( 'url' ) . '/' . $checkout_page->post_name . '/' ;
				$payment_created_account = isset($bp->unfiltered_uri[0]) ? $bp->unfiltered_uri[0] : '';

				$link = apply_filters( 'wc4bp_checkout_page_link', $link );
                if ($payment_created_account==$checkout_page->post_name){
                    $link=$url;
                }
				break;
			
			case $account_page_id:
				if ( ! empty( $action ) ) {
					$link .= $action . '/';
				}
				$link = apply_filters( 'wc4bp_account_page_link', $link );
				break;
		}
		
		if ( isset( $wc4bp_pages_options['selected_pages'] ) && is_array( $wc4bp_pages_options['selected_pages'] ) ) {
			foreach ( $wc4bp_pages_options['selected_pages'] as $key => $attached_page ) {
				if ( $attached_page['children'] > 0 ) {
					$the_page_id    = get_top_parent_page_id( $attached_page['page_id'] );
					$the_courent_id = get_top_parent_page_id( $id );
				} else {
					$the_page_id    = $attached_page['page_id'];
					$the_courent_id = $id;
				}
				if ( $the_page_id == $the_courent_id ) {
					$post_data  = get_post( $id );
					$final_slug = ( $attached_page['tab_slug'] != $post_data->post_name ) ? $attached_page['tab_slug'] . '/' . $post_data->post_name : $attached_page['tab_slug'];
					$link       .= $final_slug . '/';
				}
			}
		}
		
		if ( 'yes' == get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
			$link = str_replace( 'http:', 'https:', $link );
		}
	}
	
	return apply_filters( 'wc4bp_get_redirect_link', $link );
}

function get_top_parent_page_id( $post_id ) {
	$ancestors = get_post_ancestors( $post_id );
	// Check if page is a child page (any level)
	if ( $ancestors ) {
		// Grab the ID of top-level page from the tree
		return end( $ancestors );
	} else {
		// Page is the top level, so use  it's own id
		return $post_id;
	}
}

/**
 * Redirect the user to their respective profile page
 *
 * @since 1.0.6
 */
function wc4bp_redirect_to_profile() {
	global $post, $wp_query;
	
	if ( ! is_user_logged_in() ) {
		return false;
	}
	
	if ( empty( $post ) || ! is_object( $post ) ) {
		return false;
	}
	
	$link = wc4bp_get_redirect_link( $post->ID );
	
	if ( ! empty( $link ) ) :
		wp_safe_redirect( $link );
		exit;
	endif;
}

add_action( 'template_redirect', 'wc4bp_redirect_to_profile' );

/**
 * Link router function
 *
 * @since    1.0.6
 * @uses    bp_get_option()
 * @uses    is_page()
 */
function wc4bp_page_link_router( $link, $id ) {
	if ( ! is_user_logged_in() || is_admin() ) {
		return $link;
	}

	
	$new_link = wc4bp_get_redirect_link( $id );
	
	if ( ! empty( $new_link ) ) {
		$link = $new_link;
	}
	
	return apply_filters( 'wc4bp_router_link', $link );
}

add_filter( 'page_link', 'wc4bp_page_link_router', 10, 2 );
