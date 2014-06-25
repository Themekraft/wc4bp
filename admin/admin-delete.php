<?php


/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */

function wc4bp_screen_delete() { ?>

    <div class="wrap">

    <div id="icon-options-general" class="icon32"><br></div>
    <h2>WooCommerce BuddyPress Integration</h2>
    <div style="overflow: auto;">

        <span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>

    </div>
    <br>
    <form method="post" action="options.php">
        <?php wp_nonce_field( 'update-options' ); ?>
        <?php settings_fields( 'wc4bp_options_delete' ); ?>
        <?php do_settings_sections( 'wc4bp_options_delete' ); ?>

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

add_action( 'admin_init', 'wc4bp_register_admin_settings_delete' );

function wc4bp_register_admin_settings_delete() {

    register_setting( 'wc4bp_options_delete', 'wc4bp_options_delete' );

    // Settings fields and sections
    add_settings_section(	'section_delete'	    , 'Delete all WooCommerce BuddyPress Integration Settings on Plugin Deactivation', ''	, 'wc4bp_options_delete' );
    add_settings_field(		'delete_all_settings'	, '<b>Delete all Settings</b>' , 'wc4bp_delete_all_settings'	, 'wc4bp_options_delete' , 'section_delete' );
}

function wc4bp_delete_all_settings(){
    $wc4bp_options_delete = get_option('wc4bp_options_delete')
    ?>

    <p>Be careful! If you check this option, all settings will be deleted on the plugin deactivation.</p><br>
    Yes I want to delete all Settings: <input type="checkbox" name="wc4bp_options_delete" value="delete" <?php checked($wc4bp_options_delete, 'delete', true) ?>>

<?php
    submit_button();
}