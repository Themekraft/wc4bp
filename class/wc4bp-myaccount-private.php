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

class WC4BP_MyAccount_Private {
	public function __construct() {
		add_filter( 'wp_head', array( $this, 'restrict_pages' ) );
	}

	public function restrict_pages() {
		try {
			$pages = WC4BP_MyAccount::get_available_endpoints();
			foreach ( $pages as $end_point_key => $end_point_value ) {
				if ( is_page( $end_point_key ) && ! is_user_logged_in() ) {
					add_filter( 'the_content', array( $this, 'restrict_content' ), 999 );
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function restrict_content() {
		/**
		 * Restrict content String.
		 *
		 * String used to print if somebody with no permission try to access to one of the endpoint enable.
		 *
		 * @param string  The localize message to show.
		 */
		return apply_filters( 'wc4bp_restrict_message', __( 'Sorry this page is only for logging users!', 'wc4bp' ) );
	}
}