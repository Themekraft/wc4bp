<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wc4bp_admin_sync extends wc4bp_base {
	/**
	 * The Admin Page
	 *
	 * @author  Sven Lehnert
	 * @package WC4BP
	 * @since   1.3
	 */
	public function wc4bp_screen_sync( $active_tab, $wc4bp_options ) {
		try {
			$this->wc4bp_register_admin_settings_sync();
			$number      = 20;
			$count_users = count_users();
			$total_users = $count_users['total_users'];
			$total_pages = intval( $total_users / $number ) + 1;
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_screen_sync.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	/**
	 * Register the admin settings
	 *
	 * @author  Sven Lehnert
	 * @package TK Loop Designer
	 * @since   1.0
	 */
	public function wc4bp_register_admin_settings_sync() {
		try {
			if ( ! bp_is_active( 'xprofile' ) ) {
				add_settings_section( 'section_sync', __( 'Profile Field Synchronisation Settings', 'wc4bp' ), array( $this, 'need_xprofile' ), 'wc4bp_options_sync' );
			} else {
				// Settings fields and sections
				add_settings_section( 'section_sync', __( 'Profile Field Synchronisation Settings', 'wc4bp' ), '', 'wc4bp_options_sync' );
				add_settings_section( 'section_general', __( 'Default BuddyPress WooCommerce Profile Field Settings', 'wc4bp' ), '', 'wc4bp_options_sync' );
				add_settings_field( 'wc4bp_shop_profile_sync', __( '<b>Woo & BP Profile Fields Sync </b>', 'wc4bp' ), array( $this, 'wc4bp_shop_profile_sync' ), 'wc4bp_options_sync', 'section_sync' );
				add_settings_field( 'wc4bp_change_xprofile_visibility_by_user', __( '<b>Profile Field Visibility</b>', 'wc4bp' ), array( $this, 'wc4bp_change_xprofile_visibility_by_user' ), 'wc4bp_options_sync', 'section_sync' );
				add_settings_field( 'wc4bp_change_xprofile_visibility_default', __( '<b>Default Profile Fields Visibility</b>', 'wc4bp' ), array( $this, 'wc4bp_change_xprofile_visibility_default' ), 'wc4bp_options_sync', 'section_general' );
				add_settings_field( 'wc4bp_change_xprofile_allow_custom_visibility', __( '<b>Allow Custom Visibility Change by User</b>', 'wc4bp' ), array( $this, 'wc4bp_change_xprofile_allow_custom_visibility' ), 'wc4bp_options_sync', 'section_general' );
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function need_xprofile() {
		_e( '<p><strong>The options in this tab need xProfile be activated in BuddyPress.</strong></p>', 'wc4bp' );
	}

	public function wc4bp_shop_profile_sync() {
		try {
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'sync/html_admin_sync_shop_profile.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_shop_profile_sync_ajax() {
		try {
			$update_type = sanitize_text_field( $_POST['update_type'] );
			$number      = 20;
			$paged       = isset( $_POST['wc4bp_page'] ) ? intval( sanitize_text_field( $_POST['wc4bp_page'] ) ) : 1;
			$offset      = ( $paged - 1 ) * $number;
			$query       = get_users( '&offset=' . $offset . '&number=' . $number );
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'sync/html_admin_sync_shop_profile_sync_ajax.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_sync_from_admin( $user_id ) {
		try {
			if ( bp_is_active( 'xprofile' ) ) {
				// get the profile fields
				$shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
				$billing  = bp_get_option( 'wc4bp_billing_address_ids' );
				$groups   = BP_XProfile_Group::get( array(
					'fetch_fields' => true,
				) );
				if ( ! empty( $groups ) ) {
					foreach ( $groups as $group ) {
						if ( empty( $group->fields ) ) {
							continue;
						}
						foreach ( $group->fields as $field ) {
							$billing_key  = array_search( $field->id, $billing );
							$shipping_key = array_search( $field->id, $shipping );
							if ( $shipping_key ) {
								$type       = 'shipping';
								$field_slug = $shipping_key;
							}
							if ( $billing_key ) {
								$type       = 'billing';
								$field_slug = $billing_key;
							}
							if ( isset( $field_slug ) ) {
								xprofile_set_field_data( $field->id, $user_id, get_user_meta( $user_id, $type . '_' . $field_slug, true ) );
							}
						}
					}
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function select_visibility_levels( $name ) {
		try {
			$name = esc_attr( $name );
			if ( bp_is_active( 'xprofile' ) ) {
				$visibility_levels = '<select id="wc4bp_set_bp_' . $name . '" name="wc4bp_options_sync[' . $name . ']">' . '<option value="none">' . __( 'Select Visibility', 'wc4bp' ) . ' </option>';
				foreach ( bp_xprofile_get_visibility_levels() as $level ) {
					$visibility_levels .= '<option value="' . $level['id'] . '" >' . $level['label'] . '</option>';
				}
				$visibility_levels .= '</select>';
				echo $visibility_levels;
			} else {
				echo '<input type="hidden" name="wc4bp_options_sync[' . $name . ']" value="none"/>';
				echo __( '<p><strong>xProfile need to be active in BuddyPress</strong></p>', 'wc4bp' );
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_change_xprofile_visibility_by_user() {
		try {
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'sync/html_admin_sync_change_xprofile.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_change_xprofile_visibility_by_user_ajax( $user_id ) {
		try {
			if ( bp_is_active( 'xprofile' ) ) {
				// get the corresponding  wc4bp fields
				$shipping         = bp_get_option( 'wc4bp_shipping_address_ids' );
				$billing          = bp_get_option( 'wc4bp_billing_address_ids' );
				$visibility_level = sanitize_text_field( $_POST['visibility_level'] );
				foreach ( $shipping as $key => $field_id ) {
					xprofile_set_field_visibility_level( $field_id, $user_id, $visibility_level );
				}
				foreach ( $billing as $key => $field_id ) {
					xprofile_set_field_visibility_level( $field_id, $user_id, $visibility_level );
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_change_xprofile_visibility_default() {
		try {
			$wc4bp_options_sync = get_option( 'wc4bp_options_sync' );
			$billing            = bp_get_option( 'wc4bp_billing_address_ids' );
			$shipping           = bp_get_option( 'wc4bp_shipping_address_ids' );
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'sync/html_admin_sync_change_xprofile_visibility.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_change_xprofile_allow_custom_visibility() {
		try {
			$wc4bp_options_sync = get_option( 'wc4bp_options_sync' );
			$billing            = bp_get_option( 'wc4bp_billing_address_ids' );
			$shipping           = bp_get_option( 'wc4bp_shipping_address_ids' );
			include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'sync/html_admin_sync_change_xprofile_allow_custom.php' );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
}