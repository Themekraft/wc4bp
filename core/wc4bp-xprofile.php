<?php

/**
 * Add the field to the checkout
 */
add_action( 'woocommerce_after_order_notes', 'wc4bp_custom_checkout_field' );

function wc4bp_custom_checkout_field( $checkout ) {

    $bf_xprofile_options = get_option('bf_xprofile_options');

    foreach( $bf_xprofile_options as $group_id => $fields){
        echo '<div id="wc4bp_custom_checkout_field">';

        $display_group_name = true;

        foreach($fields as $field_id => $field){

            if( isset($field['checkout']) ){
                if( $display_group_name ){
                    echo '<h2>' . $field['group_name'] . '</h2>';
                    $display_group_name = false;
                }

                $field_slug = sanitize_title($group_id.'-'.$field_id);

                $field_types = wc4bp_supported_field_types();

                $field_type = $field_types[$field['field_type']];

                if($field_type) {
                    woocommerce_form_field( $field_slug, array(
                        'required'      => $field['field_is_required'],
                        'type'          => $field_type,
                        'class'         => array('form-row-wide'),
                        'label'         => $field['field_name'],
                        'placeholder'   => '--',
                    ), $checkout->get_value( $field_slug ));
                }

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

                $field_slug = sanitize_title($group_id.'-'.$field_id);

                if( $field['field_is_required'] && ! $_POST[$field_slug] )
                    wc_add_notice( __( 'Please enter something into this new shiny field.' ), 'error' );



            }

        }

    }

}

/**
 * Update the user meta with field value
 **/
add_action('woocommerce_checkout_update_user_meta', 'my_custom_checkout_field_update_user_meta');

function my_custom_checkout_field_update_user_meta( $user_id ) {

    $bf_xprofile_options = get_option('bf_xprofile_options');

    foreach( $bf_xprofile_options as $group_id => $fields){

        foreach($fields as $field_id => $field){

            if( isset($field['checkout']) ){

                $field_slug = sanitize_title($group_id.'-'.$field_id);

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

                $field_slug = sanitize_title($group_id.'-'.$field_id);

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

                $field_slug = sanitize_title($group_id.'-'.$field_id);
                echo '<p><strong>'.$field['field_name'].':</strong> ' . get_post_meta( $order->id, $field_slug, true ) . '</p>';

            }

        }

    }

}

/**
 * Add the field to order emails
 **/
add_filter('woocommerce_email_order_meta_keys', 'my_custom_checkout_field_order_meta_keys');

function wc4bp_checkout_field_order_meta_keys( $keys ) {


    $bf_xprofile_options = get_option('bf_xprofile_options');

    foreach( $bf_xprofile_options as $group_id => $fields){

        foreach($fields as $field_id => $field){

            if( isset($field['checkout']) ){

                $field_slug = sanitize_title($group_id.'-'.$field_id);
                $keys[] = $field_slug;

            }

        }

    }

    return $keys;
}

function wc4bp_supported_field_types(){

    return array(
        'checkbox'                      => 'checkbox',
        'selectbox'                     => 'select',
        'multiselectbox'                => 'multiselect',
        'radio'                         => false,
        'datebox'                       => false,
        'textarea'                      => 'textarea',
        'number'                        => false,
        'textbox'                       => 'text',
        'birthdate'                     => false,
        'email'                         => false,
        'web'                           => false,
        'datepicker'                    => false,
        'select_custom_post_type'       => false,
        'multiselect_custom_post_type'  => false,
        'checkbox_acceptance'           => false,
        'image'                         => false,
        'file'                          => false,
        'color'                         => false,
    );

}


///////////////////////


/**
 * Outputs a rasio button form field
 */
function woocommerce_form_field_radio( $key, $args, $value = '' ) {
    global $woocommerce;
    $defaults = array(
        'type' => 'radio',
        'label' => '',
        'placeholder' => '',
        'required' => false,
        'class' => array( ),
        'label_class' => array( ),
        'return' => false,
        'options' => array( )
    );
    $args     = wp_parse_args( $args, $defaults );
    if ( ( isset( $args[ 'clear' ] ) && $args[ 'clear' ] ) )
        $after = '<div class="clear"></div>';
    else
        $after = '';
    $required = ( $args[ 'required' ] ) ? ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>' : '';
    switch ( $args[ 'type' ] ) {
        case "select":
            $options = '';
            if ( !empty( $args[ 'options' ] ) )
                foreach ( $args[ 'options' ] as $option_key => $option_text )
                    $options .= '<input type="radio" name="' . $key . '" id="' . $key . '" value="' . $option_key . '" ' . selected( $value, $option_key, false ) . 'class="select">' . $option_text . '' . "\r\n";
            $field = '<p class="form-row ' . implode( ' ', $args[ 'class' ] ) . '" id="' . $key . '_field">
<label for="' . $key . '" class="' . implode( ' ', $args[ 'label_class' ] ) . '">' . $args[ 'label' ] . $required . '</label>
' . $options . '
</p>' . $after;
            break;
    } //$args[ 'type' ]
    if ( $args[ 'return' ] )
        return $field;
    else
        echo $field;
}


/**
 * Add the field to the checkout
 **/
//add_action( 'woocommerce_after_checkout_billing_form', 'hear_about_us_field', 10 );
function hear_about_us_field( $checkout ) {
    echo '<div id="hear_about_us_field" style="background: lightgoldenrodyellow;"><h3>' . __( 'How\'d you First find us?' ) . '</h3>';
    woocommerce_form_field_radio( 'hear_about_us', array(
        'type' => 'select',
        'class' => array(
            'here-about-us form-row-wide'
        ),
        'label' => __( '' ),
        'placeholder' => __( '' ),
        'required' => false,
        'options' => array(
            'Google' => 'Google<br/>',
            'Other Search' => 'Other Search Engine<br/>',
            'Friend' => 'Friend<br/>',
            'Friend/Facebook' => 'Facebook/Friend<br/>',
            'Cristal' => 'CristalProStyler on YoutTube',
            'Other' => 'Other<br/>'


        )
    ), $checkout->get_value( 'hear_about_us' ) );
    echo '</div>';
}

?>
