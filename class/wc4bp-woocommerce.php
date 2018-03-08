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

	private $wc4bp_options;

	public function __construct() {
		$this->wc4bp_options = get_option( 'wc4bp_options' );
		// Check if we are on checkout in profile
		if ( ! isset( $this->wc4bp_options['tab_activity_disabled'] ) ) {
			add_filter( 'woocommerce_is_checkout', array( $this, 'wc4bp_woocommerce_is_checkout' ) );
			if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$professional_plan_id ) ) {
				// Check if we are on the my account page in profile
				add_filter( 'woocommerce_is_account_page', array( $this, 'wc4bp_woocommerce_is_account_page__premium_only' ) );
			}
			add_filter( 'woocommerce_get_endpoint_url', array( $this, 'endpoint_url' ), 1, 4 );
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ), 1, 1 );
		}
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
			if ( 'payment-methods' === $c_action && ! isset( $this->wc4bp_options['wc4bp_endpoint_payment-methods'] ) ) {
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

	public function wc4bp_woocommerce_is_account_page__premium_only( $is_account_page ) {
		$default = $is_account_page;
		try {
			if ( is_user_logged_in() ) {
				if ( bp_is_current_component( wc4bp_Manager::get_shop_slug() ) && bp_is_current_action( 'checkout' ) ) {
					$is_account_page = true;
				}
			}

			return $is_account_page;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

			return $default;
		}
	}

	/*
	 * @param is_checkout
	 *
	 * @return true if is the woocommerce checkout page
	 * */
	public function wc4bp_woocommerce_is_checkout( $is_checkout ) {
		$default = $is_checkout;
		try {
			if ( is_user_logged_in() && ! isset( $this->wc4bp_options['tab_checkout_disabled'] ) ) {
				if ( bp_is_current_component( wc4bp_Manager::get_shop_slug() ) && ( bp_is_current_action( 'checkout' ) || bp_is_current_action( 'home' ) ) ) {
					$is_checkout = true;
				}
			}

			return $is_checkout;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

			return $default;
		}
	}

	/*
	 * @param url
	 * @param endpoint
	 * @param value
	 * @param permalink
	 * @return the url of the woocommerce endpoint
	*/
	public function endpoint_url( $url, $endpoint, $value, $permalink ) {
		$default = $url;
		try {
			$base_path = wc4bp_redirect::get_base_url();
			switch ( $endpoint ) {
                case 'orders':
                    $url = $base_path . $endpoint . '/' . $value;
                    break;
				case 'edit-address':
					if ( ! isset( $this->wc4bp_options['wc4bp_endpoint_edit-address'] ) ) {
						$url = $base_path . $endpoint . '/' . $value;
					}
					break;
				case 'payment-methods':
					if ( ! isset( $this->wc4bp_options['wc4bp_endpoint_payment-methods'] ) ) {
						$url = add_query_arg( $endpoint, 'w2ewe3423ert', $base_path . 'payment-methods' );
					}
					break;
				case 'order-received':
					$checkout_page_id = wc_get_page_id( 'checkout' );
					$checkout_page    = get_post( $checkout_page_id );
					$url              = get_bloginfo( 'url' ) . '/' . $checkout_page->post_name . '/' . $endpoint . '/' . $value;
					//If checkout page do not exist, assign this url.
					if ( - 1 === $checkout_page_id ) {
						$url = $base_path . '/orders/view-order/' . $value;
					}
					break;
				case 'set-default-payment-method':
				case 'delete-payment-method':
					if ( ! isset( $this->wc4bp_options['wc4bp_endpoint_payment-methods'] ) ) {
						$url = add_query_arg( $endpoint, $value, $base_path . 'payment' );
					}
					break;
				case 'add-payment-method':
					if ( ! isset( $this->wc4bp_options['wc4bp_endpoint_payment-methods'] ) ) {
						$url = add_query_arg( $endpoint, 'w2ewe3423ert', $base_path . 'payment-methods' );
					}
					break;
			}

			return $url;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

			return $default;
		}
	}
}
