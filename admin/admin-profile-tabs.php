<?php


/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */

function wc4bp_screen_tabs() { ?>

    <div class="wrap">

    <div id="icon-options-general" class="icon32"><br></div>
    <h2>WooCommerce BuddyPress Integration Settings</h2>

    <div style="overflow: auto;">

        <span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>

    </div>
    <br>

    <form method="post" action="options.php">
        <?php wp_nonce_field( 'update-options' ); ?>
        <?php settings_fields( 'wc4bp_options_tabs' ); ?>
        <?php do_settings_sections( 'wc4bp_options_tabs' ); ?>

    </form>

    </div><?php

}

/**
 * Register the admin settings
 *
 * @author Sven Lehnert
 * @package TK Loop Designer
 * @since 1.0
 */

add_action( 'admin_init', 'wc4bp_register_admin_tabs_settings' );

function wc4bp_register_admin_tabs_settings() {

    register_setting( 'wc4bp_options_tabs', 'wc4bp_options_tabs' );

    // Settings fields and sections
    add_settings_section(	'section_general'	, ''							, ''	, 'wc4bp_options_tabs' );

    //add_settings_field(		'tabs_disabled'	, '<p><b>Remove Shop Tabs</b></p>'	, 'wc4bp_shop_tabs_disable'	, 'wc4bp_options' , 'section_general' );
    //add_settings_field(		'tabs_rename'	, '<b>Rename Shop Profile Tabs</b>'	, 'wc4bp_shop_tabs_rename'	, 'wc4bp_options' , 'section_general' );
    add_settings_field(		'tabs_add'	, '<p><b>Add New Tabs</b></p>'	, 'wc4bp_shop_tabs_add'	, 'wc4bp_options_tabs' , 'section_general' );

}
?>