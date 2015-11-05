<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, ShabuShabu Webdesign
 * @link		http://shabushabu.eu
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Activation routine
 * 
 * Add all BudddyPress profile groups and fields
 *
 * @todo	- Also check xprofile_insert_field to see if 'type' accepts
 * 			  a value of 'option'
 * @since 	1.0
 */
function  wc4bp_activate() {
	global $wpdb, $bp;

    if( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
            add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'WC BP Integration needs WooCommerce to be installed. <a href="%s">Download it now</a>!\', " wc4bp" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
            return;
    }

	if(is_multisite()):
	  if( get_blog_option( BP_ROOT_BLOG, 'wc4bp_installed' ) )
		return false;
	  
	  	// we need to create the extra profile groups
		// and corresponding fields here
		$default_country = get_blog_option( BP_ROOT_BLOG, 'woocommerce_default_country' );
	else:
	 	if( get_option( 'wc4bp_installed' ) )
			return false;
		  
		  	// we need to create the extra profile groups
			// and corresponding fields here
			$default_country = get_option( 'woocommerce_default_country' );
	endif;
	
	$geo = new WC_Countries();
	
	$billing 			   = array();

	if ( bp_is_active( 'xprofile' ) ) {
		$billing['group_id']   = xprofile_insert_field_group( array(
			'name' => 'Billing Address'
		) );

		$billing['first_name'] = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'First Name', 'field_order' => 1, 'is_required' => 1  ) );
		$billing['last_name']  = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'Last Name', 	'field_order' => 2, 'is_required' => 1	) );
		$billing['company']    = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'Company',	'field_order' => 3, 'is_required' => 0	) );
		$billing['address_1']  = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'Address 1',	'field_order' => 4,	'is_required' => 1	) );
		$billing['address_2']  = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'Address 2',	'field_order' => 5, 'is_required' => 0	) );
		$billing['city'] 	   = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'City', 		'field_order' => 6, 'is_required' => 1	) );
		$billing['postcode']   = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'Postcode', 	'field_order' => 7,	'is_required' => 1	) );
		$billing['country']    = xprofile_insert_field( array(
			'field_group_id' => $billing['group_id'],
			'type' 			 => 'selectbox',
			'order_by'		 => 'asc',
			'name' 			 => 'Country',
			'is_required' 	 => 1,
			'field_order' 	 => 8
		) );
		
		// we need to query directly as xprofile_insert_field
		// does not accept 'option' as type
		$counter = 1;
		foreach( $geo->countries as $country_code => $country ) :
			$is_default = ( $country_code == $default_country ) ? 1 : 0;
			$wpdb->query( $wpdb->prepare( "
				INSERT INTO {$bp->profile->table_name_fields}
				(group_id, parent_id, type, name, description, is_required, option_order, is_default_option)
				VALUES
				(%d, %d, 'option', %s, '', 0, %d, %d)",
				$billing['group_id'], $billing['country'], $country, $counter, $is_default
			) );
			$counter++;
		endforeach;
		
		$billing['state'] = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'State', 			'field_order' =>  9, 'is_required' => 1 ) );
		$billing['email'] = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'Email Address',  'field_order' => 10, 'is_required' => 1 ) );
		$billing['phone'] = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'Phone', 			'field_order' => 11, 'is_required' => 1 ) );
		$billing['fax']   = xprofile_insert_field( array( 'field_group_id' => $billing['group_id'], 'type' => 'textbox', 'name' => 'Fax', 			'field_order' => 12, 'is_required' => 0 ) );
		
		$shipping				= array();
		$shipping['group_id']   = xprofile_insert_field_group( array(
			'name' => 'Shipping Address'
		) );

		$shipping['first_name']	= xprofile_insert_field( array( 'field_group_id' => $shipping['group_id'], 'type' => 'textbox', 'name' => 'First Name',		'field_order' => 1, 'is_required' => 1	) );
		$shipping['last_name']	= xprofile_insert_field( array( 'field_group_id' => $shipping['group_id'], 'type' => 'textbox', 'name' => 'Last Name',		'field_order' => 2, 'is_required' => 1	) );
		$shipping['company']	= xprofile_insert_field( array( 'field_group_id' => $shipping['group_id'], 'type' => 'textbox', 'name' => 'Company',		'field_order' => 3, 'is_required' => 0	) );
		$shipping['address_1']	= xprofile_insert_field( array( 'field_group_id' => $shipping['group_id'], 'type' => 'textbox', 'name' => 'Address 1',		'field_order' => 4,	'is_required' => 1	) );
		$shipping['address_2']	= xprofile_insert_field( array( 'field_group_id' => $shipping['group_id'], 'type' => 'textbox', 'name' => 'Address 2',		'field_order' => 5, 'is_required' => 0	) );
		$shipping['city']		= xprofile_insert_field( array( 'field_group_id' => $shipping['group_id'], 'type' => 'textbox', 'name' => 'City',			'field_order' => 6,	'is_required' => 1	) );
		$shipping['postcode']	= xprofile_insert_field( array( 'field_group_id' => $shipping['group_id'], 'type' => 'textbox', 'name' => 'Postcode',		'field_order' => 7,	'is_required' => 1	) );
		$shipping['country']	= xprofile_insert_field( array(
			'field_group_id' => $shipping['group_id'],
			'order_by'		 => 'asc',
			'type' 			 => 'selectbox',
			'name' 			 => 'Country',
			'is_required' 	 => 1,
			'field_order' 	 => 8
		) );

		// we need to query directly as xprofile_insert_field
		// does not accept 'option' as type
		$counter = 1;
		foreach( $geo->countries as $country_code => $country ) :
			$is_default = ( $country_code == $default_country ) ? 1 : 0;
			$wpdb->query( $wpdb->prepare( "
				INSERT INTO {$bp->profile->table_name_fields}
				(group_id, parent_id, type, name, description, is_required, option_order, is_default_option)
				VALUES
				(%d, %d, 'option', %s, '', 0, %d, %d)",
				$shipping['group_id'], $shipping['country'], $country, $counter, $is_default
			) );
			$counter++;
		endforeach;
		
		$shipping['state'] = xprofile_insert_field( array( 'field_group_id' => $shipping['group_id'], 'type' => 'textbox', 'name' => 'State','field_order' => 9, 'is_required' => 1 ) );
		
		if(is_multisite()):
			// set the plugin to be installed
			update_blog_option( BP_ROOT_BLOG, 'wc4bp_installed', 			 true	   );
			// save the shipping data
			update_blog_option( BP_ROOT_BLOG, 'wc4bp_shipping_address_ids', $shipping );
			// save the billing data
			update_blog_option( BP_ROOT_BLOG, 'wc4bp_billing_address_ids',  $billing  );
		else:
			// set the plugin to be installed
			update_option( 'wc4bp_installed', 			 true	   );
			// save the shipping data
			update_option( 'wc4bp_shipping_address_ids', $shipping );
			// save the billing data
			update_option( 'wc4bp_billing_address_ids',  $billing  );
		endif;

	    update_option('wc4bp_options',Array('tab_shop_default' => 'default'));

	    wc4bp_bp_xprofile_update_field_meta($billing);
	    wc4bp_bp_xprofile_update_field_meta($shipping);
	}
}

