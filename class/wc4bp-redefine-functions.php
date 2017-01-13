<?php

if ( ! function_exists( 'is_cart' ) ) :
	/**
	 * Check if we're on a cart page
	 *
	 * @since    1.0.5
	 */
	function is_cart() {
		$wc4bp_options = get_option( 'wc4bp_options' );
		
		if ( is_user_logged_in() && ! isset( $wc4bp_options['tab_cart_disabled'] ) ) :
			if ( bp_is_current_component( 'shop' ) && ! bp_action_variables() ) :
				return true;
			endif;
		else :
			return is_page( wc_get_page_id( 'cart' ) ) || defined( 'WOOCOMMERCE_CART' );
		endif;
		
		return false;
	}
endif;

if ( ! function_exists( 'is_order_received_page' ) ) {
	
	/**
	 * is_order_received_page - Returns true when viewing the order received page.
	 *
	 * @access public
	 * @return bool
	 */
	function is_order_received_page() {
		global $wp;
		
		if ( is_user_logged_in() ) :
			if ( bp_is_current_component( 'shop' ) && ( bp_is_action_variable( 'checkout' ) || bp_is_action_variable( 'cart' ) ) ) :
				return true;
			endif;
		else :
			if ( is_page( wc_get_page_id( 'checkout' ) ) && isset( $wp->query_vars['order-received'] ) ):
				return true;
			endif;
		endif;
		
		return false;
		
	}
}