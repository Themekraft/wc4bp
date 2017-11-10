<p <?php /** @var wc4bp_admin $this */
echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>>
    <input type="text" placeholder="wc4bp" <?php echo $this->disable_input_tag( 'text', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options[my_account_prefix]' value='<?php echo esc_attr( $my_account_prefix ); ?>'/>
    <br/><b><?php _e( 'Change \'Woocommerce My Account Page Prefix\' url.', 'wc4bp' ); ?></b>&nbsp; <?php _e( 'This preffix is used to print the page name in the url. If you leave it empty the system will use the default value. When you change the old page will be move to the trash.', 'wc4bp' ); ?>
</p>