/**
 * Uninstall routine
 * 
 * Cleans up after uninstalling the plugin. Removes options
 * and BP profile groups plus associated data
 *
 * @since 	1.0
 */
function  wc4bp_cleanup() {
	if(is_multisite()):
		if ( bp_is_active( 'xprofile' ) ) {
			xprofile_delete_field_group( get_blog_option( BP_ROOT_BLOG, 'wc4bp_shipping_address_ids' ) );
			xprofile_delete_field_group( get_blog_option( BP_ROOT_BLOG, 'wc4bp_billing_address_ids'  ) );
		}
		
		delete_blog_option( BP_ROOT_BLOG, 'wc4bp_shipping_address_ids' );
		delete_blog_option( BP_ROOT_BLOG, 'wc4bp_billing_address_ids'  );
		delete_blog_option( BP_ROOT_BLOG, 'wc4bp_installed' 			);
	else:
		if ( bp_is_active( 'xprofile' ) ) {
			xprofile_delete_field_group( get_option( 'wc4bp_shipping_address_ids' ) );
			xprofile_delete_field_group( get_option( 'wc4bp_billing_address_ids'  ) );
		}
		
		delete_option( 'wc4bp_shipping_address_ids' );
		delete_option( 'wc4bp_billing_address_ids'  );
		delete_option( 'wc4bp_installed' 			);
	endif;		
	
}

function wc4bp_bp_xprofile_update_field_meta($field_ids){

    foreach($field_ids as $key => $field_id){
        bp_xprofile_update_field_meta( $field_id, 'default_visibility', 'adminsonly' );
        bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', 'disabled' );
    }

}

