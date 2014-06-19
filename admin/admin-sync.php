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
        <h2>WooCommerce BuddyPress Integration Settings</h2>

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
    add_settings_section(	'section_general'	, ''							, ''	, 'wc4bp_options_sync' );

    add_settings_field(		'profile_sync'	, '<p><b>Sync Options</b></p>'	, 'wc4bp_shop_profile_sync'	, 'wc4bp_options_sync' , 'section_general' );
    //add_settings_field(		'tabs_disabled'	, '<p><b>Remove Shop Tabs</b></p>'	, 'wc4bp_shop_tabs_disable'	, 'wc4bp_options_sync' , 'section_general' );
    //add_settings_field(		'tabs_rename'	, '<b>Rename Shop Profile Tabs</b>'	, 'wc4bp_shop_tabs_rename'	, 'wc4bp_options' , 'section_general' );
    //add_settings_field(		'tabs_add'	, '<p><b>Add New Tabs</b></p>'	, 'wc4bp_shop_tabs_add'	, 'wc4bp_options' , 'section_general' );

}

function wc4bp_shop_profile_sync(){

    // Create the WP_User_Query object
    $wp_user_query = new WP_User_Query( array( 'role' => 'administrator' ) );

    // Get the users
    $users = $wp_user_query->get_results();

    // Check for users
    if (!empty($users)) {
        // Loop through each user
        foreach ($users as $user) {

            ?>
            <ul>
                <li>
                    <?php
                    wc4bp_sync_from_admin($user->ID);
                    echo $user->ID; ?>
                </li>
            </ul>
            <?php
            // Add the meta_key and the value
            // add_user_meta($user->ID, 'name_of_new_meta_key', 'value_of_meta_key', false);

        }
    } else {
        echo 'No users found.';
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
            echo $user_id. ' $key '.$key.' meta '. get_user_meta( $user_id, $key, true ) .'<br>';
            //$all_meta_for_user = get_user_meta( $user_id );
            /*echo '<pre>';
            print_r( $all_meta_for_user );
            echo '</pre>';
            */// update if it isn't empty
            if( ! empty( $field_id ) )
                xprofile_set_field_data( $field_id, $user_id, get_user_meta( $user_id, $key, true ) );

        endforeach;
    endforeach;
}

?>