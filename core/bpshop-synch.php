<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress,woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Synchronize the shipping and billing address
 * 
 * Makes sure that the addresses are always the same
 * to avoid template problems. Note that $$context is a
 * variable variable and not a misspelling :)
 *
 * @since 	1.0
 * 
 * @uses	bp_get_option()
 * @uses	bp_update_user_meta()
 * @uses	bp_action_variable()
 * @uses	bp_displayed_user_id()
 */
function bpshop_synch_addresses( $field_id, $value )
{
	$shipping = bp_get_option( 'bpshop_shipping_address_ids' );
	$billing  = bp_get_option( 'bpshop_billing_address_ids'  );
	
	if( ! in_array( bp_action_variable( 1 ), array( $shipping['group_id'], $billing['group_id'] ) ) )
		return false;
	
	$context = ( $shipping['group_id'] == bp_action_variable( 1 ) ) ? 'shipping' : 'billing';
	
	// group ids can have duplicate field ids, so we need to unset them here
	unset( $shipping['group_id'] );
	unset( $billing['group_id']  );

	// change $$context to something else and the sky will fall on your head
	$key = array_search( $field_id, $$context );

	if( ! $key )
		return false;
	
	if( $key == 'country' ) :
		$geo = new woocommerce_countries();		
		$value = array_search( $value, $geo->countries );
	endif;

	bp_update_user_meta( bp_displayed_user_id(), $context .'_'. $key, $value );
}
add_action( 'xprofile_profile_field_data_updated', 'bpshop_synch_addresses', 10, 2 );
?>