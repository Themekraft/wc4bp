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
		$current_user = wp_get_current_user();
		if ( ! empty( $current_user ) && ! isset( $wc4bp_options['tab_cart_disabled'] ) ) {
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

	if ( is_checkout() && $is_current_component === true ) {

		$product_name .= apply_filters(
			'woocommerce_cart_item_remove_link',
			sprintf(
				'<a href="%s" rel="nofollow" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" style="float:left;margin-right:5px">&times;</a>',
				esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
				esc_attr__( 'Remove this item', 'wc4bp' ),
				esc_attr( $cart_item['product_id'] ),
				esc_attr( $cart_item_key ),
				esc_attr( $cart_item['data']->get_sku() )
			),
			$cart_item_key
		);

		return $product_name;
	}

	return $product_name;
}

/**
 * If there are no products in the checkout then, go to the cart
 */

add_action( 'wp_loaded', 'wc4bp_redirection_checkout', 99 );

function wc4bp_redirection_checkout() {

	global $woocommerce, $bp;
	$is_current_component = bp_is_current_component( wc4bp_Manager::get_shop_slug() );

	if ( is_checkout() && $is_current_component === true ) {

		if ( $woocommerce->cart->cart_contents_count === 0 ) {
			wp_safe_redirect( wc_get_cart_url() );
			exit;
		}
	}

	do_action( 'wc4bp_redirection_check' );
}

/**
 * Reload the cart page to remove the checkout tab
 */

add_action( 'wp_enqueue_scripts', 'wc4bp_url_cart_refresh' );

function wc4bp_url_cart_refresh() {

	global $woocommerce, $bp;
	$is_current_component = bp_is_current_component( wc4bp_Manager::get_shop_slug() );

	if ( is_cart() && $is_current_component === true ) {
		wp_enqueue_script( 'wc4bp-checkout-refresh-page', dirname( plugin_dir_url( __FILE__ ) ) . '/admin/js/wc4bp-cart-page-reload.js', array( 'jquery' ), '1.0.0', true );
	}
}

/**
 * Redirect after logout to avoid 404 error
 * on user profile pages
 */
add_action( 'wp_logout', 'wc4bp_safe_redirect_bp' );
function wc4bp_safe_redirect_bp() {
	wp_redirect( home_url() );
	exit();
}

add_filter( 'wc_get_template', 'wc4bp_change_wc_dashboard_template', 9999, 5 );
function wc4bp_change_wc_dashboard_template( $template, $template_name, $args, $template_path, $default_path ) {
	$theme_active      = wp_get_theme();
	$theme_active_name = $theme_active->template;
	$wc4bp_options     = get_option( 'wc4bp_options' );
	if ( ! is_array( $wc4bp_options ) ) {
		$wc4bp_options = (array) json_decode( $wc4bp_options );
	}
	if ( array_key_exists( 'tab_my_account_extra_content', $wc4bp_options ) && $wc4bp_options['tab_my_account_extra_content'] == '1' ) {

		if ( 'my-account.php' === basename( $template ) ) {
			$template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/shop/member/my-account.php';
		} elseif ( 'dashboard.php' === basename( $template ) ) {
			$template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/shop/member/dashboard.php';
		}

		if ( ! empty( $theme_active_name ) && ( $theme_active_name == 'buddyboss-theme' ) ) {
			if ( 'my-account.php' === basename( $template ) ) {
				$template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/shop/member/bb/my-account.php';
			} elseif ( 'dashboard.php' === basename( $template ) ) {
				$template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/shop/member/bb/dashboard.php';
			}
		}
	}
	return $template;
}


add_filter( 'woocommerce_account_menu_items', 'wc4bp_hide_my_account_tabs', 9999 );
function wc4bp_hide_my_account_tabs( $menu_links ) {
	$wc4bp_options = get_option( 'wc4bp_options' );
	if ( array_key_exists( 'tab_my_account_extra_content', $wc4bp_options ) && $wc4bp_options['tab_my_account_extra_content'] == '1' ) {
		unset( $menu_links['dashboard'] );
		unset( $menu_links['orders'] );
		unset( $menu_links['downloads'] );
		unset( $menu_links['edit-address'] );
		unset( $menu_links['payment-methods'] );
		unset( $menu_links['edit-account'] );
		unset( $menu_links['customer-logout'] );
	}
	return $menu_links;

}
