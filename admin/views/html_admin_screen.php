<div class="wrap">
	
	<?php include_once WC4BP_ABSPATH_ADMIN_VIEWS_PATH .'html_admin_header.php'; ?>

	<form method="post" action="options.php">
		<?php wp_nonce_field( 'update-options' ); ?>
		<?php settings_fields( 'wc4bp_options' ); ?>
		<?php do_settings_sections( 'wc4bp_options' ); ?>
	</form>

</div>
