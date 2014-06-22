<?php


/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */

function wc4bp_screen_sync() { ?>

    <div class="wrap">

        <div id="icon-options-general" class="icon32"><br></div>
        <h2>WooCommerce BuddyPress Integration</h2>

        <div style="overflow: auto;">

            <span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>

        </div>
        <br>

        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-options' ); ?>
            <?php settings_fields( 'wc4bp_options_sync' ); ?>
            <?php do_settings_sections( 'wc4bp_options_sync' ); ?>

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

add_action( 'admin_init', 'wc4bp_register_admin_settings_sync' );

function wc4bp_register_admin_settings_sync() {

    register_setting( 'wc4bp_options_sync', 'wc4bp_options_sync' );

    // Settings fields and sections
    add_settings_section(	'section_general'	, 'Profile Field Synchronisation Settings'							, ''	, 'wc4bp_options_sync' );

    add_settings_field(		'wc4bp_shop_profile_sync'                       , '<p><b>WooCommerce -> BuddyPress Profile fields Sync </b></p>'   , 'wc4bp_shop_profile_sync'                         , 'wc4bp_options_sync' , 'section_general' );
    add_settings_field(		'wc4bp_change_xprofile_visabilyty_by_user'	    , '<p><b>Change fields visibility for all user</b></p>'     , 'wc4bp_change_xprofile_visabilyty_by_user'	    , 'wc4bp_options_sync' , 'section_general' );
    add_settings_field(		'wc4bp_change_xprofile_visabilyty_default'	    , '<h2>Default Settings</h2><p><b>Set the default Profile fields visibility</b></p>'	, 'wc4bp_change_xprofile_visabilyty_default'	    , 'wc4bp_options_sync' , 'section_general' );
    add_settings_field(		'wc4bp_change_xprofile_allow_custom_visibility'	, '<p><b>Allow custom visibility change by user</b></p>'	, 'wc4bp_change_xprofile_allow_custom_visibility'	, 'wc4bp_options_sync' , 'section_general' );



    //add_settings_field(		'tabs_disabled'	, '<p><b>Remove Shop Tabs</b></p>'	, 'wc4bp_shop_tabs_disable'	, 'wc4bp_options_sync' , 'section_general' );
    //add_settings_field(		'tabs_rename'	, '<b>Rename Shop Profile Tabs</b>'	, 'wc4bp_shop_tabs_rename'	, 'wc4bp_options' , 'section_general' );
    //add_settings_field(		'tabs_add'	, '<p><b>Add New Tabs</b></p>'	, 'wc4bp_shop_tabs_add'	, 'wc4bp_options' , 'section_general' );

}

function wc4bp_shop_profile_sync(){ ?>
    <p>Sync all WooCommerce user data with BuddyPress</p>
    <input type="submit" name="wc4bp_options_sync[wc_bp_sync]" class="button" value="Sync Now">

    <?php

    $wc4bp_options_sync = get_option( 'wc4bp_options_sync' );

    if ( isset( $wc4bp_options_sync['wc_bp_sync'] ) ){
        $all_user = wc4bp_get_all_user();
        foreach ( $all_user as $userid ) {
            $user_id       = (int) $userid->ID;
            $display_name  = stripslashes($userid->display_name);

            wc4bp_sync_from_admin($user_id);

            $return  = '';
            $return .= "\t" . '<li>'.$user_id .' - '. $display_name .'</li>' . "\n";

            print($return);
        }
        delete_option('wc4bp_options_sync');
    }

}

function  wc4bp_sync_from_admin( $user_id ) {

    // get the woocommerce fields
    // $fields = WC_Countries::get_default_address_fields();
    $fields = wc4bp_get_customer_meta_fields();

    // get the mapped fields
    $mapped_fields =  wc4bp_get_mapped_fields();

    // get the corresponding  wc4bp fields
    $shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
    $billing  = bp_get_option( 'wc4bp_billing_address_ids'  );

    foreach( $fields as $type => $fieldset ) :
        if( ! in_array( $type, array( 'billing', 'shipping' ) ) )
            continue;

        // get the kind of address to update
        $kind_of = $$type;

        foreach( $fieldset['fields'] as $key => $field ) :
            // update the field

            // get the profile field id to update
            $mapped_key = str_replace( $type, '', $key );

            // get the field id
            $field_id = $kind_of[$mapped_fields[$mapped_key]];

            // update if it isn't empty
            if( ! empty( $field_id ) )
                xprofile_set_field_data( $field_id, $user_id, get_user_meta( $user_id, $key, true ) );

        endforeach;
    endforeach;
}


function select_visibility_levels($name){

    $visibility_levels = '<select name="wc4bp_options_sync[' . $name . ']"><option value="none">Select Visibility</option>';

    foreach (bp_xprofile_get_visibility_levels() as $level) {

        $visibility_levels .= '<option value="' . $level['id'] . '" >' . $level['label'] . '</option>';

    }
    $visibility_levels .= '</select>';

    echo $visibility_levels;
}


function  wc4bp_change_xprofile_visabilyty_by_user(){ ?>



    <p>Set the Profile field visibility for all users to</p>
        <?php select_visibility_levels('visibility_levels'); ?> <input type="submit" class="button" name="wc4bp_options_sync[change_xprofile_visabilyty]" value="Sync Now">
    <?php

    $wc4bp_options_sync = get_option( 'wc4bp_options_sync' );


    if ( isset( $wc4bp_options_sync['change_xprofile_visabilyty'] ) ) {
        $all_user = wc4bp_get_all_user();

        // get the corresponding  wc4bp fields
        $shipping = bp_get_option('wc4bp_shipping_address_ids');
        $billing = bp_get_option('wc4bp_billing_address_ids');

        foreach ($all_user as $userid) {
            $user_id = (int)$userid->ID;
            $display_name = stripslashes($userid->display_name);

            foreach ($shipping as $key => $field_id) {
                xprofile_set_field_visibility_level($field_id, $user_id, $wc4bp_options_sync['visibility_levels']);
            }
            foreach ($billing as $key => $field_id) {
                xprofile_set_field_visibility_level($field_id, $user_id, $wc4bp_options_sync['visibility_levels']);
            }

            $return = '';
            $return .= "\t" . '<li>User ID: ' . $user_id . ' Display Name: ' . $display_name . ' Set to '. $wc4bp_options_sync['visibility_levels'] .'</li>' . "\n";

            print($return);
        }
        delete_option('wc4bp_options_sync');
    }

}


function wc4bp_change_xprofile_visabilyty_default(){ ?>
    <br><br><br>
    <p>Set the default profile field viability to</p>
    <?php select_visibility_levels('default_visibility'); ?>
    <input type="submit" class="button" name="wc4bp_options_sync[change_xprofile_visabilyty_field_default]" value="Change now">
    <?php
    $wc4bp_options_sync = get_option( 'wc4bp_options_sync' );

    $billing = bp_get_option('wc4bp_billing_address_ids');
    $shipping = bp_get_option('wc4bp_shipping_address_ids');

    if ( isset( $wc4bp_options_sync['change_xprofile_visabilyty_field_default'] ) ) {
        echo '<ul>';

        foreach($billing as $key => $field_id){
            bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
            echo '<li>billing_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['default_visibility'] . '</li>';
        }
        echo '</ul>';

        echo '<ul>';
        foreach($shipping as $key => $field_id){
            bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
            echo '<li>shipping_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['default_visibility'] . '</li>';
        }
        echo '</ul>';
        delete_option('wc4bp_options_sync');
    }
}

function wc4bp_change_xprofile_allow_custom_visibility(){ ?>

    <p>Set custom visibility by user</p>

    <p>
        <select name="wc4bp_options_sync[custom_visibility]">
            <option value="allowed">Let members change this field's visibility</option>
            <option value="disabled">Enforce the default visibility for all members</option>
         </select>

        <input type="submit" class="button" name="wc4bp_options_sync[allow_custom_visibility]" value="Change Now">
    </p><?php
    $wc4bp_options_sync = get_option( 'wc4bp_options_sync' );

    $billing = bp_get_option('wc4bp_billing_address_ids');
    $shipping = bp_get_option('wc4bp_shipping_address_ids');

    if ( isset( $wc4bp_options_sync['allow_custom_visibility'] ) ) {
        echo '<ul>';
        foreach($billing as $key => $field_id){
            bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', $wc4bp_options_sync['custom_visibility'] );
            echo '<li>billing_' .$key . ' default visibility changed to ' . $wc4bp_options_sync['custom_visibility'] . '</li>';
        }
        echo '</ul>';

        echo '<ul>';
        foreach($shipping as $key => $field_id){
            bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', $wc4bp_options_sync['visibility_levels'] );
            echo '<li>shipping_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['custom_visibility'] . '</li>';
        }
        echo '</ul>';
        delete_option('wc4bp_options_sync');
    }

}

function wc4bp_get_all_user(){

    global $wpdb;
    $all_user = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");

    return $all_user;
}

?>