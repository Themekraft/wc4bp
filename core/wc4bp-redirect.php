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
	global $current_user, $bp;

	if ( ! $id ) {
		return false;
	}

	$current_user = wp_get_current_user();
	$userdata     = get_userdata( $current_user->ID );

	$wc4bp_options       = get_option( 'wc4bp_options' );
	$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );

	$cart_page_id     = wc_get_page_id( 'cart' );
	$checkout_page_id = wc_get_page_id( 'checkout' );
	$account_page_id  = wc_get_page_id( 'myaccount' );

	$link = '';

	switch ( $id ) {
		case $cart_page_id:
			if ( ! isset( $wc4bp_options['tab_cart_disabled'] ) && $wc4bp_options['tab_shop_default'] == 'default' ) {

				$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/home/';

				if ( 'yes' == get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
					$link = str_replace( 'http:', 'https:', $link );
				}

			}

			break;

		case $checkout_page_id:
			if ( ! isset( $wc4bp_options['tab_checkout_disabled'] ) && is_object( WC()->cart ) && ! WC()->cart->is_empty() ) {
				$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/checkout/';
				if ( 'yes' == get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
					$link = str_replace( 'http:', 'https:', $link );
				}
			} elseif ( ! isset( $wc4bp_options['tab_checkout_disabled'] ) && ! is_object( WC()->cart ) ) {
				$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/home/';
				if ( 'yes' == get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
					$link = str_replace( 'http:', 'https:', $link );
				}
			}
			$link = apply_filters( 'wc4bp_checkout_page_link', $link );
			break;

		case $account_page_id:
			if ( ! isset( $wc4bp_options['tab_history_disabled'] ) ) {

				$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/history/';

				if ( 'yes' == get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
					$link = str_replace( 'http:', 'https:', $link );
				}

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
				$post_data  = get_post( $id, ARRAY_A );
				$slug       = $post_data['post_name'];
				$final_slug = ( $attached_page['tab_slug'] != $slug ) ? $attached_page['tab_slug'] . '/' . $slug : $attached_page['tab_slug'];
				$link       = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/' . $final_slug . '/';
				
//				$post_data = get_post( $id, ARRAY_A );
//				$slug      = $post_data['post_name'];
//				$link      = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/' . $attached_page['tab_slug'] . '/' . $slug . '/';
				
				if ( 'yes' == get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
					$link = str_replace( 'http:', 'https:', $link );
				}
			}

		}
	}

	return apply_filters( 'wc4bp_get_redirect_link', $link );
}


/**
 * Get the BP member page id as woo my_account
 *
 * @param $page_id
 *
 * @return string
 */
function wc4bp_my_account_page_id( $page_id ) {
	global $bp;

	$page = get_page_by_path( $bp->pages->members->slug );

	return $page->ID;
}

//add_filter( 'woocommerce_get_myaccount_page_id', 'wc4bp_my_account_page_id', 10, 1 );

/**
 * Redirect WC my Account to BP member profile page
 *
 * @param $permalink
 *
 * @return string
 */
function wc4bp_my_account_page_permalink( $permalink ) {
	global $current_user, $bp;

	$current_user = wp_get_current_user();
	$userdata     = get_userdata( $current_user->ID );

	$account_page_id = wc_get_page_id( 'myaccount' );
	$woo_my_account  = get_post( $account_page_id );

	$permalink = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/' . $woo_my_account->post_name . '/';

	return $permalink;
}

//Redirect myaccount to BP memeber
//add_filter( 'woocommerce_get_myaccount_page_permalink', 'wc4bp_my_account_page_permalink', 10, 1 );


function wc4bp_get_endpoint_url( $url, $endpoint, $value, $permalink ) {
	global $current_user, $bp;

	$current_user = wp_get_current_user();
	$userdata     = get_userdata( $current_user->ID );

	$base_path = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/';

	switch ( $endpoint ) {
		case "payment-methods":
			$url = $base_path . 'payment';
			break;
		case "set-default-payment-method":
		case "delete-payment-method":
			$url = add_query_arg( $endpoint, $value, $base_path . 'payment' );
			break;
		case "add-payment-method":
			$url = add_query_arg( $endpoint, "w2ewe3423ert", $base_path . 'payment/add-payment' );
			break;
		case "view-subscription":
			$url = add_query_arg( $endpoint, $value, $permalink . $endpoint );
			break;
		case "view-order":
			$url = add_query_arg( "id", $value, $permalink . $endpoint );
			break;
		case 'edit-account':
			$url = add_query_arg( $endpoint, $value, $permalink . $endpoint );
			break;
	}

	return $url;
}

//add_filter( 'woocommerce_get_endpoint_url', 'wc4bp_get_endpoint_url', 1, 4 );

function get_top_parent_page_id( $post_id ) {

	$ancestors = get_post_ancestors( $post_id );

	// Check if page is a child page (any level)
	if ( $ancestors ) {

		//  Grab the ID of top-level page from the tree
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

/**
 * Generates a URL so that a customer can pay for their (unpaid - pending) order. Pass 'true' for the checkout version which doesn't offer gateway choices.
 *
 * @access public
 *
 * @param  boolean $on_checkout
 *
 * @return string
 */
function wc4bp_get_checkout_payment_url( $pay_url, $order ) {
	global $current_user, $bp;

	$current_user = wp_get_current_user();
	$userdata     = get_userdata( $current_user->ID );

	$wc4bp_options = get_option( 'wc4bp_options' );

	if ( isset( $wc4bp_options['tab_cart_disabled'] ) ) {
		return $pay_url;
	}

	$pay_url = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/home/checkout/';

	if ( 'yes' == get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
		$pay_url = str_replace( 'http:', 'https:', $pay_url );
	}

	$pay_url = wc_get_endpoint_url( 'order-pay', $order->id, $pay_url );
	$pay_url = add_query_arg( 'key', $order->order_key, $pay_url );

	return $pay_url;
}

//add_filter( 'woocommerce_get_checkout_payment_url', 'wc4bp_get_checkout_payment_url', 999, 2 );

/**
 * Generates a URL for the thanks page (order received)
 *
 * @access public
 * @return string
 */
function wc4bp_get_checkout_order_received_url( $order_received_url, $order ) {
	global $current_user, $bp;

	$current_user = wp_get_current_user();
	$userdata     = get_userdata( $current_user->ID );

	if ( ! is_user_logged_in() ) {
		return $order_received_url;
	}

	$wc4bp_options = get_option( 'wc4bp_options' );

	if ( isset( $wc4bp_options['tab_cart_disabled'] ) ) {
		return $order_received_url;
	}

	$order_received_url = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/home/checkout/';

	if ( 'yes' == get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
		$order_received_url = str_replace( 'http:', 'https:', $order_received_url );
	}

	$order_received_url = wc_get_endpoint_url( 'order-received', $order->id, $order_received_url );

	$order_received_url = add_query_arg( 'key', $order->order_key, $order_received_url );


	return $order_received_url;
}

//add_filter( 'woocommerce_get_checkout_order_received_url', 'wc4bp_get_checkout_order_received_url', 999, 2 );
