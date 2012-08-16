<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Synchronize the shipping and billing address from the profile
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
function bpshop_synch_addresses_from_profile( $field_id, $value ) {
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
		$geo = new WC_Countries();		
		$value = array_search( $value, $geo->countries );
	endif;

	bp_update_user_meta( bp_displayed_user_id(), $context .'_'. $key, $value );
}
add_action( 'xprofile_profile_field_data_updated', 'bpshop_synch_addresses_from_profile', 10, 2 );

/**
 * Synchronize the shipping and billing address from the admin area
 * 
 * @since 	1.0.5
 * @param	int 	$user_id	The user to synchronize the fields for
 */
function bpshop_synch_addresses_from_admin( $user_id ) {
	// get the woocommerce fields
 	$fields = woocommerce_get_customer_meta_fields();

	// woocommerce -> bpshop
	$mapped_fields = array(
		'_first_name' 	=> 'first_name',
		'_last_name' 	=> 'last_name',
		'_company' 		=> 'company',
		'_address_1' 	=> 'address',
		'_address_2' 	=> 'address-2',
		'_city' 		=> 'city',
		'_postcode' 	=> 'postcode',
		'_country' 		=> 'country',
		'_state' 		=> 'state',
		'_phone' 		=> 'phone',
		'_email' 		=> 'email'
	);
	
	// get the corresponding bpshop fields
	$shipping = bp_get_option( 'bpshop_shipping_address_ids' );
	$billing  = bp_get_option( 'bpshop_billing_address_ids'  );

 	foreach( $fields as $type => $fieldset ) :
		if( ! in_array( $type, array( 'billing', 'shipping' ) ) )
			continue;
		
 		foreach( $fieldset['fields'] as $key => $field ) :			
			// update the field	
 			if( isset( $_POST[$key] ) ) :
				// get the profile field id to update
				$mapped_key = str_replace( $type, '', $key );
				
				$field_id = $$type[$mapped_fields[$mapped_key]];
 			
				xprofile_set_field_data( $field_id, $user_id, $_POST[$key] );
			endif;
 		endforeach;
 	endforeach;

}
add_action( 'personal_options_update',  'bpshop_synch_addresses_from_admin' );
add_action( 'edit_user_profile_update', 'bpshop_synch_addresses_from_admin' );
