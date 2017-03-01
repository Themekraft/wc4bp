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
}