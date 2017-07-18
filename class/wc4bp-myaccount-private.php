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
class WC4BP_MyAccount_Private {
	public function __construct() {
		add_filter( 'wp_head', array( $this, 'restrict_pages' ) );
	}
	public function restrict_pages() {
		$pages = WC4BP_MyAccount::get_available_endpoints();
		foreach ( $pages as $end_point_key => $end_point_value ) {
			if ( is_page( wc4bp_Manager::get_prefix() . $end_point_key ) && ! is_user_logged_in() ) {
				add_filter( 'the_content', array( $this, 'restrict_content' ), 999 );
			}
		}
	}
	public function restrict_content() {
		return apply_filters( 'wc4bp_restrict_message', __( 'Sorry this page is only for logging users!', 'wc4bp' ) );
	}
}