<?php /** @var wc4bp_admin $this */ ?>
<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>>
    <label>
        <input <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options[tab_activity_disabled]' type='checkbox' value='1' <?php checked( $tab_activity_disabled, 1 ); ?> />
        <b><?php _e( 'Turn off Shop', 'wc4bp' ); ?></b>
    </label>
</p>
<p <?php /** @var wc4bp_admin $this */
echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>>
    <label>
        <input <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options[disable_shop_settings_tab]' type='checkbox' value='1' <?php checked( $disable_shop_settings_tab, 1 ); ?> />
        <b><?php _e( 'Turn off \'Shop Tab\' ', 'wc4bp' ); ?></b>
		<?php _e( 'inside "Settings" for the activity stream settings.', 'wc4bp' ); ?>
    </label>
</p>
<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>>
    <label>
        <input <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options[tab_my_account_disabled]' type='checkbox' value='1' <?php checked( $tab_my_account_disabled, 1 ); ?> />
        <b><?php _e( 'Turn off \'Woocommerce My Account\' redirection.', 'wc4bp' ); ?></b>&nbsp; <?php _e( 'This option is useful when you have problem with 3rd Woocommerce plugins.', 'wc4bp' ); ?>
    </label>
</p>
