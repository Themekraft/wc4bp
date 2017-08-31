<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wc4bp_Woocommerce {
	
	public function __construct() {
		// Check if we are on checkout in profile
		add_filter( 'woocommerce_is_checkout', array( $this, 'wc4bp_woocommerce_is_checkout' ) );
		if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$professional_plan_id ) ) {
			// Check if we are on the my account page in profile
			add_filter( 'woocommerce_is_account_page', array( $this, 'wc4bp_woocommerce_is_account_page__premium_only' ) );
		}
		add_filter( 'woocommerce_get_endpoint_url', array( $this, 'endpoint_url' ), 1, 4 );
        add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ), 1, 1 );
	}

	public function available_payment_gateways($_available_gateways){
        $available_gateways = array();
        foreach ($_available_gateways as $key=>$gateway ){
            if ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
                $available_gateways[$key]=$gateway;

            }
        }

        return $available_gateways;
    }
	public function wc4bp_woocommerce_is_account_page__premium_only( $is_account_page ) {
		
		if ( is_user_logged_in() ) {
			if ( bp_is_current_component( 'shop' ) && bp_is_current_action( 'checkout' ) ) {
				$is_account_page = true;
			}
		}
		
		return $is_account_page;
	}
	
	public function wc4bp_woocommerce_is_checkout( $is_checkout ) {
		$wc4bp_options = get_option( 'wc4bp_options' );
		
		if ( is_user_logged_in() && ! isset( $wc4bp_options['tab_checkout_disabled'] ) ) {
			if ( bp_is_current_component( 'shop' ) && ( bp_is_current_action( 'checkout' ) || bp_is_current_action( 'home' ) ) ) {
				$is_checkout = true;
			}
		}
		
		return $is_checkout;
	}
	
	public function endpoint_url( $url, $endpoint, $value, $permalink ) {
		global $current_user, $bp, $wp;
		
		$current_user = wp_get_current_user();
		$userdata     = get_userdata( $current_user->ID );
		
		$base_path = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/shop/';
		
		switch ( $endpoint ) {
			case "payment-methods":
				$url = $base_path . 'payment';
				break;
			case 'order-received':
				$checkout_page_id = wc_get_page_id( 'checkout' );
				$checkout_page    = get_post( $checkout_page_id );
				$url              = get_bloginfo( 'url' ) . '/' . $checkout_page->post_name . '/' . $endpoint . '/' . $value;
				//If checkout page do not exist, asign this url.
				if($checkout_page_id == -1){
					$url = $base_path .'/wc4pb_orders/view-order/'.$value;
				}
				
				break;
			case "set-default-payment-method":
			case "delete-payment-method":
				$url = add_query_arg( $endpoint, $value, $base_path . 'payment' );
				break;
			case "add-payment-method":
				$url = add_query_arg( $endpoint, "w2ewe3423ert", $base_path . 'wc4pb_payment-methods' );
				break;
		}
		
		return $url;
	}
}