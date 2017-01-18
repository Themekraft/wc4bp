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
		$this->end_points = apply_filters( "wc4bp_woocommerce_endpoint_key_content", array(
			'orders',
			'downloads',
			'edit-address',
			'payment-methods',
			'edit-account'
		));
		foreach ( $this->end_points as $key ) {
			add_shortcode( $key, array( $this, "process_shortcodes" ) );
		}
	}

	public function process_shortcodes( $attr, $content = "", $tag ) {
		foreach ( $this->end_points as $key ) {
			if ( $tag == $key ) {
				$fnc = "wc4bp_my_account_process_shortcode_" . str_replace( "-", "_", $key );
				if ( method_exists( $this, $fnc ) ) {
					call_user_func( array( $this, $fnc ), $attr, $content = "" );
				}
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
	
	public function wc4bp_my_account_process_shortcode_edit_address( $attr, $content ) {
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