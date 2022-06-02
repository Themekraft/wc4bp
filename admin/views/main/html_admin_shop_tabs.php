<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<?php /** @var wc4bp_admin $this */ ?>
<p <?php echo wp_kses_post( $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ) ); ?>>
	<label>
		<input <?php echo wp_kses_post( $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ) ); ?> name='wc4bp_options[tab_activity_disabled]' type='checkbox' value='1' <?php checked( $tab_activity_disabled, 1 ); ?> />
		<b><?php esc_html_e( 'Turn off Shop.', 'wc4bp' ); ?></b>
		<?php esc_html_e( 'Disable the BuddyPress Shop Tab and WooCommerce My Account will work normally.', 'wc4bp' ); ?>
	</label>
</p>
<p 
<?php
/** @var wc4bp_admin $this */
echo wp_kses_post( $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ) );
?>
>
	<label>
		<input <?php echo wp_kses_post( $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ) ); ?> name='wc4bp_options[disable_shop_settings_tab]' type='checkbox' value='1' <?php checked( $disable_shop_settings_tab, 1 ); ?> />
		<b><?php esc_html_e( 'Turn off \'Shop Tab\' ', 'wc4bp' ); ?></b>
		<?php esc_html_e( 'inside "Settings" for the activity stream settings.', 'wc4bp' ); ?>
	</label>
</p>
<p <?php echo wp_kses_post( $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ) ); ?>>
	<label>
		<input <?php echo wp_kses_post( $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ) ); ?> name='wc4bp_options[tab_my_account_disabled]' type='checkbox' value='1' <?php checked( $tab_my_account_disabled, 1 ); ?> />
		<b><?php esc_html_e( 'Turn off \'WooCommerce My Account\' redirection.', 'wc4bp' ); ?></b>&nbsp; <?php esc_html_e( 'This option is useful when you have problems with 3rd WooCommerce plugins. It disables only the My Account redirection, not the sub tabs URL.', 'wc4bp' ); ?>
	</label>
</p>
<p <?php echo wp_kses_post( $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ) ); ?>>
	<label>
		<input <?php echo wp_kses_post( $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ) ); ?> name='wc4bp_options[tab_my_account_extra_content]' type='checkbox' value='1' <?php checked( $tab_my_account_enable_extra_content, 1 ); ?> />
		<b><?php esc_html_e( 'Turn on \'Extra Content Tab\' (BETA).', 'wc4bp' ); ?></b>&nbsp; <?php esc_html_e( 'Check this option if you have 3rd WooCommerce plugins and some of them add tabs within My Account. It also disables the My Account redirection.', 'wc4bp' ); ?>
	</label>
</p>
<p <?php echo wp_kses_post( $this->disable_class_tag( 'p' ) ); ?>>
	<label>
		<b><?php esc_html_e( 'Change the  Shop label. ', 'wc4bp' ); ?></b>&nbsp; <?php esc_html_e( 'This option is useful when you want to change the Label of the Shop.', 'wc4bp' ); ?>
		<br/>
		<input <?php echo wp_kses_post( $this->disable_input_tag( 'text' ) ); ?> name='wc4bp_options[tab_my_account_shop_label]' type='text' value='<?php echo esc_attr( $tab_my_account_shop_label ); ?>'  />

	</label>
</p>

<p <?php echo wp_kses_post( $this->disable_class_tag( 'p' ) ); ?>>
	<label>
		<b><?php esc_html_e( 'Change the  Shop Url. ', 'wc4bp' ); ?></b>&nbsp; <?php esc_html_e( 'This option is useful when you want to change the Url of the Shop.', 'wc4bp' ); ?>
		<br/>
		<input <?php echo wp_kses_post( $this->disable_input_tag( 'text' ) ); ?> name='wc4bp_options[tab_my_account_shop_url]' type='text' value='<?php echo esc_attr( $tab_my_account_shop_url ); ?>'  />

	</label>
</p>
