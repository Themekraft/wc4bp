<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
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
	
	public function __construct() {
		/**
		 * Apply fitlers to the endpoint shortcodes to handle woocommerce my account individual tabs
		 */
		$this->end_points = apply_filters( "wc4bp_woocommerce_endpoint_key_content", array(
				'orders'          => array( $this, "wc4bp_my_account_process_shortcode_orders" ),
				'downloads'       => array( $this, "wc4bp_my_account_process_shortcode_downloads" ),
				'edit-address'    => array( $this, "wc4bp_my_account_process_shortcode_edit_address" ),
				'payment-methods' => array( $this, "wc4bp_my_account_process_shortcode_payment_methods" ),
				'edit-account'    => array( $this, "wc4bp_my_account_process_shortcode_edit_account" ),
			)
		);
		foreach ( $this->end_points as $key => $class ) {
			add_shortcode( $key, array( $this, "process_shortcodes" ) );
		}
	}
	
	public function process_shortcodes( $attr, $content = "", $tag ) {
		foreach ( $this->end_points as $key => $class ) {
			if ( $tag == $key ) {
				call_user_func( $class, $attr, $content = "" );
			}
		}
	}
	
	public function wc4bp_my_account_process_shortcode_orders( $attr, $content ) {
		wc_print_notices();
		woocommerce_account_orders( 1 );//TODO get the current page
	}
	
	public function wc4bp_my_account_process_shortcode_downloads( $attr, $content ) {
		//TODO need to implement
		echo "Pending";
	}
	
	public static function wc4bp_my_account_process_shortcode_edit_address( $attr, $content ) {
		wc_print_notices();
		WC_Shortcode_My_Account::edit_address();
	}
	
	public function wc4bp_my_account_process_shortcode_payment_methods( $attr, $content ) {
		wc_print_notices();
		woocommerce_account_payment_methods();
	}
	
	public function wc4bp_my_account_process_shortcode_edit_account( $attr, $content ) {
		wc_print_notices();
		WC_Shortcode_My_Account::edit_account();
	}
	
	
}