<?php /** @var wc4bp_admin $this */ ?>
<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$professional_plan_id ); ?>></p>
<label>
    <p><?php _e( 'This option override the default Woocommerce Thank You Page.', 'wc4bp' ); ?></p>
    <select <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$professional_plan_id ); ?> name='wc4bp_options[thank_you_page]'>
        <?php
        if ( isset( $wc4bp_pages_options['selected_pages'] ) && is_array( $wc4bp_pages_options['selected_pages'] ) && count( $wc4bp_pages_options['selected_pages'] ) > 0 ) {
            foreach ( $wc4bp_pages_options['selected_pages'] as $key => $attached_page ) {
                echo '<option value="' . $key . '" ' . selected( $wc4bp_options['thank_you_page'], $key, false ) . '>' . $attached_page['tab_name'] . '</option>';
            }
        }
        ?>
    </select>
</label>