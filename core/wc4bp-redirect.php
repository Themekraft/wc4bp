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
function  wc4bp_get_redirect_link( $id = false ) {
	global $bp;

	$wc4bp_options	= get_option( 'wc4bp_options' ); 
	$wc4bp_pages_options	= get_option( 'wc4bp_pages_options' ); 

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
			if( ! isset( $wc4bp_options['tab_cart_disabled']) && $wc4bp_options['tab_shop_default'] == 'default')
				$link = bp_loggedin_user_domain() .'shop/home/';
			break;

		case $checkout_page_id:
			if( ! isset( $wc4bp_options['tab_cart_disabled']))
				$link = bp_loggedin_user_domain() .'shop/home/checkout/';
			break;

		case $thanks_page_id:
			if( ! isset( $wc4bp_options['tab_cart_disabled']))
				$link = bp_loggedin_user_domain() .'shop/home/checkout/thanks/';
			break;

		case $pay_page_id:
			if( ! isset( $wc4bp_options['tab_cart_disabled']))
				$link = bp_loggedin_user_domain() .'shop/home/checkout/pay/';
			break;

		case $track_page_id:
			if( ! isset( $wc4bp_options['tab_track_disabled']))
				$link = bp_loggedin_user_domain() .'shop/track/';
			break;

		case $account_page_id:
			if( ! isset( $wc4bp_options['tab_history_disabled']))
				$link = bp_loggedin_user_domain() .'shop/history/';
			break;

		case $view_page_id:
			if( ! isset( $wc4bp_options['tab_history_disabled']))
				$link = bp_loggedin_user_domain() .'shop/history/view/';
			break;

		case $address_page_id:
			$type = ( isset( $_GET['address'] ) ) ? $_GET['address'] : 'billing';

			switch( $type )	{
				case 'shipping' :
					$ids = bp_get_option( 'wc4bp_shipping_address_ids' );
					$url = bp_loggedin_user_domain(). $bp->profile->slug .'/edit/group/'. $ids['group_id'];
					break;

				case 'billing' :
					$ids = bp_get_option( 'wc4bp_billing_address_ids' );
					$url = bp_loggedin_user_domain(). $bp->profile->slug .'/edit/group/'. $ids['group_id'];
					break;
			}
			break;

		case $password_page_id:
			$link = bp_loggedin_user_domain() . $bp->settings->slug .'/';
			break;

		default :
			
			
			
			break;
	}
	if(isset($wc4bp_pages_options['selected_pages']) && is_array($wc4bp_pages_options['selected_pages'])){
					
		foreach ($wc4bp_pages_options['selected_pages'] as $key => $attached_page) {

			if($attached_page['children'] > 0){
				$the_page_id	= get_top_parent_page_id($attached_page['page_id']);
				$the_courent_id	= get_top_parent_page_id($id);
			} else {
				$the_page_id	= $attached_page['page_id'];
				$the_courent_id	= $id;
			}
			
			if($the_page_id == $the_courent_id){
				$post_data = get_post($id, ARRAY_A);
				$slug = $post_data['post_name'];
				$link = bp_loggedin_user_domain() .'shop/'.$attached_page['tab_slug'].'/'.$slug;
			}
			
	 	}
	}

	return apply_filters( 'wc4bp_get_redirect_link', $link );
}

function get_top_parent_page_id($post_id) {
	
	
    $ancestors = get_post_ancestors( $post_id );

    // Check if page is a child page (any level)
    if ($ancestors) {

        //  Grab the ID of top-level page from the tree
        return end($ancestors);

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
function  wc4bp_redirect_to_profile() {
	global $post;

	if( ! isset( $post->ID ) || ! is_user_logged_in() )
		return false;

	$link =  wc4bp_get_redirect_link( $post->ID );

	if( ! empty( $link ) ) :
		wp_safe_redirect( $link );
		exit;
	endif;
}
add_action( 'template_redirect', 'wc4bp_redirect_to_profile' );

/**
 * Link router function
 *
 * @since 	1.0.6
 * @uses	bp_get_option()
 * @uses	is_page()
 * @uses	bp_loggedin_user_domain()
 */
function  wc4bp_page_link_router( $link, $id )	{
	if( ! is_user_logged_in() || is_admin() )
		return $link;

	$new_link =  wc4bp_get_redirect_link( $id );

	if( ! empty( $new_link ) )
		$link = $new_link;

	return apply_filters( 'wc4bp_router_link', $link );
}
add_filter( 'page_link', 'wc4bp_page_link_router', 10, 2 );