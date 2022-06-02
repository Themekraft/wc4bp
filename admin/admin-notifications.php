<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, WooCommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

class wc4bp_admin_notifications extends wc4bp_base {

	/**
	 * The Admin Page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 *
	 * @param $active_tab
	 */
	public function wc4bp_screen_notifications( $active_tab ) {
		try {
			$this->wc4bp_register_admin_settings_notifications();
			include_once WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_notifications_screen.php';
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	/**
	 * Register the admin settings
	 *
	 * @author Sven Lehnert
	 * @package TK Loop Designer
	 * @since 1.0
	 */

	public function wc4bp_register_admin_settings_notifications() {
		// Settings fields and sections
		add_settings_section( 'section_notifications', __( 'WC4BP Notifications Settings', 'wc4bp' ), '', 'wc4bp_options_notifications' );
		add_settings_field( 'notifications_settings', __( '<b>Purchase Notification</b>', 'wc4bp' ), array( $this, 'wc4bp_notifications_settings' ), 'wc4bp_options_notifications', 'section_notifications' );
		add_settings_field( 'notifications_order_status', '', array( $this, 'wc4bp_notifications_order_status' ), 'wc4bp_options_notifications', 'section_notifications' );
	}

	public function wc4bp_notifications_settings() {
		try {
			$wc4bp_options_notifications = get_option( 'wc4bp_options_notifications' );
			include_once WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'notifications/html_admin_notifications_settings.php';
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function wc4bp_notifications_order_status() {
		try {
			$wc4bp_options_notifications = get_option( 'wc4bp_options_notifications' );
			include_once WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'notifications/html_admin_notifications_select_order.php';
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
}
