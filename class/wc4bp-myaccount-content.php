<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, WooCommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC4BP_MyAccount_Content {

	private $end_points;
	private $is_payment_short_code;
	private $exist_stripe_payment;

	public function __construct() {
		try {
			/**
			 * Filter the endpoint shortcodes to handle woocommerce my account individual tabs.
			 *
			 * @param array $args {
			 *     @type string Key The string to identify each tab item.
			 *     @type callable Value The callable to handle the content of each tab item.
			 * }
			 */
			$this->end_points = apply_filters( 'wc4bp_woocommerce_endpoint_key_content', array(
				'orders'              => array( $this, 'wc4bp_my_account_process_shortcode_orders' ),
				'downloads'           => array( $this, 'wc4bp_my_account_process_shortcode_downloads' ),
				'edit-address'        => array( $this, 'wc4bp_my_account_process_shortcode_edit_address' ),
				'payment-methods'     => array( $this, 'wc4bp_my_account_process_shortcode_payment_methods' ),
				'edit-account'        => array( $this, 'wc4bp_my_account_process_shortcode_edit_account' ),
				'add-payment-methods' => array( $this, 'wc4bp_my_account_process_shortcode_add_payment_methods' ),
			) );
			foreach ( $this->end_points as $key => $class ) {
				add_shortcode( $key, array( $this, 'process_shortcodes' ) );
			}
			add_action( 'wp_footer', array( $this, 'add_footer_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function process_shortcodes( $attr, $content = '', $tag ) {
		try {
			foreach ( $this->end_points as $key => $class ) {
				if ( $tag == $key ) {
					call_user_func( $class, $attr, $content );
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}


	public function wc4bp_my_account_process_shortcode_orders( $attr, $content ) {
		global $wp;
		try {
			wc_print_notices();
			$current_page = 1;
			if ( isset( $wp->query_vars['orders'] ) ) {
				$current_page = $wp->query_vars['orders'];
			}
			woocommerce_account_orders( $current_page );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_my_account_process_shortcode_downloads( $attr, $content ) {
		try {
			wc_print_notices();
			woocommerce_account_downloads();
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public static function wc4bp_my_account_process_shortcode_edit_address( $attr, $content ) {
		global $wp;
		try {
			wc_print_notices();
			$load_address = '';
			if ( isset( $wp->query_vars['edit-address'] ) ) {
				$load_address = $wp->query_vars['edit-address'];
			}

			woocommerce_account_edit_address( $load_address );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}


	public function wc4bp_my_account_process_shortcode_payment_methods( $attr, $content ) {
		try {
			wc_print_notices();
			$result = Request_Helper::simple_get( 'add-payment-method' );
			if ( ! empty( $result ) ) {
				if ( class_exists( 'WooCommerce' ) ) {
					$this->is_payment_short_code = true;
					if ( class_exists( 'WC_Gateway_Stripe' ) ) {
						$this->exist_stripe_payment = true;
					}
				}
				woocommerce_account_add_payment_method();
			} else {
				woocommerce_account_payment_methods();
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function add_scripts() {
		$is_wc4pb_component = bp_is_current_component( wc4bp_Manager::get_shop_slug() );
		$is_checkout        = bp_is_current_action( 'checkout' );

		if ( $is_wc4pb_component && ( $is_checkout ) ) {
			$this->add_woocommerce_scripts();
			$this->add_stripe_scripts();
		}
	}

	public function add_footer_scripts() {
		if ( $this->is_payment_short_code ) {
			$this->add_woocommerce_scripts();
			if ( $this->exist_stripe_payment ) {
				$this->add_stripe_scripts();
			}
		}
	}

	public function add_stripe_scripts() {
		if ( class_exists( 'WC_Gateway_Stripe' ) ) {
			$payment_management = new WC_Gateway_Stripe();
			if ( $payment_management->settings['enabled'] === 'yes' ) {
				$payment_management->payment_scripts();
			}
		}
	}

	public function add_woocommerce_scripts() {
		if ( class_exists( 'WooCommerce' ) ) {
			$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$path    = WC()->plugin_url() . 'assets/js/frontend/add-payment-method' . $suffix . '.js';
			$deps    = array( 'jquery', 'woocommerce' );
			$version = WC()->version;
			wp_register_script( 'wc-add-payment-method', $path, $deps, $version, true );
			wp_enqueue_script( 'wc-add-payment-method' );
		}
	}

	public function wc4bp_my_account_process_shortcode_add_payment_methods( $attr, $content ) {
		try {
			wc_print_notices();
			woocommerce_account_add_payment_method();
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_my_account_process_shortcode_edit_account( $attr, $content ) {
		try {
			wc_print_notices();
			WC_Shortcode_My_Account::edit_account();
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}


}
