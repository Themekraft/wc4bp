<?php
/**
 * @package       	WordPress
 * @subpackage    	BuddyPress, Woocommerce
 * @author        	Boris Glumpler
 * @copyright		2011, Themekraft
 * @link        	https://github.com/Themekraft/BP-Shop-Integration
 * @license        	http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Look for the templates in the proper places
 * 
 * @since 1.0
 */
function  wc4bp_load_template_filter( $found_template, $templates ) {
    if( bp_is_current_component( 'shop' ) ) {
        foreach( (array)$templates as $template ) {
            if( file_exists( STYLESHEETPATH .'/'. $template ) )
                $filtered_templates[] = STYLESHEETPATH .'/'. $template;
                
            else
                $filtered_templates[] = WC4BP_ABSPATH .'templates/'. $template;
        }
    
        return apply_filters( 'wc4bp_load_template_filter', $filtered_templates[0] );
    }
    else
        return $found_template;
}
//add_filter( 'bp_located_template', 'wc4bp_load_template_filter', 10, 2 );

/**
 * Load a template in the correct order
 * 
 * @since 1.0
 */
function  wc4bp_load_template( $template_name ) {
    global $bp;
    
    if( file_exists( STYLESHEETPATH .'/'. $template_name . '.php' ) )
        $located = STYLESHEETPATH .'/'. $template_name . '.php';
        
    elseif( file_exists( TEMPLATEPATH .'/'. $template_name . '.php' ) )
        $located = TEMPLATEPATH .'/'. $template_name . '.php';
    
    else
        $located = WC4BP_ABSPATH .'templates/'. $template_name . '.php';

    include( $located );
}

/**
 * Exclude all woocommerce pages from the main nav
 * 
 * Only used in default theme and possibly child themes
 * if no custom menu is defined for the top navigation
 * 
 * @since   1.0
 * @uses    is_user_logged_in()
 * @uses    bp_get_option()
 * @uses     wc4bp_get_tracking_page_id()
 */
function  wc4bp_exclude_pages_navigation( $args ) {
    if( ! is_user_logged_in() )
        return $args;
    
    $woo_pages = array(
        bp_get_option( 'woocommerce_cart_page_id' ),
        bp_get_option( 'woocommerce_checkout_page_id' ),
        bp_get_option( 'woocommerce_view_order_page_id' ),
        bp_get_option( 'woocommerce_edit_address_page_id' ),
        bp_get_option( 'woocommerce_myaccount_page_id' ),
        bp_get_option( 'woocommerce_pay_page_id' ),
        bp_get_option( 'woocommerce_thanks_page_id' ),
        bp_get_option( 'woocommerce_change_password_page_id' ),
        woocommerce_get_page_id( 'order_tracking' )
    );
    
    $args['exclude'] = join( ',', $woo_pages );
    
    return apply_filters( 'wc4bp_exclude_pages_navigation', $args, $woo_pages );
}
add_filter( 'wp_page_menu_args', 'wc4bp_exclude_pages_navigation' );

/**
 * Adjust the checkout url to point to the profile
 * 
 * @since   1.0
 * @uses    bp_loggedin_user_domain()
 * @uses    is_user_logged_in()
 */
function  wc4bp_checkout_url( $url ) {
	$wc4bp_options		= get_option( 'wc4bp_options' ); 
	
	if( isset( $wc4bp_options['tab_cart_disabled']))
		return $url;
	
    return ( is_user_logged_in() ) ? apply_filters( 'wc4bp_checkout_url', bp_loggedin_user_domain() .'shop/cart/checkout/' ) : $url;
}
add_filter( 'woocommerce_get_checkout_url', 'wc4bp_checkout_url' );

/**
 * Link to the user shop settings page
 * 
 * @since   unknown
 * @uses    bp_get_settings_slug()
 */
function  wc4bp_settings_link() {
    echo  wc4bp_get_settings_link();
}
    function  wc4bp_get_settings_link() {
        return apply_filters( 'wc4bp_get_settings_link', trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() . '/shop' ) );
    }

/**
 * Adds an activity stream item when a user has written a new review to a product.
 *
 * @since   unknown
 * 
 * @uses bp_is_active() Checks that the Activity component is active
 * @uses bp_activity_add() Adds an entry to the activity component tables for a specific activity
 */
function  wc4bp_loader_review_activity( $comment_id, $comment_data ) {
    if( ! bp_is_active( 'activity' ) )
        return false;

    // Get the product data
    $product = get_post( $comment_data->comment_post_ID );
    
    if( $product->post_type != 'product' )
        return false;

    $user_id = apply_filters( 'wc4bp_loader_review_activity_user_id', $comment_data->user_id );

    // check that user enabled updating the activity stream
    if( bp_get_user_meta( $user_id, 'notification_activity_shop_reviews', true ) == 'no' )
        return false;

    $user_link = bp_core_get_userlink( $user_id );

    // record the activity
    bp_activity_add( array(
        'user_id'   => $user_id,
        'action'    => apply_filters('wc4bp_loader_review_activity_action',
                            sprintf(
                                __( '%s wrote a review about <a href="%s">%s</a>', 'wc4bp' ),
                                    $user_link,
                                    get_permalink($comment_data->comment_post_ID),
                                    $product->post_title
                                ), 
                                $user_id,
                                $comment_data,
                                $product
                        ),
        'component' => 'shop',
        'type'      => 'new_shop_review'
    ) );
}
add_action( 'wp_insert_comment', 'wc4bp_loader_review_activity', 10, 2 );

/**
 * Adds an activity stream item when a user has purchased a new product(s).
 *
 * @since   unknown
 * 
 * @global 	object $bp Global BuddyPress settings object
 * @uses 	bp_activity_add() Adds an entry to the activity component tables for a specific activity
 */
function  wc4bp_loader_purchase_activity( $order_id ) {
    if( ! is_user_logged_in() )
        return false;

    if( ! bp_is_active( 'activity' ) )
        return false;

    $order = new WC_Order( $order_id );

    if( $order->status != 'completed' )
        return false;
    
    if( $order->user_id != $order->customer_user )
        return false;

    $user_link = bp_core_get_userlink( $order->customer_user );

    // if several products - combine them, otherwise - display the product name
    $products = $order->get_items();
    $names    = array();
	
    foreach( $products as $product ){
        $names[] = '<a href="'. get_permalink( $product['item_meta']['_product_id'][0] ).'">'. $product['name'] .'</a>';
    }
    
    // record the activity
    bp_activity_add( array(
        'user_id'   => $order->user_id,
        'action'    => apply_filters( 'wc4bp_loader_purchase_activity_action',
                            sprintf(
                                __( '%s purchased %s', 'wc4bp' ),
                                    $user_link,
                                    implode(', ', $names)
                        	), 
                            $user_id,
                            $order,
                            $products
                        ),
        'component' => 'shop',
        'type'      => 'new_shop_purchase'
    ) );
}
add_action( 'woocommerce_order_status_completed', 'wc4bp_loader_purchase_activity');

function wc4bp_my_downloads_shortcode( $atts ){
    return woocommerce_get_template( 'myaccount/my-downloads.php' );
}
add_shortcode( 'wc4bp_my_downloads', 'wc4bp_my_downloads_shortcode' );


function wc4bp_my_recent_orders_shortcode( $atts ){
    return woocommerce_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) );
}
add_shortcode( 'wc4bp_my_recent_orders', 'wc4bp_my_recent_orders_shortcode' );