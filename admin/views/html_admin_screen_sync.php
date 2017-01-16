
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2> <?php _e('WooCommerce BuddyPress Integration', 'wc4bp'); ?></h2>
	<div style="overflow: auto;">
		<span style="font-size: 13px; float:right;"><?php _e('Proudly brought to you by', 'wc4bp'); ?><a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>
	</div>
	<br>

	<input id="wc4bp_total_user_pages" type="hidden" value="<?php echo $total_pages ?>">
	<div id="result"></div>

	<div class="wrap">
		<form method="post" action="options.php">
			<?php wp_nonce_field( 'update-options' ); ?>
			<?php settings_fields( 'wc4bp_options_sync' ); ?>
			<?php do_settings_sections( 'wc4bp_options_sync' ); ?>

		</form>
	</div>
</div>

