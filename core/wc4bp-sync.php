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
function  wc4bp_sync_addresses_from_profile($user_id, $field_id, $value ) {

    // get the profile fields
    $shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
    $billing  = bp_get_option( 'wc4bp_billing_address_ids'  );

    $shipping_key = array_search( $field_id, $shipping );
    $billing_key = array_search( $field_id, $billing );

    if( $shipping_key ){
        $type       = 'shipping';
        $field_slug = $shipping_key;
    }

    if( $billing_key ){
        $type       = 'billing';
        $field_slug = $billing_key;
    }

    if( ! isset($type) )
        return false;

    if( $type == 'country' ) :
        $geo = new WC_Countries();
        $value = array_search( $value, $geo->countries );
    endif;

    if(empty($user_id))
        $user_id = bp_displayed_user_id();

    update_user_meta( $user_id, $type . '_' . $field_slug, $value );
}


function wc4bp_xprofile_profile_field_data_updated($field_id, $value ){
  global $bp;

  $user_id = bp_loggedin_user_id();
  if(isset($_GET['user_id']))
      $user_id = $_GET['user_id'];

  wc4bp_sync_addresses_from_profile($user_id, $field_id, $value );

}
add_action( 'xprofile_profile_field_data_updated', 'wc4bp_xprofile_profile_field_data_updated', 10, 3 );


/**
 * Synchronize the shipping and billing address to the profile
 *
 * @since 	1.0.5
 * @param	int		$user_id	The user ID to synch the address for
 * @param	array 	$_post 		All cleaned POST data
 */
function  wc4bp_sync_addresses_to_profile( $user_id ) {

	// get the profile fields
	$shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
	$billing  = bp_get_option( 'wc4bp_billing_address_ids'  );

  $groups = BP_XProfile_Group::get(array(
      'fetch_fields' => true
  ));


  if ( !empty( $groups ) ) : foreach ( $groups as $group ) :

      if ( empty( $group->fields ) )
          continue;

      foreach ( $group->fields as $field ) {

          $billing_key    = array_search( $field->id  , $billing  );
          $shipping_key   = array_search( $field->id  , $shipping );

          if( $shipping_key ){
              $type       = 'shipping';
              $field_slug = $shipping_key;
          }

          if( $billing_key ){
              $type       = 'billing';
              $field_slug = $billing_key;
          }

          if( isset($field_slug) )
              xprofile_set_field_data( $field->id, $user_id, $_POST[ $type . '_' . $field_slug] );

      }

    endforeach; endif;

}
add_action( 'personal_options_update'               ,  'wc4bp_sync_addresses_to_profile' , 10, 1 );
add_action( 'edit_user_profile_update'              , 'wc4bp_sync_addresses_to_profile'  , 10, 1 );
add_action( 'woocommerce_checkout_update_user_meta' ,  'wc4bp_sync_addresses_to_profile' , 10, 1 );

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
