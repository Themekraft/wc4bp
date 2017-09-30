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
		$data['WC4BP']              = array(
			'version' => $GLOBALS['wc4bp_loader']->get_version(),
		);
		$wc4bp_options              = get_option( 'wc4bp_options' );
		$all_endpoints              = WC4BP_MyAccount::get_available_endpoints();
		$endpoint_my_account_status = array();
		foreach ( $all_endpoints as $endpoint_key => $endpoint_name ) {
			$endpoint_my_account_status[ $endpoint_key ]                 = $endpoint_name;
			$endpoint_my_account_status[ $endpoint_key . '_off' ]        = ( isset( $wc4bp_options[ 'wc4bp_endpoint_' . $endpoint_key ] ) ) ? 'true' : 'false';
			$post                                                        = WC4BP_MyAccount::get_page_by_name( wc4bp_Manager::get_prefix() . $endpoint_key );
			$endpoint_my_account_status[ $endpoint_key . '_page_exist' ] = ( empty( $post ) ) ? 'false' : 'true';
		}
		$data['Shop Settings']                                    = array();
		$data['My Account Tabs']                                  = $endpoint_my_account_status;
		$data['Shop Tabs']                                        = array();
		$data['Turn off the profile sync']                        = array();
		$data['Overwrite the Content of your Shop Home/Main Tab'] = array();
		
		return $data;
	}
}