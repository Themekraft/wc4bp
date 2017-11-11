<?php /** @var wc4bp_admin $this */ ?>
<label>
    <p <?php echo $this->disable_class_tag( 'p' ); ?>>
        <input <?php echo $this->disable_input_tag( 'checkbox' ); ?> name='wc4bp_options[tab_sync_disabled]' type='checkbox' value='1' <?php checked( $tab_sync_disabled, 1 ); ?> />
        <b><?php _e( 'Turn off WooCommerce BuddyPress Profile Sync.', 'wc4bp' ); ?></b>
    </p>
    <p>
		<?php _e( 'If you turn off profile sync, the Billing and Shipping profile groups and tabs from Profile/Edit will be deleted and disable all sync settings. When you turn of again will be created again.', 'wc4bp' ); ?>
    </p>
</label>
