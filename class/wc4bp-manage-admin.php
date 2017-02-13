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

class wc4bp_Manage_Admin {
	
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'wc4bp_admin_js' ), 10 );
		if ( is_admin() && wc4bp_Manager::is_woocommerce_active()) {
			// API License Key Registration Form
			require_once( WC4BP_ABSPATH . 'admin/admin.php' );
			new wc4bp_admin();
		}
	}
	
	/**
	 * Enqueue admin JS and CSS
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.0
	 */
	public function wc4bp_admin_js() {
		
		add_thickbox();
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-effects-core' );
		
		wp_enqueue_script( 'wc4bp_admin_js', WC4BP_JS . 'admin.js', array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-widget',
			'jquery-ui-tabs'
		) );
		wp_enqueue_style( 'wc4bp_admin_css', WC4BP_CSS . 'admin.css' );
		
	}
}