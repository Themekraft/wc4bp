<?php
/**
 * @package            WordPress
 * @subpackage         BuddyPress, WooCommerce
 * @author             Boris Glumpler
 * @copyright          2011, Themekraft
 * @link               https://github.com/Themekraft/BP-Shop-Integration
 * @license            http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Look for the templates in the proper places
 *
 * @since 1.0
 *
 * @param $found_template
 * @param $templates
 *
 * @return mixed
 */
function wc4bp_load_template_filter( $found_template, $templates ) {
	try {
		if ( bp_is_current_component( wc4bp_Manager::get_shop_slug() ) ) {
			foreach ( (array) $templates as $template ) {
				if ( file_exists( STYLESHEETPATH . '/' . $template ) ) {
					$filtered_templates[] = STYLESHEETPATH . '/' . $template;
				} else {
					$filtered_templates[] = WC4BP_ABSPATH . 'templates/' . $template;
				}
			}

			return apply_filters( 'wc4bp_load_template_filter', $filtered_templates[0] );
		} else {
			return $found_template;
		}
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
	}
}

//add_filter( 'bp_located_template', 'wc4bp_load_template_filter', 10, 2 );

/**
 * Load a template in the correct order
 *
 * @since 1.0
 */
function wc4bp_load_template( $template_name ) {
	try {
		global $bp;

		if ( file_exists( STYLESHEETPATH . '/' . $template_name . '.php' ) ) {
			$located = STYLESHEETPATH . '/' . $template_name . '.php';
		} elseif ( file_exists( TEMPLATEPATH . '/' . $template_name . '.php' ) ) {
			$located = TEMPLATEPATH . '/' . $template_name . '.php';
		} else {
			$located = WC4BP_ABSPATH . 'templates/' . $template_name . '.php';
		}

		include( $located );
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
	}
}


/**
 * Link to the user shop settings page
 *
 * @since   unknown
 * @uses    bp_get_settings_slug()
 */
function wc4bp_settings_link() {
	echo wc4bp_get_settings_link();
}

function wc4bp_get_settings_link() {
	return apply_filters( 'wc4bp_get_settings_link', trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() . '/' . wc4bp_Manager::get_shop_slug() ) );
}

function wc4bp_my_downloads_shortcode( $atts ) {
	wc_get_template( 'myaccount/my-downloads.php' );
}

add_shortcode( 'wc4bp_my_downloads', 'wc4bp_my_downloads_shortcode' );

function wc4bp_my_addresses_shortcode( $atts ) {
	wc_get_template( 'myaccount/my-address.php' );
}

add_shortcode( 'wc4bp_my_addresses', 'wc4bp_my_addresses_shortcode' );

function wc4bp_my_recent_orders_shortcode( $atts ) {
	try {
		global $bp;
		if ( ! isset( $bp->action_variables[1] ) ) {
			wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => 0 ) );
		} else {
			/**
			 * Execute the action from woo to view the order details
			 *
			 * @param string|int The order id
 			 */
			do_action( 'woocommerce_view_order', $bp->action_variables[1] );
		}
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
	}
}

add_shortcode( 'wc4bp_my_recent_orders', 'wc4bp_my_recent_orders_shortcode' );

/*
 * Inserts a new key/value after the key in the array.
 *
 * @param $key The key to insert after.
 * @param $array An array to insert in to.
 * @param $new_key The key to insert.
 * @param $new_value An value to insert.
 *
 * @return The new array if the key exists, FALSE otherwise.
 *
 */
function wc4bp_array_insert_after( $key, array &$array, $new_key, $new_value ) {
	if ( array_key_exists( $key, $array ) ) {
		$new = array();
		foreach ( $array as $k => $value ) {
			$new[ $k ] = $value;
			if ( $k === $key ) {
				$new[ $new_key ] = $new_value;
			}
		}

		return $new;
	}

	return false;
}
