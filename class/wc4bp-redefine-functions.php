<?php
/**
 * This file is to override functions and keep the compatibility with older ones
 */
if ( ! function_exists( 'is_cart' ) ) {
	/**
	 * Check if we're on a cart page
	 *
	 * @since    1.0.5
	 */
	function is_cart() {
		$wc4bp_options = get_option( 'wc4bp_options' );

		if ( is_user_logged_in() && ! isset( $wc4bp_options['tab_cart_disabled'] ) ) {
			if ( bp_is_current_component( wc4bp_Manager::get_shop_slug() ) && ! bp_action_variables() ) {
				return true;
			}
		} else {
			return is_page( wc_get_page_id( 'cart' ) ) || defined( 'WOOCOMMERCE_CART' );
		}

		return false;
	}
}

// TODO commented in order to avoid incompatibility with the payment method page. In some install woo my account page is not detected
if ( ! function_exists( 'is_add_payment_method_page' ) ) {

	/**
	 * Is_add_payment_method_page - Returns true when viewing the add payment method page.
	 *
	 * @return bool
	 */
	function is_add_payment_method_page() {

		global $wp;

		$page_id = wc_get_page_id( 'myaccount' );

		$is_page_id          = is_page( $page_id );
		$payment_methods     = isset( $wp->query_vars['payment-methods'] );
		$add_payment_methods = isset( $wp->query_vars['add-payment-method'] );

		$is_wc4pb_component = bp_is_current_component( wc4bp_Manager::get_shop_slug() );

		$add_payment_method = ( class_exists( 'Request_Helper' ) ) ? Request_Helper::simple_get( 'add-payment-method' ) : false;

		$is_checkout = bp_is_current_action( 'checkout' );

		return ( ( ( $page_id && $is_page_id ) || ( $is_wc4pb_component || $is_checkout ) && ( $payment_methods || $add_payment_method || $add_payment_methods ) ) );
	}
}


/**
 * Add Remove button to checkout page   
 */ 

add_filter( 'woocommerce_cart_item_name', 'wc4bp_filter_wc_checkout_item_remove', 10, 3 );

function wc4bp_filter_wc_checkout_item_remove( $product_name, $cart_item, $cart_item_key ) {
	
	global $woocommerce, $bp;
	$is_current_component = bp_is_current_component( wc4bp_Manager::get_shop_slug() );
	
	if ( is_checkout() && $is_current_component == 1 ) {
		
		$product_name .= apply_filters('woocommerce_cart_item_remove_link', sprintf(
		'<a href="%s" rel="nofollow" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" style="float:left;margin-right:5px">&times;</a>',
		esc_url(wc_get_cart_remove_url($cart_item_key)),
		esc_attr__('Remove this item', 'wc4bp'),
		esc_attr($cart_item['product_id']),
		esc_attr( $cart_item_key ),
		esc_attr($cart_item['data']->get_sku())),
		$cart_item_key);

		return $product_name;
    }
}