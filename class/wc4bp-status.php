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

class WC4BP_Status {
	private $status_handler;

	public function __construct() {
		require_once WC4BP_ABSPATH_CLASS_PATH . 'includes/class-wp-plugin-status.php';
		$this->status_handler = WpPluginStatusFactory::build_manager( array(
			'slug' => 'wc4bp-options-page',
		) );
		add_action( 'init', array( $this, 'set_status_options' ), 1, 1 );
		add_filter( 'wp_plugin_status_data', array( $this, 'status_data' ) );
	}

	public function set_status_options() {
		// Only Check for requirements in the admin
		if ( ! is_admin() ) {
			return;
		}
	}

	public function status_data( $data ) {
		$versions = array(
			'WC4BP' => $GLOBALS['wc4bp_loader']->get_version(),
		);
		if ( defined( 'BP_PLUGIN_DIR' ) ) {
			$bp_loader = constant( 'BP_PLUGIN_DIR' ) . DIRECTORY_SEPARATOR . 'bp-loader.php';
			if ( file_exists( $bp_loader ) ) {
				$buddypress = get_plugin_data( constant( 'BP_PLUGIN_DIR' ) . DIRECTORY_SEPARATOR . 'bp-loader.php' );
				if ( ! empty( $buddypress ) ) {
					$versions['BuddyPress'] = ( ! empty( $buddypress['Version'] ) ) ? $buddypress['Version'] : '-';
				}
			}
		}
		if ( defined( 'WC_PLUGIN_FILE' ) ) {
			if ( file_exists( WC_PLUGIN_FILE ) ) {
				$woocommerce_data = get_plugin_data( WC_PLUGIN_FILE );
				if ( ! empty( $woocommerce_data ) ) {
					$versions['Woocommerce'] = ( ! empty( $woocommerce_data['Version'] ) ) ? $woocommerce_data['Version'] : '-';
				}
			}
		}
		$data['Versions']                                = $versions;
		$wc4bp_options                                   = get_option( 'wc4bp_options' );
		$shop_settings['shop_slug']                      = wc4bp_Manager::get_shop_slug();
		$shop_settings['is_shop_off']                    = empty( $wc4bp_options['tab_activity_disabled'] ) ? 'false' : 'true';
		$shop_settings['is_shop_inside_setting_off']     = empty( $wc4bp_options['disable_shop_settings_tab'] ) ? 'false' : 'true';
		$shop_settings['is_woo_my_account_redirect_off'] = empty( $wc4bp_options['tab_my_account_disabled'] ) ? 'false' : 'true';
		$shop_settings['woo_page_prefix']                = ( isset( $wc4bp_options['my_account_prefix'] ) ) ? $wc4bp_options['my_account_prefix'] : 'default';
		$shop_settings['is_cart_off']                    = empty( $wc4bp_options['tab_cart_disabled'] ) ? 'false' : 'true';
		$shop_settings['is_checkout_off']                = empty( $wc4bp_options['tab_checkout_disabled'] ) ? 'false' : 'true';
		$shop_settings['is_history_off']                 = empty( $wc4bp_options['tab_history_disabled'] ) ? 'false' : 'true';
		$shop_settings['is_track_off']                   = empty( $wc4bp_options['tab_track_disabled'] ) ? 'false' : 'true';
		$shop_settings['is_woo_sync_off']                = empty( $wc4bp_options['tab_sync_disabled'] ) ? 'false' : 'true';
		$shop_settings['tab_shop_default']               = ( isset( $wc4bp_options['tab_shop_default'] ) ) ? $wc4bp_options['tab_shop_default'] : 'default';
		$user_id                                         = get_current_user_id();
		$wc4bp_review                                    = false;
		$wc4bp_review_later                              = false;
		if ( $user_id > 0 ) {
			$wc4bp_review       = get_user_meta( $user_id, 'wc4bp-review', true );
			$wc4bp_review_later = get_user_meta( $user_id, 'wc4bp-review-later', true );
			if ( ! empty( $wc4bp_review_later ) && $wc4bp_review_later instanceof DateTime ) {
				$wc4bp_review_later = $wc4bp_review_later->format( 'Y-m-d H:i:s' );
			}
		}
		$shop_settings['review']       = ( ! empty( $wc4bp_review ) ) ? $wc4bp_review : 'false';
		$shop_settings['review-later'] = ( ! empty( $wc4bp_review_later ) ) ? $wc4bp_review_later : 'false';
		$data['WC4BP Settings']        = $shop_settings;

		$shipping          = bp_get_option( 'wc4bp_shipping_address_ids' );
		$billing           = bp_get_option( 'wc4bp_billing_address_ids' );
		$exist_group_in_bp = array();
		$exist_field_in_bp = array();
		$no_internal_group = array();
		$no_internal_field = array();
		//Get BP XProfield groups
		$groups = BP_XProfile_Group::get( array(
			'fetch_fields' => true,
		) );
		/** @var BP_XProfile_Group $group */
		foreach ( $groups as $group ) {
			$group_id = ( empty( $group->description ) ) ? $group->id : $group->description;
			if ( wc4bp_Sync::wc4bp_is_invalid_xprofile_group( $group ) ) {
				$no_internal_group[ $group_id ] = $group;
				/** @var BP_XProfile_Field $field */
				foreach ( $group->fields as $field ) {
					$no_internal_field[ $group_id ][ $field->id ] = $field;
				}
			} else {
				$exist_group_in_bp[ $group_id ] = $group;
				/** @var BP_XProfile_Field $field */
				foreach ( $group->fields as $field ) {
					$billing_key  = array_search( $field->id, $billing, true );
					$shipping_key = array_search( $field->id, $shipping, true );
					if ( $shipping_key || $billing_key ) {
						$exist_field_in_bp[ $group_id ][ $field->id ] = $field;
					}
				}
			}
		}

		$xprofiels_settings['shipping_array'] = is_array( $shipping ) ? 'true' : 'false';
		$xprofiels_settings['billing_array']  = is_array( $billing ) ? 'true' : 'false';
		/**
		 * @var string            $key
		 * @var BP_XProfile_Group $item
		 */
		foreach ( $exist_group_in_bp as $key => $item ) {
			$xprofiels_settings[ $key ] = $item->name;
			if ( is_array( $exist_field_in_bp[ $key ] ) ) {
				/**
				 * @var integer           $field_id
				 * @var BP_XProfile_Field $field_data
				 */
				foreach ( $exist_field_in_bp[ $key ] as $field_id => $field_data ) {
					$xprofiels_settings[ $key . '_' . $field_data->name ] = $field_data->id . ' (required:' . $field_data->is_required . ')';
				}
			}
		}
		/**
		 * @var string            $key
		 * @var BP_XProfile_Group $item
		 */
		foreach ( $no_internal_group as $key => $item ) {
			$xprofiels_settings[ $key ] = $item->name;
			if ( is_array( $no_internal_field[ $key ] ) ) {
				/**
				 * @var integer           $field_id
				 * @var BP_XProfile_Field $field_data
				 */
				foreach ( $no_internal_field[ $key ] as $field_id => $field_data ) {
					$xprofiels_settings[ $key . '_' . $field_data->name ] = $field_data->id . ' (required:' . $field_data->is_required . ')';
				}
			}
		}
		$data['WC4BP XProfield Details'] = $xprofiels_settings;

		return $data;
	}
}
