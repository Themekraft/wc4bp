<?php
/**
 * @package         WordPress
 * @subpackage      BuddyPress, WooCommerce
 * @author          Boris Glumpler
 * @copyright       2011, ShabuShabu Webdesign
 * @link            http://shabushabu.eu
 * @license         http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Activation routine
 *
 * Add all BudddyPress profile groups and fields
 *
 * @since    1.0
 *
 */
function wc4bp_activate() {
	try {
		global $wpdb, $bp;

		$wc4bp_sync = new wc4bp_Sync( false );

		if ( ! wc4bp_Manager::is_woocommerce_active() ) {
			return false;
		}

		if ( is_multisite() ) {
			if ( get_blog_option( BP_ROOT_BLOG, 'wc4bp_installed' ) ) {
				return false;
			}

			// we need to create the extra profile groups
			// and corresponding fields here
			$default_country = get_blog_option( BP_ROOT_BLOG, 'woocommerce_default_country' );
		} else {
			if ( get_option( 'wc4bp_installed' ) ) {
				return false;
			}

			// we need to create the extra profile groups
			// and corresponding fields here
			$default_country = get_option( 'woocommerce_default_country' );
		}

		$geo = new WC_Countries();

		$billing = array();

		if ( bp_is_active( 'xprofile' ) ) {
			$insert_billing_group  = true;
			$insert_shipping_group = true;
			//Get all the groups from the database
			$groups = BP_XProfile_Group::get( array(
				'fetch_fields' => true,
			) );
			//look if a record with the billing code already exist
			/** @var BP_XProfile_Group $current */
			foreach ( $groups as $current ) {
				/**
				 * Get the String ID used to identify Billing Group ID used in BuddyPress
				 *
				 * @param string
				 */
				$billing_string_id = apply_filters( 'wc4bp_billing_group_id', 'billing' );
				if ( $billing_string_id === $current->description ) {
					//If exist a record with the billing code take it, and avoid insert a new one
					$insert_billing_group = false;
					$type                 = $current->description;
					/** @var BP_XProfile_Field $field */
					foreach ( $current->fields as $field ) {
						$slug = $wc4bp_sync->get_slug_of_field( $type, $field->name, true );
						if ( false !== $slug ) {
							$billing[ $slug ] = $field->id;
						}
					}
					break;
				}
			}

			if ( $insert_billing_group ) {
				$billing['group_id'] = xprofile_insert_field_group( array(
					'name'        => __( 'Billing Address', 'wc4bp' ),
					'description' => 'billing', //WE USE THE DESCRIPTION FIELD AS KEY,FOR UNIQUE CODE
				) );

				$billing['first_name'] = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'First Name', 'wc4bp' ),
					'field_order'    => 1,
					'is_required'    => 1,
				) );
				$billing['last_name']  = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Last Name', 'wc4bp' ),
					'field_order'    => 2,
					'is_required'    => 1,
				) );
				$billing['company']    = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Company', 'wc4bp' ),
					'field_order'    => 3,
					'is_required'    => 0,
				) );
				$billing['address_1']  = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Address 1', 'wc4bp' ),
					'field_order'    => 4,
					'is_required'    => 1,
				) );
				$billing['address_2']  = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Address 2', 'wc4bp' ),
					'field_order'    => 5,
					'is_required'    => 0,
				) );
				$billing['city']       = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'City', 'wc4bp' ),
					'field_order'    => 6,
					'is_required'    => 1,
				) );
				$billing['postcode']   = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Postcode', 'wc4bp' ),
					'field_order'    => 7,
					'is_required'    => 1,
				) );
				$billing['country']    = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'selectbox',
					'order_by'       => 'asc',
					'name'           => __( 'Country', 'wc4bp' ),
					'is_required'    => 1,
					'field_order'    => 8,
				) );

				// we need to query directly as xprofile_insert_field
				// does not accept 'option' as type
				$counter = 1;
				foreach ( $geo->get_countries() as $country_code => $country ) {
					$is_default = ( $country_code == $default_country ) ? 1 : 0;
					$wpdb->query( $wpdb->prepare( " INSERT INTO {$bp->profile->table_name_fields}
					(group_id, parent_id, type, name, description, is_required, option_order, is_default_option)
					VALUES (%d, %d, 'option', %s, '', 0, %d, %d)", array( $billing['group_id'], $billing['country'], $country, $counter, $is_default ) ) );
					$counter ++;
				}

				$billing['state'] = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'State/County', 'wc4bp' ),
					'field_order'    => 9,
					'is_required'    => 1,
				) );
				$billing['email'] = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Email Address', 'wc4bp' ),
					'field_order'    => 10,
					'is_required'    => 1,
				) );
				$billing['phone'] = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Phone', 'wc4bp' ),
					'field_order'    => 11,
					'is_required'    => 1,
				) );
				$billing['fax']   = xprofile_insert_field( array(
					'field_group_id' => $billing['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Fax', 'wc4bp' ),
					'field_order'    => 12,
					'is_required'    => 0,
				) );
			}
			$shipping = array();
			//Look if exist a record with the shipping code
			/** @var BP_XProfile_Group $current */
			foreach ( $groups as $current ) {
				/**
				 * Get the String ID used to identify Shipping Group ID used in BuddyPress
				 *
				 * @param string
				 */
				$shipping_string_id = apply_filters( 'wc4bp_shipping_group_id', 'shipping' );
				if ( $shipping_string_id === $current->description ) {
					// If exist a record with the shipping code take it and avoid inserting a new one
					$insert_shipping_group = false;
					$type                  = $current->description;
					/** @var BP_XProfile_Field $field */
					foreach ( $current->fields as $field ) {
						$slug = $wc4bp_sync->get_slug_of_field( $type, $field->name, true );
						if ( false !== $slug ) {
							$shipping[ $slug ] = $field->id;
						}
					}
					break;
				}
			}
			if ( $insert_shipping_group ) {
				$shipping['group_id'] = xprofile_insert_field_group( array(
					'name'        => __( 'Shipping Address', 'wc4bp' ),
					'description' => 'shipping', //WE USE THE DESCRIPTION FIELD AS KEY,FOR UNIQUE CODE
				) );

				$shipping['first_name'] = xprofile_insert_field( array(
					'field_group_id' => $shipping['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'First Name', 'wc4bp' ),
					'field_order'    => 1,
					'is_required'    => 1,
				) );
				$shipping['last_name']  = xprofile_insert_field( array(
					'field_group_id' => $shipping['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Last Name', 'wc4bp' ),
					'field_order'    => 2,
					'is_required'    => 1,
				) );
				$shipping['company']    = xprofile_insert_field( array(
					'field_group_id' => $shipping['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Company', 'wc4bp' ),
					'field_order'    => 3,
					'is_required'    => 0,
				) );
				$shipping['address_1']  = xprofile_insert_field( array(
					'field_group_id' => $shipping['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Address 1', 'wc4bp' ),
					'field_order'    => 4,
					'is_required'    => 1,
				) );
				$shipping['address_2']  = xprofile_insert_field( array(
					'field_group_id' => $shipping['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Address 2', 'wc4bp' ),
					'field_order'    => 5,
					'is_required'    => 0,
				) );
				$shipping['city']       = xprofile_insert_field( array(
					'field_group_id' => $shipping['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'City', 'wc4bp' ),
					'field_order'    => 6,
					'is_required'    => 1,
				) );
				$shipping['postcode']   = xprofile_insert_field( array(
					'field_group_id' => $shipping['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'Postcode', 'wc4bp' ),
					'field_order'    => 7,
					'is_required'    => 1,
				) );
				$shipping['country']    = xprofile_insert_field( array(
					'field_group_id' => $shipping['group_id'],
					'order_by'       => 'asc',
					'type'           => 'selectbox',
					'name'           => __( 'Country', 'wc4bp' ),
					'is_required'    => 1,
					'field_order'    => 8,
				) );

				// we need to query directly as xprofile_insert_field
				// does not accept 'option' as type
				$counter = 1;
				foreach ( $geo->get_countries() as $country_code => $country ) {
					$is_default = ( $country_code == $default_country ) ? 1 : 0;
					$wpdb->query( $wpdb->prepare( "INSERT INTO {$bp->profile->table_name_fields}
					(group_id, parent_id, type, name, description, is_required, option_order, is_default_option)
					VALUES (%d, %d, 'option', %s, '', 0, %d, %d)", array( $shipping['group_id'], $shipping['country'], $country, $counter, $is_default ) ) );
					$counter ++;
				}

				$shipping['state'] = xprofile_insert_field( array(
					'field_group_id' => $shipping['group_id'],
					'type'           => 'textbox',
					'name'           => __( 'State/County', 'wc4bp' ),
					'field_order'    => 9,
					'is_required'    => 1,
				) );
			}
			if ( is_multisite() ) {
				// set the plugin to be installed
				update_blog_option( BP_ROOT_BLOG, 'wc4bp_installed', true );
				// save the shipping data
				update_blog_option( BP_ROOT_BLOG, 'wc4bp_shipping_address_ids', $shipping );
				// save the billing data
				update_blog_option( BP_ROOT_BLOG, 'wc4bp_billing_address_ids', $billing );
			} else {
				// set the plugin to be installed
				update_option( 'wc4bp_installed', true );
				// save the shipping data
				update_option( 'wc4bp_shipping_address_ids', $shipping );
				// save the billing data
				update_option( 'wc4bp_billing_address_ids', $billing );
			}

			update_option( 'wc4bp_options', array(
				'tab_shop_default' => 'default',
			) );

			wc4bp_bp_xprofile_update_field_meta( $billing );
			wc4bp_bp_xprofile_update_field_meta( $shipping );
		}

		return true;
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

		return false;
	}


}

/**
 * Clean routine
 *
 * Cleans up after uninstalling the plugin or onSync. Removes options and BP profile groups (Shipping/Billing) plus associated data
 *
 */
function wc4bp_cleanup() {
	try {
		if ( function_exists( 'xprofile_delete_field_group' ) ) { //Proceed if the function exist
			if ( is_multisite() ) {
				$wc4bp_shipping_address_ids = get_blog_option( BP_ROOT_BLOG, 'wc4bp_shipping_address_ids' );
				if ( is_array( $wc4bp_shipping_address_ids ) && ! empty( $wc4bp_shipping_address_ids ) && ! empty( $wc4bp_shipping_address_ids['group_id'] ) ) {
					$result_delete_field_1 = xprofile_delete_field_group( $wc4bp_shipping_address_ids['group_id'] );
					if ( ! empty( $result_delete_field_1 ) ) {
						$delete_result_1 = delete_blog_option( BP_ROOT_BLOG, 'wc4bp_shipping_address_ids' );
					}
				}
				$wc4bp_billing_address_ids = get_blog_option( BP_ROOT_BLOG, 'wc4bp_billing_address_ids' );
				if ( is_array( $wc4bp_billing_address_ids ) && ! empty( $wc4bp_billing_address_ids ) && ! empty( $wc4bp_billing_address_ids['group_id'] ) ) {
					$result_delete_field_2 = xprofile_delete_field_group( $wc4bp_billing_address_ids['group_id'] );
					if ( ! empty( $result_delete_field_2 ) ) {
						$delete_result_2 = delete_blog_option( BP_ROOT_BLOG, 'wc4bp_billing_address_ids' );
					}
				}
				if ( ! empty( $delete_result_1 ) && ! empty( $delete_result_2 ) ) {
					delete_blog_option( BP_ROOT_BLOG, 'wc4bp_installed' );
				}
			} else {
				$wc4bp_shipping_address_ids = get_option( 'wc4bp_shipping_address_ids' );
				if ( is_array( $wc4bp_shipping_address_ids ) && ! empty( $wc4bp_shipping_address_ids ) && ! empty( $wc4bp_shipping_address_ids['group_id'] ) ) {
					$result_delete_field_1 = xprofile_delete_field_group( $wc4bp_shipping_address_ids['group_id'] );
					if ( ! empty( $result_delete_field_1 ) ) {
						$delete_result_1 = delete_option( 'wc4bp_shipping_address_ids' );
					}
				}
				$wc4bp_billing_address_ids = get_option( 'wc4bp_billing_address_ids' );
				if ( is_array( $wc4bp_billing_address_ids ) && ! empty( $wc4bp_billing_address_ids ) && ! empty( $wc4bp_billing_address_ids['group_id'] ) ) {
					$result_delete_field_2 = xprofile_delete_field_group( $wc4bp_billing_address_ids['group_id'] );
					if ( ! empty( $result_delete_field_2 ) ) {
						$delete_result_2 = delete_option( 'wc4bp_billing_address_ids' );
					}
				}
				if ( ! empty( $delete_result_1 ) && ! empty( $delete_result_2 ) ) {
					delete_option( 'wc4bp_installed' );
				}
				wc4bp_Sync::clean_xprofield_fields_cached();
			}
		}
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
	}
}

function wc4bp_bp_xprofile_update_field_meta( $field_ids ) {
	try {
		foreach ( $field_ids as $key => $field_id ) {
			bp_xprofile_update_field_meta( $field_id, 'default_visibility', 'adminsonly' );
			bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', 'disabled' );
		}
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
	}
}

