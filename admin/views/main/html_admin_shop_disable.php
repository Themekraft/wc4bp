<p <?php echo $this->disable_class_tag( 'p' ); ?>>
	<input <?php echo $this->disable_input_tag( 'checkbox' ); ?> name='wc4bp_options[tab_cart_disabled]' type='checkbox' value='1' <?php checked( $tab_cart_disabled, 1 ); ?> />
	<b><?php _e('Turn off \'Cart\' tab', 'wc4bp'); ?></b>
</p>
<p <?php echo $this->disable_class_tag( 'p' ); ?>>
	<input <?php echo $this->disable_input_tag( 'checkbox' ); ?> name='wc4bp_options[tab_checkout_disabled]' type='checkbox' value='1' <?php checked( $tab_checkout_disabled, 1 ); ?> />
	<b><?php _e('Turn off \'Checkout\' tab.', 'wc4bp'); ?></b>
</p>
<p <?php echo $this->disable_class_tag( 'p' ); ?>>
	<input <?php echo $this->disable_input_tag( 'checkbox' ); ?> name='wc4bp_options[tab_history_disabled]' type='checkbox' value='1' <?php checked( $tab_history_disabled, 1 ); ?> />
	<b><?php _e('Turn off \'History\' tab.', 'wc4bp'); ?></b>
</p>
<p <?php echo $this->disable_class_tag( 'p' ); ?>>
	<input <?php echo $this->disable_input_tag( 'checkbox' ); ?> name='wc4bp_options[tab_track_disabled]' type='checkbox' value='1' <?php checked( $tab_track_disabled, 1 ); ?> />
	<b><?php _e('Turn off \'Track my order\' tab.', 'wc4bp'); ?></b>
</p>