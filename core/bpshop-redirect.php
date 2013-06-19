<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get the redirect link
 *
 * @since 1.0.6
 */
function bpshop_get_redirect_link( $id = false ) {
	global $bp;

	if( ! $id )
		return false;

	$cart_page_id 		= woocommerce_get_page_id( 'cart' 			 );
	$checkout_page_id 	= woocommerce_get_page_id( 'checkout' 		 );
	$view_page_id 		= woocommerce_get_page_id( 'view_order' 	 );
	$address_page_id 	= woocommerce_get_page_id( 'edit_address' 	 );
	$account_page_id 	= woocommerce_get_page_id( 'myaccount' 		 );
	$password_page_id 	= woocommerce_get_page_id( 'change_password' );
	$thanks_page_id 	= woocommerce_get_page_id( 'thanks' 		 );
	$pay_page_id 		= woocommerce_get_page_id( 'pay' 			 );
	$track_page_id 		= woocommerce_get_page_id( 'order_tracking'  );
	$link = '';
	
	switch( $id ) {
		case $cart_page_id:
			$link = bp_loggedin_user_domain() .'shop/cart/';
			break;

		case $checkout_page_id:
			$link = bp_loggedin_user_domain() .'shop/cart/checkout/';
			break;

		case $thanks_page_id:
			$link = bp_loggedin_user_domain() .'shop/cart/checkout/thanks/';
			break;

		case $pay_page_id:
			$link = bp_loggedin_user_domain() .'shop/cart/checkout/pay/';
			break;

		case $track_page_id:
			$link = bp_loggedin_user_domain() .'shop/track/';
			break;

		case $account_page_id:
			$link = bp_loggedin_user_domain() .'shop/history/';
			break;

		case $view_page_id:
			$link = bp_loggedin_user_domain() .'shop/history/view/';
			break;

		case $address_page_id:
			$type = ( isset( $_GET['address'] ) ) ? $_GET['address'] : 'billing';

			switch( $type )	{
				case 'shipping' :
					$ids = bp_get_option( 'bpshop_shipping_address_ids' );
					$url = bp_loggedin_user_domain(). $bp->profile->slug .'/edit/group/'. $ids['group_id'];
					break;

				case 'billing' :
					$ids = bp_get_option( 'bpshop_billing_address_ids' );
					$url = bp_loggedin_user_domain(). $bp->profile->slug .'/edit/group/'. $ids['group_id'];
					break;
			}
			break;

		case $password_page_id:
			$link = bp_loggedin_user_domain() . $bp->settings->slug .'/';
			break;

		default :
			$link = '';
			break;
	}

	return apply_filters( 'bpshop_get_redirect_link', $link );
}

/**
 * Redirect the user to their respective profile page
 *
 * @since 1.0.6
 */
function bpshop_redirect_to_profile() {
	global $post;

	if( ! isset( $post->ID ) || ! is_user_logged_in() )
		return false;

	$link = bpshop_get_redirect_link( $post->ID );

	if( ! empty( $link ) ) :
		wp_safe_redirect( $link );
		exit;
	endif;
}
add_action( 'template_redirect', 'bpshop_redirect_to_profile' );

/**
 * Link router function
 *
 * @since 	1.0.6
 * @uses	bp_get_option()
 * @uses	is_page()
 * @uses	bp_loggedin_user_domain()
 */
function bpshop_page_link_router( $link, $id )	{
	if( ! is_user_logged_in() || is_admin() )
		return $link;

	$new_link = bpshop_get_redirect_link( $id );

	if( ! empty( $new_link ) )
		$link = $new_link;

	return apply_filters( 'bpshop_router_link', $link );
}
add_filter( 'page_link', 'bpshop_page_link_router', 10, 2 );