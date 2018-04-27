<?php /** @var wc4bp_admin $this */ ?>
<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>>
    <label>
        <input <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options[tab_activity_disabled]' type='checkbox' value='1' <?php checked( $tab_activity_disabled, 1 ); ?> />
        <b><?php _e( 'Turn off Shop.', 'wc4bp' ); ?></b>
		<?php _e( 'Disable the BuddyPress Shop Tab and WooCommerce My Account will work normally.', 'wc4bp' ); ?>
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
        <b><?php _e( 'Turn off \'WooCommerce My Account\' redirection.', 'wc4bp' ); ?></b>&nbsp; <?php _e( 'This option is useful when you have problems with 3rd WooCommerce plugins. It disables only the My Account redirection, not the sub tabs URL.', 'wc4bp' ); ?>
    </label>
</p>
