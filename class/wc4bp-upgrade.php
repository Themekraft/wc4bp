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

/**
 * Class WC4BP_Upgrade
 *
 * This class handle the patch to apply to future version when we need to fix some issue. It use name conventions to find the version patch to apply.
 * The patch need to be created as a php class with a file name to match with the plugin version like '3.0.13.php', the class name is a combination of
 * the version with no dots and the string WC4BP for the last example the class name will be 'WC4BP_3013'.
 *
 */
class WC4BP_Upgrade {
	private $plugin_name;

	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name . DIRECTORY_SEPARATOR . 'wc4bp-basic-integration.php';//TODO this need to be testd in the free version
		add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 10, 2 );
	}

	/**
	 * This function is trigger when the plugin is updated. It check if the version already apply a patch if not it execute.
	 *
	 * @use WC4BP_Loader::VERSION The function use this constant to check for the current version of the patch
	 *
	 * @param $upgrader_object
	 * @param $options
	 *
	 * @return bool
	 */
	public function upgrader_process_complete( $upgrader_object, $options ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && defined( 'WP_ADMIN' ) && WP_ADMIN ) {
			return false;
		}
		$wc4bp_upgrade_patch = get_option( 'wc4bp_upgrade' ); //Option where the flags of path version will be store
		if ( empty( $wc4bp_upgrade_patch ) || empty( $wc4bp_upgrade_patch[ WC4BP_Loader::VERSION ] ) ) {
			if ( 'update' === $options['action'] && 'plugin' === $options['type'] && isset( $options['plugins'] ) ) {
				// Iterate through the plugins being updated and check if ours is there
				foreach ( $options['plugins'] as $plugin ) {
					if ( $plugin === $this->plugin_name ) {
						if ( empty( $wc4bp_upgrade_patch[ WC4BP_Loader::VERSION ] ) && file_exists( WC4BP_ABSPATH_PATCH_PATH . WC4BP_Loader::VERSION . '.php' ) ) {
							return $this->execute_patch( $wc4bp_upgrade_patch );
						}
					}
				}
			}
		}

		return false;
	}

	/**
	 * Execute the patch. This use a name convention to remove the dot in the version and build the class name like WC4BP_3013
	 *
	 * @param $wc4bp_upgrade_patch
	 *
	 * @return bool
	 */
	public function execute_patch( $wc4bp_upgrade_patch ) {
		$plain_version = str_replace( '.', '', WC4BP_Loader::VERSION );
		$class_name    = 'WC4BP_' . $plain_version;
		require_once WC4BP_ABSPATH_PATCH_PATH . WC4BP_Loader::VERSION . '.php';
		new $class_name( $wc4bp_upgrade_patch );

		return true;
	}
}