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

//Manage Woocommerce hooks
class wc4bp_Woocommerce {

	public function __construct() {
		// Check if we are on checkout in profile
		add_filter( 'woocommerce_is_checkout', array( $this, 'wc4bp_woocommerce_is_checkout' ) );
		if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$professional_plan_id ) ) {
			// Check if we are on the my account page in profile
			add_filter( 'woocommerce_is_account_page', array( $this, 'wc4bp_woocommerce_is_account_page__premium_only' ) );
		}
		add_filter( 'woocommerce_get_endpoint_url', array( $this, 'endpoint_url' ), 1, 4 );
		add_filter( 'wcs_get_view_subscription_url', array( $this, 'wc4bp_get_view_subscription_url' ), 1, 2 );
		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ), 1, 1 );
	}

	/**
	 * Return a list of  payment gateways that supports 'add_payment_method'
	 *
	 * @param array $_available_gateways
	 *
	 * @return array
	 * */
	public function available_payment_gateways( $_available_gateways ) {
		$default = $_available_gateways;
		try {
			global $bp;
			$c_action           = $bp->current_action;
			$available_gateways = array();
			if ( 'wc4pb_payment-methods' === $c_action ) {
				foreach ( $_available_gateways as $key => $gateway ) {
					if ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
						$available_gateways[ $key ] = $gateway;
					}
				}
			} else {
				$available_gateways = $_available_gateways;
			}

			return $available_gateways;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

			return $default;
		}
	}


	public function wc4bp_get_view_subscription_url( $view_subscription_url, $id ) {
		global $bp;
		$c_action                   = $bp->current_action;
		$current_user               = wp_get_current_user();
		$userdata                   = get_userdata( $current_user->ID );
		$link                       = $view_subscription_url;
		$my_account_page_id         = get_option( 'woocommerce_myaccount_page_id' );
		$woo_my                     = get_post( $my_account_page_id );
		$wc4bp_subscriptions_active = is_plugin_active( 'wc4bp-subscriptions/wc4bp-subscriptions.php' );

		//If the wc4bp_subscriptions add-on is disable then the url of the subscription will go to
		//woocommerce page
		if ( ! $wc4bp_subscriptions_active ) {
			if ( $c_action === 'wc4pb_subscriptions' ) {
				$link = get_bloginfo( 'url' ) . '/' . $woo_my->post_name . '/view-subscription/' . $id;
			}
			if ( $c_action === 'wc4pb_orders' ) {

				$link = get_bloginfo( 'url' ) . '/' . $woo_my->post_name . '/view-subscription/' . $id;
			}
		}

		return $link;
	}


	public function wc4bp_woocommerce_is_account_page__premium_only( $is_account_page ) {
		$default = $is_account_page;
		try {
			if ( is_user_logged_in() ) {
				if ( bp_is_current_component( 'shop' ) && bp_is_current_action( 'checkout' ) ) {
					$is_account_page = true;
				}
			}

			return $is_account_page;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

			return $default;
		}
	}

	public function wc4bp_woocommerce_is_checkout( $is_checkout ) {
		$default = $is_checkout;
		try {
			$wc4bp_options = get_option( 'wc4bp_options' );

			if ( is_user_logged_in() && ! isset( $wc4bp_options['tab_checkout_disabled'] ) ) {
				if ( bp_is_current_component( 'shop' ) && ( bp_is_current_action( 'checkout' ) || bp_is_current_action( 'home' ) ) ) {
					$is_checkout = true;
				}
			}

			return $is_checkout;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

			return $default;
		}
	}

	public function endpoint_url( $url, $endpoint, $value, $permalink ) {
		$default = $url;
		try {
			global $current_user, $bp, $wp;

			$current_user = wp_get_current_user();
			$user_data    = get_userdata( $current_user->ID );

			$base_path = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $user_data->user_nicename . '/shop/';//TODO we need to put a dynamic value here

			switch ( $endpoint ) {
				case 'payment-methods':
					//$url = $base_path . 'payment';
					$url = add_query_arg( $endpoint, 'w2ewe3423ert', $base_path . 'wc4pb_payment-methods' );//TODO we need to put a dynamic value here
					break;
				case 'order-received':
					$checkout_page_id = wc_get_page_id( 'checkout' );
					$checkout_page    = get_post( $checkout_page_id );
					$url              = get_bloginfo( 'url' ) . '/' . $checkout_page->post_name . '/' . $endpoint . '/' . $value;
					//If checkout page do not exist, assign this url.
					if ( $checkout_page_id == - 1 ) {
						$url = $base_path . '/wc4pb_orders/view-order/' . $value;//TODO this is wrong
					}
					break;
				case 'set-default-payment-method':
				case 'delete-payment-method':
					$url = add_query_arg( $endpoint, $value, $base_path . 'payment' );
					break;
				case 'add-payment-method':
					$url = add_query_arg( $endpoint, 'w2ewe3423ert', $base_path . 'wc4pb_payment-methods' );//TODO we need to put a dynamic value here
					break;
			}

			return $url;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

			return $default;
		}
	}
}