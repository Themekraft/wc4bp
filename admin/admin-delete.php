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

class wc4bp_admin_delete extends wc4bp_base {
	
	/**
	 * The Admin Page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 *
	 * @param $active_tab
	 */
	public function wc4bp_screen_delete($active_tab) {
		$this->wc4bp_register_admin_settings_delete();
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_delete_screen.php' );
	}
	
	/**
	 * Register the admin settings
	 *
	 * @author Sven Lehnert
	 * @package TK Loop Designer
	 * @since 1.0
	 */

	public function wc4bp_register_admin_settings_delete() {
		// Settings fields and sections
		add_settings_section( 'section_delete',  __('Delete all WooCommerce BuddyPress Integration Settings on Plugin Deactivation', 'wc4bp' ), '', 'wc4bp_options_delete' );
		add_settings_field( 'delete_all_settings', __('<b>Delete all Settings</b>', 'wc4bp' ), array( $this, 'wc4bp_delete_all_settings' ), 'wc4bp_options_delete', 'section_delete' );
	}
	
	public function wc4bp_delete_all_settings() {
		$wc4bp_options_delete = get_option( 'wc4bp_options_delete' );
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'delete/html_admin_delete_all_settings.php' );
	}
}