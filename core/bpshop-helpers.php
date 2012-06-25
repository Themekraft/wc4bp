<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress,woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Look for the templates in the proper places
 * 
 * @since 1.0
 */
function bpshop_load_template_filter( $found_template, $templates )
{
	if( bp_is_current_component( 'shop' ) )
	{
		foreach( (array)$templates as $template )
		{
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
add_filter( 'bp_located_template', 'bpshop_load_template_filter', 10, 2 );

/**
 * Load a template in the correct order
 * 
 * @since 1.0
 */
function bpshop_load_template( $template_name )
{
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
 * Get the tracking page id

 * @todo	Check regularly if there is a db option
 * @since 	1.0
 */
function bpshop_get_tracking_page_id()
{
	global $wpdb;
	
	return $wpdb->get_var( $wpdb->prepare( "
		SELECT ID 
		FROM {$wpdb->posts} 
		WHERE post_name = 'order-tracking' 
		LIMIT 1
	" ) );
}

/**
 * Exclude all woocommerce pages from the main nav
 * 
 * Only used in default theme and possibly child themes
 * if no custom menu is defined for the top navigation
 * 
 * @since 	1.0
 * @uses	is_user_logged_in()
 * @uses	bp_get_option()
 * @uses	bpshop_get_tracking_page_id()
 */
function bpshop_exclude_pages_navigation( $args )
{
	if( ! is_user_logged_in() )
		return $args;
	
	$woo_pages = array(
		bp_get_option( 'woocommerce_cart_page_id' 			  ),
		bp_get_option( 'woocommerce_checkout_page_id' 		  ),
		bp_get_option( 'woocommerce_view_order_page_id' 	  ),
		bp_get_option( 'woocommerce_edit_address_page_id' 	  ),
		bp_get_option( 'woocommerce_myaccount_page_id' 	  ),
		bp_get_option( 'woocommerce_pay_page_id' 			  ),
		bp_get_option( 'woocommerce_thanks_page_id' 		  ),
		bp_get_option( 'woocommerce_change_password_page_id' ),
		bpshop_get_tracking_page_id()
	);
	
	$args['exclude'] = join( ',', $woo_pages );
	
	return apply_filters( 'bpshop_exclude_pages_navigation', $args, $woo_pages );
}
add_filter( 'wp_page_menu_args', 'bpshop_exclude_pages_navigation' );

/**
 * Adjust the checkout url to point to the profile
 * 
 * @since 	1.0
 * @uses	bp_loggedin_user_domain()
 * @uses	is_user_logged_in()
 */
function bpshop_checkout_url( $url )
{
	return ( is_user_logged_in() ) ? apply_filters( 'bpshop_checkout_url', bp_loggedin_user_domain() .'shop/cart/checkout/' ) : $url;
}
add_filter( 'woocommerce_get_checkout_url', 'bpshop_checkout_url' );
?>