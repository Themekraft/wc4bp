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

class WC4BP_Required {

	public function __construct() {
		add_action( 'init', array( $this, 'setup_init' ), 1, 1 );
	}

	public function setup_init() {
		// Only Check for requirements in the admin
		if ( ! is_admin() ) {
			return;
		}
		add_action( 'wc4bp_tgmpa_register', array( $this, 'setup_and_check' ) );
		add_action( 'in_admin_footer', array( $this, 'remove_woo_footer' ) );
	}

	public function remove_woo_footer() {
		try {
			$current_screen = get_current_screen();
			if ( isset( $current_screen->id ) && $current_screen->id == 'admin_page_wc4bp-install-plugins' && class_exists( 'WC_Admin' ) ) {
				$this->remove_anonymous_callback_hook( 'admin_footer_text', 'WC_Admin', 'admin_footer_text' );
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	private function remove_anonymous_callback_hook( $tag, $class, $method ) {
		try {
			$filters = $GLOBALS['wp_filter'][ $tag ];

			if ( empty ( $filters ) || empty( $filters->callbacks ) ) {
				return;
			}

			foreach ( $filters->callbacks as $priority => $filter ) {
				foreach ( $filter as $identifier => $function ) {
					if ( is_array( $function ) && is_a( $function['function'][0], $class ) && $method === $function['function'][1] ) {
						remove_filter( $tag, array( $function['function'][0], $method ), $priority );
					}
				}
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function setup_and_check() {
		try {
			// Create the required required_plugins array
			$required_plugins = array(
				array(
					'name'     => 'BuddyPress',
					'slug'     => 'buddypress',
					'version'  => '2.2',
					'required' => true,
				),
				array(
					'name'     => 'WooCommerce',
					'slug'     => 'woocommerce',
					'version'  => '3.1',
					'required' => true,
				),
			);

			$config = array(
				'id'           => 'wc4bp',                 // Unique ID for hashing notices for multiple instances of TGMPA.
				'menu'         => 'wc4bp-install-plugins', // Menu slug.
				'parent_slug'  => 'admin.php',            // Parent menu slug.
				'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => false,                   // Automatically activate plugins after installation or not.
				'strings'      => array(
					'notice_can_install_required'    => _n_noop(
					/* translators: 1: plugin name(s). */
						'<u>WC4BP -> WooCommerce BuddyPress Integration</u> plugin requires the following plugin: %1$s.',
						'<u>WC4BP -> WooCommerce BuddyPress Integration</u> plugin requires the following plugins: %1$s.',
						'wc4bp'
					),
					'notice_can_install_recommended' => _n_noop(
					/* translators: 1: plugin name(s). */
						'<u>WC4BP -> WooCommerce BuddyPress Integration</u> plugin recommends the following plugin: %1$s.',
						'<u>WC4BP -> WooCommerce BuddyPress Integration</u> plugin recommends the following plugins: %1$s.',
						'wc4bp'
					),
					'notice_can_activate_required'   => _n_noop(
					/* translators: 1: plugin name(s). */
						'The following is a required plugin for <u>WC4BP -> WooCommerce BuddyPress Integration</u> and is currently inactive: %1$s.',
						'The following is a required plugins for <u>WC4BP -> WooCommerce BuddyPress Integration</u> and they are currently inactive: %1$s.',
						'wc4bp'
					),
					'notice_ask_to_update'           => _n_noop(
					/* translators: 1: plugin name(s). */
						'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this plugin: %1$s.',
						'The following plugins need to be updated to their latest version to ensure maximum compatibility with this plugin: %1$s.',
						'wc4bp'
					),
				),
			);

			// Call the tgmpa function to register the required required_plugins
			wc4bp_tgmpa( $required_plugins, $config );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

}