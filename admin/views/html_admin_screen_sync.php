<div class="wrap">
	<?php include_once WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_header.php'; ?>
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

