<?php
/**
 * @package       WordPress
 * @subpackage    BuddyPress, WooCommerce
 * @author        Boris Glumpler
 * @copyright     2011, Themekraft
 * @link          https://github.com/Themekraft/BP-Shop-Integration
 * @license       http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wc4bp_Sync {

	public function __construct( $init = true ) {
		if ( $init ) {
			$this->init();
		}
	}

	private function init() {
		add_action( 'xprofile_profile_field_data_updated', array( $this, 'wc4bp_xprofile_profile_field_data_updated' ), 10, 3 );
		add_action( 'personal_options_update', array( $this, 'wc4bp_sync_addresses_to_profile' ), 10, 1 );
		add_action( 'edit_user_profile_update', array( $this, 'wc4bp_sync_addresses_to_profile' ), 10, 1 );
		add_action( 'woocommerce_checkout_update_user_meta', array( $this, 'wc4bp_sync_addresses_to_profile' ), 10, 1 );
		add_action( 'woocommerce_customer_save_address', array( $this, 'wc4bp_sync_addresses_from_woo_my_account' ), 10, 2 );
	}

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
	 *
	 * @param $user_id
	 * @param $field_id
	 * @param $value
	 *
	 * @return bool
	 */
	static function wc4bp_sync_addresses_from_profile( $user_id, $field_id, $value ) {
		try {
			// get the profile fields
			$ids      = self::wc4bp_get_xprofield_fields_ids();
			$shipping = $ids['shipping'];
			$billing  = $ids['billing'];

			if ( is_array( $shipping ) ) {
				if ( isset( $shipping['group_id'] ) ) {
					unset( $shipping['group_id'] );
				}
				$shipping_key = array_search( $field_id, $shipping, true );
			} elseif ( isset( $shipping->group_id ) ) {
				unset( $shipping->group_id );
				$shipping_key = self::extract_field( $field_id, $shipping->fields );
			}
			if ( is_array( $billing ) ) {
				if ( isset( $billing['group_id'] ) ) {
					unset( $billing['group_id'] );
				}
				$billing_key = array_search( $field_id, $billing, true );
			} elseif ( isset( $billing->group_id ) ) {
				unset( $billing->group_id );
				$billing_key = self::extract_field( $field_id, $shipping->fields );
			}

			if ( ! empty( $shipping_key ) ) {
				$type       = 'shipping';
				$field_slug = $shipping_key;
			}

			if ( ! empty( $billing_key ) ) {
				$type       = 'billing';
				$field_slug = $billing_key;
			}

			if ( ! isset( $type ) ) {
				return false;
			}

			if ( 'country' === $shipping_key || 'country' === $billing_key ) {
				$geo   = new WC_Countries();
				$value = array_search( $value, $geo->get_countries(), true );
			}

			if ( empty( $user_id ) ) {
				$user_id = bp_displayed_user_id();
			}

			if ( ! empty( $user_id ) && wc4bp_Manager::is_request( 'frontend' ) ) {
				$customer        = new WC_Customer( $user_id, true );
				$call_update_fnc = 'set_' . $type . '_' . $field_slug;
				if ( method_exists( $customer, $call_update_fnc ) && ! empty( $customer ) ) {
					$customer->$call_update_fnc( $value );
					$customer->get_data_store()->update( $customer );
					add_action( 'shutdown', array( $customer, 'save' ), 10 );
				}
			}

			return update_user_meta( $user_id, $type . '_' . $field_slug, $value );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}

		return false;
	}

	public static function extract_field( $field_id, $array_of_fields ) {
		if ( is_array( $array_of_fields ) ) {
			foreach ( $array_of_fields as $array_of_field ) {
				if ( $array_of_field instanceof BP_XProfile_Field ) {
					if ( $array_of_field->id === $field_id ) {
						return $array_of_field->Name;
					}
				}
			}
		}

		return false;
	}


	function wc4bp_xprofile_profile_field_data_updated( $field_id, $value ) {
		try {
			global $bp;
			$user_id = Request_Helper::simple_get( 'user_id', 'sanitize_text_field', bp_loggedin_user_id() );
			self::wc4bp_sync_addresses_from_profile( $user_id, $field_id, $value );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public static function wc4bp_get_xprofield_fields_ids() {
		$result = wp_cache_get( 'wc4bp_get_xprofield_fields_ids', 'wc4bp' );
		if ( false === $result ) {
			$result['shipping'] = bp_get_option( 'wc4bp_shipping_address_ids' );
			$result['billing']  = bp_get_option( 'wc4bp_billing_address_ids' );
			wp_cache_add( 'wc4bp_get_xprofield_fields_ids', $result, 'wc4bp' );
		}

		return $result;
	}

	public static function clean_xprofield_fields_cached() {
		wp_cache_delete( 'wc4bp_get_xprofield_fields_ids', 'wc4bp' );
	}

	/**
	 * Synchronize the shipping and billing address to the profile
	 *
	 * @since    1.0.5
	 *
	 * @param    int $user_id The user ID to sync the address for
	 */
	public function wc4bp_sync_addresses_to_profile( $user_id ) {
		try {
			if ( bp_is_active( 'xprofile' ) ) {
				// get the profile fields
				$ids      = self::wc4bp_get_xprofield_fields_ids();
				$shipping = $ids['shipping'];
				$billing  = $ids['billing'];
				$groups   = BP_XProfile_Group::get( array(
					'fetch_fields' => true,
				) );
				if ( ! empty( $shipping ) && ! empty( $billing ) ) {
					if ( is_array( $shipping ) && is_array( $billing ) ) {
						unset( $shipping['group_id'] );
						unset( $billing['group_id'] );
						if ( ! empty( $groups ) ) {
							foreach ( $groups as $group ) {
								if ( self::wc4bp_is_invalid_xprofile_group( $group ) ) {
									continue;
								}
								foreach ( $group->fields as $field ) {
									$billing_key  = array_search( $field->id, $billing, true );
									$shipping_key = array_search( $field->id, $shipping, true );
									if ( $shipping_key ) {
										$type       = 'shipping';
										$field_slug = $shipping_key;
									}
									if ( $billing_key ) {
										$type       = 'billing';
										$field_slug = $billing_key;
									}
									if ( isset( $field_slug ) ) {
										$this->wc4bp_update_field( $type, $field_slug, $user_id, $field );
									}
								}
							}
						}
					}
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public static function wc4bp_is_invalid_xprofile_group( $group ) {
		/** This action is documented in /wc4bp-premium/admin/wc4bp-activate.php:68 */
		$billing_text_identification = apply_filters( 'wc4bp_billing_group_id', 'billing' );
		/** This action is documented in /wc4bp-premium/admin/wc4bp-activate.php:195 */
		$shipping_text_identification = apply_filters( 'wc4bp_shipping_group_id', 'shipping' );
		return ( empty( $group->fields ) || ( $billing_text_identification !== $group->description && $shipping_text_identification !== $group->description ) );
	}

	private function wc4bp_update_field( $type, $field_slug, $user_id, $field, $use_prefix = false ) {
		try {
			$country_slug     = ( $use_prefix ) ? $type . '_country' : 'country';
			$final_field_slug = ( ! $use_prefix ) ? $type . '_' . $field_slug : $field_slug;
			$request_var      = Request_Helper::get_post_param( $final_field_slug );
			if ( ! empty( $request_var ) ) {
				if ( $field_slug === $country_slug ) {
					$geo       = new WC_Countries();
					$countries = $geo->get_countries();
					$value     = $countries[ $request_var ];
				} else {
					$value = sanitize_text_field( $request_var );
				}
				xprofile_set_field_data( $field->id, $user_id, $value );
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	/**
	 * Check if field exist in the array of fields
	 *
	 * @param $fields
	 * @param $id
	 *
	 * @return bool
	 */
	public function exist_in_group( $fields, $id ) {
		try {
			/** @var BP_XProfile_Field $field */
			foreach ( $fields as $field ) {
				if ( is_object( $field ) ) {
					if ( $field->id === $id ) {
						return true;
					}
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}

		return false;
	}

	/**
	 * Get the field key by the name, base on the internal model
	 *
	 * @param string $group ['billing'|'shipping']
	 * @param string $name
	 * @param bool $remove_group_from_name
	 *
	 * @return bool|string
	 */
	public function get_slug_of_field( $group, $name, $remove_group_from_name = false ) {
		try {
			$model = $this->wc4bp_get_customer_meta_fields();
			if ( is_array( $model ) && isset( $model[ $group ] ) ) {
				foreach ( $model[ $group ]['fields'] as $field_key => $field_data ) {
					if ( stripos( $field_data['label'], $name ) !== false ) {
						$final_key = ( $remove_group_from_name ) ? str_replace( $group . '_', '', $field_key ) : $field_key;

						return $final_key;
					}
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}

		return false;
	}

	/**
	 * Save address from billing or shipping from my account
	 *
	 * @param $user_id
	 * @param $load_address
	 */
	public function wc4bp_sync_addresses_from_woo_my_account( $user_id, $load_address ) {
		try {
			$this->wc4bp_sync_addresses_to_profile( $user_id );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	/**
	 * Get the mapped fields (woocommerce ->  wc4bp)
	 *
	 * Note that Woocommerce has 2 types of addresses, billing and shipping
	 * Format: <code>billing{$key}</code> or <code>shipping{$key}</code>
	 *
	 * @since    1.0.5
	 */
	public function wc4bp_get_mapped_fields() {
		return array(
			'_first_name' => 'first_name',
			'_last_name'  => 'last_name',
			'_company'    => 'company',
			'_address_1'  => 'address_1',
			'_address_2'  => 'address_2',
			'_city'       => 'city',
			'_postcode'   => 'postcode',
			'_country'    => 'country',
			'_state'      => 'state',
			'_phone'      => 'phone',
			'_email'      => 'email',
		);
	}

	/**
	 * Get Address Fields for edit user pages
	 */
	public function wc4bp_get_customer_meta_fields() {
		/**
		 * Change WooCommerce customer meta fields
		 *
		 * @param array
		 */
		$show_fields = apply_filters( 'woocommerce_customer_meta_fields', array(
			'billing'  => array(
				'title'  => __( 'Customer Billing Address', 'wc4bp' ),
				'fields' => array(
					'billing_first_name' => array(
						'label'       => __( 'First Name', 'wc4bp' ),
						'description' => '',
					),
					'billing_last_name'  => array(
						'label'       => __( 'Last Name', 'wc4bp' ),
						'description' => '',
					),
					'billing_company'    => array(
						'label'       => __( 'Company', 'wc4bp' ),
						'description' => '',
					),
					'billing_address_1'  => array(
						'label'       => __( 'Address 1', 'wc4bp' ),
						'description' => '',
					),
					'billing_address_2'  => array(
						'label'       => __( 'Address 2', 'wc4bp' ),
						'description' => '',
					),
					'billing_city'       => array(
						'label'       => __( 'City', 'wc4bp' ),
						'description' => '',
					),
					'billing_postcode'   => array(
						'label'       => __( 'Postcode', 'wc4bp' ),
						'description' => '',
					),
					'billing_state'      => array(
						'label'       => __( 'State/County', 'wc4bp' ),
						'description' => __( 'State/County or state code', 'wc4bp' ),
					),
					'billing_country'    => array(
						'label'       => __( 'Country', 'wc4bp' ),
						'description' => __( '2 letter Country code', 'wc4bp' ),
					),
					'billing_phone'      => array(
						'label'       => __( 'Phone', 'wc4bp' ),
						'description' => '',
					),
					'billing_email'      => array(
						'label'       => __( 'Email Address', 'wc4bp' ),
						'description' => '',
					),
					'billing_fax'        => array(
						'label'       => __( 'Fax', 'wc4bp' ),
						'description' => '',
					),
				),
			),
			'shipping' => array(
				'title'  => __( 'Customer Shipping Address', 'wc4bp' ),
				'fields' => array(
					'shipping_first_name' => array(
						'label'       => __( 'First Name', 'wc4bp' ),
						'description' => '',
					),
					'shipping_last_name'  => array(
						'label'       => __( 'Last Name', 'wc4bp' ),
						'description' => '',
					),
					'shipping_company'    => array(
						'label'       => __( 'Company', 'wc4bp' ),
						'description' => '',
					),
					'shipping_address_1'  => array(
						'label'       => __( 'Address 1', 'wc4bp' ),
						'description' => '',
					),
					'shipping_address_2'  => array(
						'label'       => __( 'Address 2', 'wc4bp' ),
						'description' => '',
					),
					'shipping_city'       => array(
						'label'       => __( 'City', 'wc4bp' ),
						'description' => '',
					),
					'shipping_postcode'   => array(
						'label'       => __( 'Postcode', 'wc4bp' ),
						'description' => '',
					),
					'shipping_state'      => array(
						'label'       => __( 'State/County', 'wc4bp' ),
						'description' => __( 'State/County or state code', 'wc4bp' ),
					),
					'shipping_country'    => array(
						'label'       => __( 'Country', 'wc4bp' ),
						'description' => __( '2 letter Country code', 'wc4bp' ),
					),
				),
			),
		) );

		return $show_fields;
	}
}
