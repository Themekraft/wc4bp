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
function bpshop_load_template_filter( $found_template, $templates ) {
    if( bp_is_current_component( 'shop' ) ) {
        foreach( (array)$templates as $template ) {
            if( file_exists( STYLESHEETPATH .'/'. $template ) )
                $filtered_templates[] = STYLESHEETPATH .'/'. $template;
                
            else
                $filtered_templates[] = BPSHOP_ABSPATH .'templates/'. $template;
        }
    
        return apply_filters( 'bpshop_load_template_filter', $filtered_templates[0] );
    }
    else
        return $found_template;
}
//add_filter( 'bp_located_template', 'bpshop_load_template_filter', 10, 2 );

/**
 * Load a template in the correct order
 * 
 * @since 1.0
 */
function bpshop_load_template( $template_name ) {
    global $bp;
    
    if( file_exists( STYLESHEETPATH .'/'. $template_name . '.php' ) )
        $located = STYLESHEETPATH .'/'. $template_name . '.php';
        
    elseif( file_exists( TEMPLATEPATH .'/'. $template_name . '.php' ) )
        $located = TEMPLATEPATH .'/'. $template_name . '.php';
    
    else
        $located = BPSHOP_ABSPATH .'templates/'. $template_name . '.php';

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
 * @uses    bpshop_get_tracking_page_id()
 */
function bpshop_exclude_pages_navigation( $args ) {
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
    
    return apply_filters( 'bpshop_exclude_pages_navigation', $args, $woo_pages );
}
add_filter( 'wp_page_menu_args', 'bpshop_exclude_pages_navigation' );

/**
 * Adjust the checkout url to point to the profile
 * 
 * @since   1.0
 * @uses    bp_loggedin_user_domain()
 * @uses    is_user_logged_in()
 */
function bpshop_checkout_url( $url ) {
    return ( is_user_logged_in() ) ? apply_filters( 'bpshop_checkout_url', bp_loggedin_user_domain() .'shop/cart/checkout/' ) : $url;
}
add_filter( 'woocommerce_get_checkout_url', 'bpshop_checkout_url' );

/**
 * Link to the user shop settings page
 * 
 * @since   unknown
 * @uses    bp_get_settings_slug()
 */
function bpshop_settings_link() {
    echo bpshop_get_settings_link();
}
    function bpshop_get_settings_link() {
        return apply_filters( 'bpshop_get_settings_link', trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() . '/shop' ) );
    }

/**
 * Adds an activity stream item when a user has written a new review to a product.
 *
 * @since   unknown
 * 
 * @uses bp_is_active() Checks that the Activity component is active
 * @uses bp_activity_add() Adds an entry to the activity component tables for a specific activity
 */
function bpshop_new_review_activity( $comment_id, $comment_data ) {
    if( ! bp_is_active( 'activity' ) )
        return false;

    // Get the product data
    $product = get_post( $comment_data->comment_post_ID );
    
    if( $product->post_type != 'product' )
        return false;

    $user_id = apply_filters( 'bpshop_new_review_activity_user_id', $comment_data->user_id );

    // check that user enabled updating the activity stream
    if( bp_get_user_meta( $user_id, 'notification_activity_shop_reviews', true ) == 'no' )
        return false;

    $user_link = bp_core_get_userlink( $user_id );

    // record the activity
    bp_activity_add( array(
        'user_id'   => $user_id,
        'action'    => apply_filters('bpshop_new_review_activity_action', 
                            sprintf(
                                __( '%s wrote a review about <a href="%s">%s</a>', 'bpshop' ), 
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
add_action( 'wp_insert_comment', 'bpshop_new_review_activity', 10, 2 );

/**
 * Adds an activity stream item when a user has purchased a new product(s).
 *
 * @since   unknown
 * 
 * @global 	object $bp Global BuddyPress settings object
 * @uses 	bp_activity_add() Adds an entry to the activity component tables for a specific activity
 */
function bpshop_new_purchase_activity( $order_id ) {
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
    $products = maybe_unserialize( $order->order_custom_fields['_order_items'][0] );
    $names    = array();
	
    foreach( $products as $product ){
        $names[] = '<a href="'. get_permalink( $product['id'] ).'">'. $product['name'] .'</a>';
    }
    
    // record the activity
    bp_activity_add( array(
        'user_id'   => $order->user_id,
        'action'    => apply_filters( 'bpshop_new_purchase_activity_action', 
                            sprintf(
                                __( '%s purchased %s', 'bpshop' ), 
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
add_action( 'woocommerce_payment_complete', 'bpshop_new_purchase_activity' );