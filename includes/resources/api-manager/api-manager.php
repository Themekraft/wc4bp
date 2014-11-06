<?php

/**
 * The main WooCommerce API Manager class for acitvations/deactivations, and plugin updates
 * Copyright Todd Lahman LLC
 */

/**
 * Displays an inactive message if the API License Key has not yet been activated
 */
if ( get_option( 'wc4bp_api_manager_activated' ) != 'Activated' ) {
    add_action( 'admin_notices', 'WC4BP_API_Manager::wc4bp_inactive_notice' );
}

class WC4BP_API_Manager {

	/**
	 * Self Upgrade Values
	 */
	// Base URL to the remote upgrade API server
	public $upgrade_url = 'http://themekraft.com/'; // URL to access the Update API Manager.

	/**
	 * @var string
	 */
	public $version;

	/**
	 * @var string
	 * This version is saved after an upgrade to compare this db version to $version
	 */
	public $wc4bp_api_manager_version_name = 'wc4bp_api_manager_version';

	/**
	 * @var string
	 */
	public $plugin_url;

	/**
	 * @var string
	 * used to defined localization for translation, but a string literal is preferred
	 *
	 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/issues/59
	 * http://markjaquith.wordpress.com/2011/10/06/translating-wordpress-plugins-and-themes-dont-get-clever/
	 * http://ottopress.com/2012/internationalization-youre-probably-doing-it-wrong/
	 */

	/**
	 * Data defaults
	 * @var mixed
	 */
	private $wc4bp_plugin_software_product_id;

	public $wc4bp_plugin_data_key;
	public $wc4bp_plugin_api_key;
	public $wc4bp_plugin_activation_email;
	public $wc4bp_plugin_product_id_key;
	public $wc4bp_plugin_instance_key;
	public $wc4bp_plugin_deactivate_checkbox_key;
	public $wc4bp_plugin_activated_key;

	public $wc4bp_plugin_deactivate_checkbox;
	public $wc4bp_plugin_activation_tab_key;
	public $wc4bp_plugin_deactivation_tab_key;
	public $wc4bp_plugin_settings_menu_title;
	public $wc4bp_plugin_settings_title;
	public $wc4bp_plugin_menu_tab_activation_title;
	public $wc4bp_plugin_menu_tab_deactivation_title;

	public $wc4bp_plugin_options;
	public $wc4bp_plugin_plugin_name;
	public $wc4bp_plugin_product_id;
	public $wc4bp_plugin_renew_license_url;
	public $wc4bp_plugin_instance_id;
	public $wc4bp_plugin_domain;
	public $wc4bp_plugin_software_version;
	public $wc4bp_plugin_plugin_or_theme;

	public $wc4bp_plugin_update_version;

	public $wc4bp_plugin_update_check = 'wc4bp_plugin_update_check';

	/**
	 * Used to send any extra information.
	 * @var mixed array, object, string, etc.
	 */
	public $wc4bp_plugin_extra;

