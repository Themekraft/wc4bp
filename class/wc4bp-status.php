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
		$data['WC4BP'] = array(
			'version' => $GLOBALS['wc4bp_loader']->get_version(),
		);

		$wc4bp_options                                   = get_option( 'wc4bp_options' );
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
		$data['WC4BP Settings']                          = $shop_settings;

		return $data;
	}
}