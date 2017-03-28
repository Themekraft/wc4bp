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

class WC4BP_Required {
	
	public function __construct() {
		add_action( 'init', array( $this, 'setup_init' ), 1, 1 );
	}
	
	public function setup_init() {
		// Only Check for requirements in the admin
		if ( ! is_admin() ) {
			return;
		}
		add_action( 'tgmpa_register', array( $this, 'setup_and_check' ) );
		add_action( 'in_admin_footer', array( $this, 'remove_woo_footer' ) );
	}
	
	public function remove_woo_footer() {
		$current_screen = get_current_screen();
		if ( isset( $current_screen->id ) && $current_screen->id == 'admin_page_wc4bp-install-plugins' && class_exists( 'WC_Admin' ) ) {
			$this->remove_anonymous_callback_hook( 'admin_footer_text', 'WC_Admin', 'admin_footer_text' );
		}
	}
	
	private function remove_anonymous_callback_hook( $tag, $class, $method ) {
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
	}
	
	public function setup_and_check() {
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
				'version'  => '2.4',
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
		);
		
		// Call the tgmpa function to register the required required_plugins
		tgmpa( $required_plugins, $config );
	}
	
}