    /**
     * @var The single instance of the class
     */
    protected static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) )
            self::$_instance = new self();

        return self::$_instance;
    }

	public function __construct() {

		// Run the activation function
		register_activation_hook( $this->plugin_file(), array( $this, 'activation' ) );

		if ( is_admin() ) {

			/**
			 * Software Product ID is the product title string
			 * This value must be unique, and it must match the API tab for the product in WooCommerce
			 */
			$this->wc4bp_plugin_software_product_id = __('woocommerce-buddypress-integration', 'wc4bp');

			/**
			 * Set all data defaults here
			 */
			$this->version 								    = WC4BP_VERSION;
			$this->wc4bp_plugin_data_key 					= 'woocommerce-buddypress-integration';
			$this->wc4bp_plugin_api_key 					= 'api_key';
			$this->wc4bp_plugin_activation_email 			= 'activation_email';
			$this->wc4bp_plugin_product_id_key 			    = 'wc4bp-basic-integration';
			$this->wc4bp_plugin_instance_key 				= 'wc4bp_api_manager_instance';
			$this->wc4bp_plugin_deactivate_checkbox_key 	= 'wc4bp_api_manager_deactivate_checkbox';
			$this->wc4bp_plugin_activated_key 				= 'wc4bp_api_manager_activated';

			/**
			 * Set all admin menu data
			 */
			$this->wc4bp_plugin_deactivate_checkbox 			= 'wc4bp_api_manager_checkbox';
			$this->wc4bp_plugin_activation_tab_key 			    = 'wc4bp_api_manager_dashboard';
			$this->wc4bp_plugin_deactivation_tab_key 			= 'wc4bp_api_manager_deactivation';
			$this->wc4bp_plugin_settings_menu_title 			= 'WC4BP License';
			$this->wc4bp_plugin_settings_title 				    = 'WooCommerce BuddyPress Integration License Activation';
			$this->wc4bp_plugin_menu_tab_activation_title 		= __('License Activation', 'wc4bp');
			$this->wc4bp_plugin_menu_tab_deactivation_title 	= __('License Deactivation', 'wc4bp');

			/**
			 * Set all software update data here
			 */
			$this->wc4bp_plugin_options 				= get_option( $this->wc4bp_plugin_data_key );
			$this->wc4bp_plugin_plugin_name 			= $this->plugin_name(); // same as plugin slug. if a theme use a theme name like 'twentyeleven'
			$this->wc4bp_plugin_product_id 			= get_option( $this->wc4bp_plugin_product_id_key ); // Software Title
			$this->wc4bp_plugin_renew_license_url 		= 'https://themekraft.com/my-account'; // URL to renew a license
			$this->wc4bp_plugin_instance_id 			= get_option( $this->wc4bp_plugin_instance_key ); // Instance ID (unique to each blog activation)
			$this->wc4bp_plugin_domain 				= site_url(); // blog domain name
			$this->wc4bp_plugin_software_version 		= $this->version; // The software version
			$this->wc4bp_plugin_plugin_or_theme 		= 'plugin'; // 'theme' or 'plugin'

			// Performs activations and deactivations of API License Keys
			require_once( $this->plugin_path() . 'includes/resources/api-manager/am/classes/class-wc-key-api.php' );

			// Checks for software updatess
			require_once( $this->plugin_path() . 'includes/resources/api-manager/am/classes/class-wc-plugin-update.php' );

			// Admin menu with the license key and license email form
			require_once( $this->plugin_path() . 'includes/resources/api-manager/am/admin/class-wc-api-manager-menu.php' );

			$options = get_option( $this->wc4bp_plugin_data_key );

			/**
			 * Check for software updates
			 */
			if ( ! empty( $options ) && $options !== false && get_option( $this->wc4bp_plugin_activated_key ) == 'Activated' ) {

				new WC4BP_API_Manager_Update_API_Check(
					$this->upgrade_url,
					$this->wc4bp_plugin_plugin_name,
					$this->wc4bp_plugin_product_id,
					$this->wc4bp_plugin_options[$this->wc4bp_plugin_api_key],
					$this->wc4bp_plugin_options[$this->wc4bp_plugin_activation_email],
					$this->wc4bp_plugin_renew_license_url,
					$this->wc4bp_plugin_instance_id,
					$this->wc4bp_plugin_domain,
					$this->wc4bp_plugin_software_version,
					$this->wc4bp_plugin_plugin_or_theme
					);

			}

		}

		/**
		 * Deletes all data if plugin deactivated
		 */
		register_deactivation_hook( $this->plugin_file(), array( $this, 'uninstall' ) );

	}

	public function plugin_file() {
		if ( function_exists( 'wc4bp_plugin_file' ) ) {
			return wc4bp_plugin_file();
		}
	}

	public function plugin_url() {
		if ( function_exists( 'wc4bp_plugin_url' ) ) {
			return wc4bp_plugin_url();
		}
	}

	public function plugin_path() {
		if ( function_exists( 'wc4bp_plugin_path' ) ) {
			return wc4bp_plugin_path();
		}
	}

	public function plugin_name() {
		if ( function_exists( 'wc4bp_plugin_name' ) ) {
			return wc4bp_plugin_name();
		}
	}

	/**
	 * Generate the default data arrays
	 */
	public function activation() {
		global $wpdb;

		$global_options = array(
			$this->wc4bp_plugin_api_key 			=> '',
			$this->wc4bp_plugin_activation_email 	=> '',
					);

		update_option( $this->wc4bp_plugin_data_key, $global_options );

		require_once( $this->plugin_path() . 'includes/resources/api-manager/am/classes/class-wc-api-manager-passwords.php' );

		$WC4BP_API_Manager_Password_Management = new WC4BP_API_Manager_Password_Management();

		// Generate a unique installation $instance id
		$instance = $WC4BP_API_Manager_Password_Management->generate_password( 12, false );

		$single_options = array(
			$this->wc4bp_plugin_product_id_key 			=> $this->wc4bp_plugin_software_product_id,
			$this->wc4bp_plugin_instance_key 				=> $instance,
			$this->wc4bp_plugin_deactivate_checkbox_key 	=> 'on',
			$this->wc4bp_plugin_activated_key 				=> 'Deactivated',
			);

		foreach ( $single_options as $key => $value ) {
			update_option( $key, $value );
		}

		$curr_ver = get_option( $this->wc4bp_api_manager_version_name );

		// checks if the current plugin version is lower than the version being installed
		if ( version_compare( $this->version, $curr_ver, '>' ) ) {
			// update the version
			update_option( $this->wc4bp_api_manager_version_name, $this->version );
		}

	}

	/**
	 * Deletes all data if plugin deactivated
	 * @return void
	 */
	public function uninstall() {
		global $wpdb, $blog_id;

		$this->license_key_deactivation();

		// Remove options
		if ( is_multisite() ) {

			switch_to_blog( $blog_id );

			foreach ( array(
					$this->wc4bp_plugin_data_key,
					$this->wc4bp_plugin_product_id_key,
					$this->wc4bp_plugin_instance_key,
					$this->wc4bp_plugin_deactivate_checkbox_key,
					$this->wc4bp_plugin_activated_key,
					) as $option) {

					delete_option( $option );

					}

			restore_current_blog();

		} else {

			foreach ( array(
					$this->wc4bp_plugin_data_key,
					$this->wc4bp_plugin_product_id_key,
					$this->wc4bp_plugin_instance_key,
					$this->wc4bp_plugin_deactivate_checkbox_key,
					$this->wc4bp_plugin_activated_key
					) as $option) {

					delete_option( $option );

					}

		}

	}

	/**
	 * Deactivates the license on the API server
	 * @return void
	 */
	public function license_key_deactivation() {

		$wc4bp_api_manager_key = new WC4BP_API_Manager_Key();

		$activation_status = get_option( $this->wc4bp_plugin_activated_key );

		$api_email = $this->wc4bp_plugin_options[$this->wc4bp_plugin_activation_email];
		$api_key = $this->wc4bp_plugin_options[$this->wc4bp_plugin_api_key];

		$args = array(
			'email' => $api_email,
			'licence_key' => $api_key,
			);

		if ( $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
			$wc4bp_api_manager_key->deactivate( $args ); // reset license key activation
		}
	}

    /**
     * Displays an inactive notice when the software is inactive.
     */
	public static function wc4bp_inactive_notice() { ?>
		<?php if ( ! current_user_can( 'manage_options' ) ) return; ?>
		<?php if ( isset( $_GET['page'] ) && 'wc4bp_api_manager_dashboard' == $_GET['page'] ) return; ?>
		<div id="message" class="error">
			<p><?php printf( __( 'The API License Key for WC4BP could not be found. %sClick here%s to activate it.', 'wc4bp' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wc4bp_api_manager_dashboard' ) ) . '">', '</a>' ); ?></p>
		</div>
		<?php
	}

} // End of class

function WC4BPAM() {
    return WC4BP_API_Manager::instance();
}

// Initialize the class instance only once
WC4BPAM();
