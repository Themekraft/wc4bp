<?php

// Make sure that we are uninstalling
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

// Removes all data from the database
delete_option( 'wc4bp_license_manager' );
delete_option( 'wc4bp_product_id');
delete_option( 'wc4bp_deactivate_checkbox' );
delete_option( 'wc4bp_activated' );
delete_option( 'wc4bp_version' );
