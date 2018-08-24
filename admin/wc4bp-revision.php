<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, WooCommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC4BP_Revision {
	public function __construct() {
		$user_id            = get_current_user_id();
		$wc4bp_review       = get_user_meta( $user_id, 'wc4bp-review', true );
		$wc4bp_review_later = get_user_meta( $user_id, 'wc4bp-review-later', true );
		$time_result        = false;
		if ( ! empty( $wc4bp_review_later ) ) {
			$wc4bp_review_now = new DateTime( 'now' );
			$time_comparison  = $wc4bp_review_now->diff( $wc4bp_review_later );
			$time_result      = ( 1 === $time_comparison->invert );
		}
		if ( empty( $wc4bp_review ) ) {
			if ( false !== $time_result || empty( $wc4bp_review_later ) ) {
				add_action( 'admin_notices', array( $this, 'ask_for_revision' ) );
				add_action( 'network_admin_notices', array( $this, 'ask_for_revision' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'revision_script' ), 10 );
				add_action( 'wp_ajax_wc4bp_revision_review', array( $this, 'wc4bp_revision_trigger' ) );
				add_action( 'wp_ajax_wc4bp_revision_later', array( $this, 'wc4bp_revision_trigger' ) );
				add_action( 'wp_ajax_wc4bp_revision_already', array( $this, 'wc4bp_revision_trigger' ) );
			}
		}
	}

	public function ask_for_revision() {
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_revision.php' );
	}

	public function revision_script( $hook ) {
		try {
			wp_enqueue_style( 'wc4bp_admin_revision_css', wc4bp_Manager::assets_path('wc4bp-revision','css') );
			wp_enqueue_script( 'wc4bp_admin_revision_js', wc4bp_Manager::assets_path( 'wc4bp-revision'), array( 'jquery' ), WC4BP_Loader::VERSION );
			wp_localize_script( 'wc4bp_admin_revision_js', 'wc4bp_admin_revision_js', array(
				'nonce'   => wp_create_nonce( 'wc4bp_review_nonce' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			) );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_revision_trigger() {
		if ( ! defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}
		$user_id = get_current_user_id();
		check_ajax_referer( 'wc4bp_review_nonce', 'nonce' );
		$trigger = Request_Helper::get_post_param( 'trigger' );
		if ( ! empty( $trigger ) ) {
			switch ( $trigger ) {
				case 'review':
					update_user_meta( $user_id, 'wc4bp-review', true );
					break;
				case 'later':
					$remind = new DateTime( 'now' );
					$remind->add( new DateInterval( 'P5D' ) );
					update_user_meta( $user_id, 'wc4bp-review-later', $remind );
					break;
				case 'already':
					update_user_meta( $user_id, 'wc4bp-review', true );
					break;
			}
		}
	}
}
