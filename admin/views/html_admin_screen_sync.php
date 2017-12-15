<div class="wrap">
	<?php include_once WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_header.php'; ?>
    <br>
    <div id="result"></div>
    <div class="wrap">
		<?php if ( ! isset( $wc4bp_options['tab_sync_disabled'] ) ) : ?>
            <form method="post" action="options.php">
                <input id="wc4bp_total_user_pages" type="hidden" value="<?php echo $total_pages ?>">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( 'wc4bp_options_sync' ); ?>
				<?php do_settings_sections( 'wc4bp_options_sync' ); ?>

            </form>
		<?php else: ?>
            <h3>
				<?php _e( "This option is not available, you need to uncheck 'Turn off the Profile Sync' from the WC4BP Settings tab.", 'wc4bp' ); ?>
            </h3>
		<?php endif; ?>
    </div>
</div>

