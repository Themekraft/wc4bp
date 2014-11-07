<?php

/**
 * Add the field to the checkout
 */
add_action( 'woocommerce_after_order_notes', 'wc4bp_custom_checkout_field' );

function wc4bp_custom_checkout_field( $checkout ) {
    global $field;
    $bf_xprofile_options = get_option('bf_xprofile_options');


    foreach( $bf_xprofile_options as $group_id => $fields){
        echo '<div class="standard-form" id="wc4bp_custom_checkout_field">';

        $display_group_name = true;

        foreach($fields as $field_id => $field){

            if( isset($field['checkout']) ){
                if( $display_group_name ){
                    echo '<h2>' . $field['group_name'] . '</h2>';
                    $display_group_name = false;
                }

                $field = new BP_XProfile_Field( $field_id );

                echo '<div><p class="form-row">';
                $field->type_obj->edit_field_html();
                echo '</p></div>';

            }

        }
        echo '</div>';
    }

}

/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', 'wc4bp_custom_checkout_field_process');

function wc4bp_custom_checkout_field_process() {

    $bf_xprofile_options = get_option('bf_xprofile_options');

    foreach( $bf_xprofile_options as $group_id => $fields){

        foreach($fields as $field_id => $field){

            if( isset($field['checkout']) ){

                $field_slug = sanitize_title('field_'.$field_id);

                if( $field['field_is_required'] && ! $_POST[$field_slug] )
                    wc_add_notice( __( 'Please enter something into this new shiny field.' ), 'error' );

            }

        }

    }

}

/**
 * Update the user meta with field value
 **/
add_action('woocommerce_checkout_update_user_meta', 'wc4bp_custom_checkout_field_update_user_meta');

function wc4bp_custom_checkout_field_update_user_meta( $user_id ) {

    $bf_xprofile_options = get_option('bf_xprofile_options');

    foreach( $bf_xprofile_options as $group_id => $fields){

        foreach($fields as $field_id => $field){

            if( isset($field['checkout']) ){

                $field_slug = sanitize_title('field_'.$field_id);

                if ($user_id && ! empty( $_POST[$field_slug] ))
                    update_user_meta( $user_id, $field_slug, esc_attr($_POST[$field_slug]) );
                    xprofile_set_field_data($field_id, $user_id, esc_attr($_POST[$field_slug]));
            }

        }

    }

}

/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'wc4bp_custom_checkout_field_update_order_meta' );

function wc4bp_custom_checkout_field_update_order_meta( $order_id ) {

    $bf_xprofile_options = get_option('bf_xprofile_options');

    foreach( $bf_xprofile_options as $group_id => $fields){

        foreach($fields as $field_id => $field){

            if( isset($field['checkout']) ){

                $field_slug = sanitize_title('field_'.$field_id);

                if ( ! empty( $_POST[$field_slug] ) ) {
                    update_post_meta( $order_id, $field_slug, sanitize_text_field( $_POST[$field_slug] ) );
                }

            }

        }

    }
}

/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'wc4bp_custom_checkout_field_display_admin_order_meta', 10, 1 );

function wc4bp_custom_checkout_field_display_admin_order_meta($order){

    $bf_xprofile_options = get_option('bf_xprofile_options');

    foreach( $bf_xprofile_options as $group_id => $fields){

        foreach($fields as $field_id => $field){

            if( isset($field['checkout']) ){

                $field_slug = sanitize_title('field_'.$field_id);
                echo '<p><strong>'.$field['field_name'].':</strong> ' . get_post_meta( $order->id, $field_slug, true ) . '</p>';

            }

        }

    }

}

/**
 * Add the field to order emails
 **/
add_filter('woocommerce_email_order_meta_keys', 'wc4bp_custom_checkout_field_order_meta_keys');

function wc4bp_checkout_field_order_meta_keys( $keys ) {


    $bf_xprofile_options = get_option('bf_xprofile_options');

    foreach( $bf_xprofile_options as $group_id => $fields){

        foreach($fields as $field_id => $field){

            if( isset($field['checkout']) ){

                $field_slug = sanitize_title('field_'.$field_id);
                $keys[$field['field_name']] = $field_slug;

            }

        }

    }

    return $keys;
}

/* WooCommerce: Remove phone number on checkout page. */
add_filter( 'woocommerce_checkout_fields' , 'wc4bp_custom_override_checkout_fields' );

function wc4bp_custom_override_checkout_fields( $fields ) {

    $bf_xprofile_options = get_option('bf_xprofile_options');

    foreach( $bf_xprofile_options as $group_id => $bf_fields){

        foreach($bf_fields as $field_id => $field){

            if( isset($field['hide']) ){
                $group_name = explode(' ', $field['group_name']);
                $field_name = str_replace(" ", "_", $field['field_name']);

                $group_name  = sanitize_title($group_name[0]);

                $field_name = sanitize_title($group_name.'_'.$field_name);

                unset($fields[$group_name][$field_name]);
            }

        }

    }

    return $fields;
}
?>