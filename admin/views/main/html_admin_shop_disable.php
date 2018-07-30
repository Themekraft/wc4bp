<?php /** @var wc4bp_admin $this */
?>
<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>>
    <label>
        <span class="dashicons dashicons-sort"></span>
        <input <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options[tab_cart_disabled]' type='checkbox' value='1' <?php checked( $tab_cart_disabled, 1 ); ?> />
        <input type="hidden" class="wc4bp-tabs-position" name="wc4bp_options[position][tab_cart_disabled]" value="<?php echo "$tab_cart_disabled_position" ?>">
        <b><?php _e( 'Turn off \'Cart\' tab', 'wc4bp' ); ?></b>
    </label>
</p>
<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>>
    <label>
        <span class="dashicons dashicons-sort"></span>
        <input <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options[tab_checkout_disabled]' type='checkbox' value='1' <?php checked( $tab_checkout_disabled, 1 ); ?> />
        <input type="hidden" class="wc4bp-tabs-position" name="wc4bp_options[position][tab_checkout_disabled]" value="<?php echo "$tab_checkout_disabled_position" ?>">
        <b><?php _e( 'Turn off \'Checkout\' tab.', 'wc4bp' ); ?></b>
    </label>
</p>
<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>>
    <label>
        <span class="dashicons dashicons-sort"></span>
        <input <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options[tab_track_disabled]' type='checkbox' value='1' <?php checked( $tab_track_disabled, 1 ); ?> />
        <input type="hidden" class="wc4bp-tabs-position" name="wc4bp_options[position][tab_track_disabled]" value="<?php echo "$tab_track_disabled_position" ?>">
        <b><?php _e( 'Turn off \'Track my order\' tab.', 'wc4bp' ); ?></b>
    </label>
</p>