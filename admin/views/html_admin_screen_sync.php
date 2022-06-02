<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<div class="wrap">
	<?php require_once WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_header.php'; ?>
	<br>
	<div id="result"></div>
	<div class="wrap">
		<?php if ( ! isset( $wc4bp_options['tab_sync_disabled'] ) ) : ?>
			<form method="post" action="options.php">
				<input id="wc4bp_total_user_pages" type="hidden" value="<?php echo esc_attr( $total_pages ); ?>">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( 'wc4bp_options_sync' ); ?>
				<?php do_settings_sections( 'wc4bp_options_sync' ); ?>

			</form>
		<?php else : ?>
			<h3>
				<?php esc_html_e( "This option is not available, you need to uncheck 'Turn off the Profile Sync' from the WooBuddy Settings tab.", 'wc4bp' ); ?>
			</h3>
		<?php endif; ?>
	</div>
</div>

