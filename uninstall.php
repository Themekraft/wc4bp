<?php

// Make sure that we are uninstalling
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

// Removes all data from the database
delete_option( 'wc4bp_installed' );
delete_option( 'wc4bp_shipping_address_ids' );
delete_option( 'wc4bp_billing_address_ids' );
delete_option( 'wc4bp_options' );
delete_option( 'woocommerce-buddypress-integration' );
delete_option( 'wc4bp-basic-integration' );
delete_option( 'wc4bp_api_manager_instance' );
delete_option( 'wc4bp_api_manager_deactivate_checkbox' );
delete_option( 'wc4bp_api_manager_activated' );
delete_option( 'wc4bp_api_manager_version' );
delete_option( 'wc4bp_api_manager_checkbox' );
delete_option( 'wc4bp_options_sync' );
delete_option( 'wc4bp_options_tabs' );




