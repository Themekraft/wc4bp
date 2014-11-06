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
 * @since    1.0
 *
 * @uses    bp_get_option()
 * @uses    bp_update_user_meta()
 * @uses    bp_action_variable()
 * @uses    bp_displayed_user_id()
 * @param $field_id
 * @param $value
 */
function  wc4bp_sync_addresses_from_profile( $field_id, $value ) {
	$shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
	$billing  = bp_get_option( 'wc4bp_billing_address_ids'  );

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

add_action( 'xprofile_profile_field_data_updated', 'wc4bp_sync_addresses_from_profile', 10, 2 );

function  wc4bp_sync_xprofile_from_profile( $field_id, $value ) {

    $bf_xprofile_options = get_option('bf_xprofile_options');

    $field = new BP_XProfile_Field( $field_id );

    if (isset($bf_xprofile_options[$field->group_id][$field_id])){
        $field_slug = sanitize_title($field->group_id.'-'.$field_id);
        bp_update_user_meta( bp_displayed_user_id(), $field_slug, $value );
    }

}
add_action( 'xprofile_profile_field_data_updated', 'wc4bp_sync_xprofile_from_profile', 10, 2 );

/**
 * Synchronize the shipping and billing address from the admin area
 * 
 * @since 	1.0.5
 * @param	int 	$user_id	The user to synchronize the fields for
 */
function  wc4bp_sync_addresses_from_admin( $user_id ) {

	// get the woocommerce fields
    // $fields = WC_Countries::get_default_address_fields();
    $fields = wc4bp_get_customer_meta_fields();

	// get the mapped fields
	$mapped_fields =  wc4bp_get_mapped_fields();
	
	// get the corresponding  wc4bp fields
	$shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
	$billing  = bp_get_option( 'wc4bp_billing_address_ids'  );

 	foreach( $fields as $type => $fieldset ) :
		if( ! in_array( $type, array( 'billing', 'shipping' ) ) )
			continue;
		
		// get the kind of address to update
		$kind_of = $$type;

 		foreach( $fieldset['fields'] as $key => $field ) :			
			// update the field	
 			if( isset( $_POST[$key] ) ) :
				// get the profile field id to update
				$mapped_key = str_replace( $type, '', $key );
								
				// get the field id
				$field_id = $kind_of[$mapped_fields[$mapped_key]];

				// update if it isn't empty
				if( ! empty( $field_id ) )
					xprofile_set_field_data( $field_id, $user_id, $_POST[$key] );
			endif;
 		endforeach;
 	endforeach;
}
add_action( 'personal_options_update',  'wc4bp_sync_addresses_from_admin' );
add_action( 'edit_user_profile_update', 'wc4bp_sync_addresses_from_admin' );

/**
 * Synchronize the shipping and billing address to the profile
 * 
 * @since 	1.0.5
 * @param	int		$user_id	The user ID to synch the address for
 * @param	array 	$_post 		All cleaned POST data
 */
function  wc4bp_sync_addresses_to_profile( $user_id, $_post ) {
	// get the profile fields
	$shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
	$billing  = bp_get_option( 'wc4bp_billing_address_ids'  );

	// get the mapped fields
	$mapped_fields =  wc4bp_get_mapped_fields();
	
	// get the types of fields to update
	$types = array( 'billing', 'shipping' );
	
	foreach( $types as $type ) :
		// get the kind of address to update
		$kind_of = $$type;

		foreach( $mapped_fields as $wc_field => $wc4bp_field ) :
			if( isset( $_post[$type . $wc_field] ) ) :
				// get the field id to update
				$field_id = $kind_of[$wc4bp_field];
				
				if( ! empty( $field_id ) )
					xprofile_set_field_data( $field_id, $user_id, $_post[$type . $wc_field] );
			endif;
		endforeach;
	endforeach;	
}
add_action( 'woocommerce_checkout_update_user_meta',  'wc4bp_sync_addresses_to_profile', 10, 2 );

/**
 * Get the mapped fields (woocommerce ->  wc4bp)
 * 
 * Note that Woocommerce has 2 types of addresses, billing and shipping
 * Format: <code>billing{$key}</code> or <code>shipping{$key}</code>
 * 
 * @since 	1.0.5
 */
function  wc4bp_get_mapped_fields() {
	return array(
		'_first_name' 	=> 'first_name',
		'_last_name' 	=> 'last_name',
		'_company' 		=> 'company',
		'_address_1'   	=> 'address_1',
		'_address_2' 	=> 'address_2',
		'_city' 		=> 'city',
		'_postcode' 	=> 'postcode',
		'_country' 		=> 'country',
		'_state' 		=> 'state',
		'_phone' 		=> 'phone',
		'_email' 		=> 'email'
	);
}

/**
 * Get Address Fields for edit user pages
 */
function wc4bp_get_customer_meta_fields() {
    $show_fields = apply_filters('woocommerce_customer_meta_fields', array(
        'billing' => array(
            'title' => __('Customer Billing Address', 'wc4bp'),
            'fields' => array(
                'billing_first_name' => array(
                    'label' => __('First name', 'wc4bp'),
                    'description' => ''
                ),
                'billing_last_name' => array(
                    'label' => __('Last name', 'wc4bp'),
                    'description' => ''
                ),
                'billing_company' => array(
                    'label' => __('Company', 'wc4bp'),
                    'description' => ''
                ),
                'billing_address_1' => array(
                    'label' => __('Address 1', 'wc4bp'),
                    'description' => ''
                ),
                'billing_address_2' => array(
                    'label' => __('Address 2', 'wc4bp'),
                    'description' => ''
                ),
                'billing_city' => array(
                    'label' => __('City', 'wc4bp'),
                    'description' => ''
                ),
                'billing_postcode' => array(
                    'label' => __('Postcode', 'wc4bp'),
                    'description' => ''
                ),
                'billing_state' => array(
                    'label' => __('State/County', 'wc4bp'),
                    'description' => 'Country or state code'
                ),
                'billing_country' => array(
                    'label' => __('Country', 'wc4bp'),
                    'description' => '2 letter Country code'
                ),
                'billing_phone' => array(
                    'label' => __('Telephone', 'wc4bp'),
                    'description' => ''
                ),
                'billing_email' => array(
                    'label' => __('Email', 'wc4bp'),
                    'description' => ''
                )
            )
        ),
        'shipping' => array(
            'title' => __('Customer Shipping Address', 'wc4bp'),
            'fields' => array(
                'shipping_first_name' => array(
                    'label' => __('First name', 'wc4bp'),
                    'description' => ''
                ),
                'shipping_last_name' => array(
                    'label' => __('Last name', 'wc4bp'),
                    'description' => ''
                ),
                'shipping_company' => array(
                    'label' => __('Company', 'wc4bp'),
                    'description' => ''
                ),
                'shipping_address_1' => array(
                    'label' => __('Address 1', 'wc4bp'),
                    'description' => ''
                ),
                'shipping_address_2' => array(
                    'label' => __('Address 2', 'wc4bp'),
                    'description' => ''
                ),
                'shipping_city' => array(
                    'label' => __('City', 'wc4bp'),
                    'description' => ''
                ),
                'shipping_postcode' => array(
                    'label' => __('Postcode', 'wc4bp'),
                    'description' => ''
                ),
                'shipping_state' => array(
                    'label' => __('State/County', 'wc4bp'),
                    'description' => __('State/County or state code', 'wc4bp')
                ),
                'shipping_country' => array(
                    'label' => __('Country', 'wc4bp'),
                    'description' => __('2 letter Country code', 'wc4bp')
                )
            )
        )
    ));
    return $show_fields;
}