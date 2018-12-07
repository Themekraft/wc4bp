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

if ( ! function_exists( 'is_order_received_page' ) ) {
	/**
	 * is_order_received_page - Returns true when viewing the order received page.
	 *
	 * @access public
	 * @return bool
	 */
	function is_order_received_page() {
		global $wp;

		if ( is_user_logged_in() ) {
			if ( bp_is_current_component( wc4bp_Manager::get_shop_slug() ) && ( bp_is_action_variable( 'checkout' ) || bp_is_action_variable( 'cart' ) ) ) {
				return true;
			}
		} else {
			if ( is_page( wc_get_page_id( 'checkout' ) ) && isset( $wp->query_vars['order-received'] ) ) {
				return true;
			}
		}

		return false;
	}
}
// TODO commented in order to avoid incompatibility with the payment method page. In some install woo my account page is not detected
//if ( ! function_exists( 'is_add_payment_method_page' ) ) {
//
//	/**
//	 * Is_add_payment_method_page - Returns true when viewing the add payment method page.
//	 *
//	 * @return bool
//	 */
//	function is_add_payment_method_page() {
//
//		$is_wc4pb_component = bp_is_current_component( wc4bp_Manager::get_shop_slug() );
//
//		$add_payment_method = ( class_exists( 'Request_Helper' ) ) ? Request_Helper::simple_get( 'add-payment-method' ) : false;
//
//		$is_checkout = bp_is_current_action( 'checkout' );
//
//		return ( $is_wc4pb_component && (! empty( $add_payment_method ) || $is_checkout ) );
//	}
//}
