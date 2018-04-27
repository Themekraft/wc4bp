<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, WooCommerce
 * @author         Boris Glumpler
 * @copyright      2011, Themekraft
 * @link           https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Conditional to check what page the user is on
 *
 * @since    1.0
 *
 * @param $page
 *
 * @return bool
 */
function wc4bp_is_page( $page ) {
	if ( bp_is_current_component( wc4bp_Manager::get_shop_slug() ) && bp_is_current_action( $page ) ) {
		return true;
	}

	return false;
}

/**
 * Conditional to check what page the user is on
 *
 * @since    1.0
 *
 * @param $sub
 *
 * @return bool
 */
function wc4bp_is_subpage( $sub ) {

	if ( bp_is_current_component( wc4bp_Manager::get_shop_slug() ) && bp_is_current_action( $sub ) || bp_is_action_variable( $sub, 0 ) ) {
		return true;
	}

	return false;
}

/**
 * Conditional to check what page the user is on
 *
 * @since    1.0
 *
 * @param $sub
 *
 * @return bool
 */
function wc4bp_is_subsubpage( $sub ) {
	if ( bp_is_current_component( wc4bp_Manager::get_shop_slug() ) && bp_is_action_variable( $sub, 1 ) ) {
		return true;
	}

	return false;
}
