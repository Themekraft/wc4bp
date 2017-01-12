<?php
/**
 * Admin View: Template pages
 *
 */

?>

<div class="wrap">

	<div id="icon-options-general" class="icon32"><br></div>
	<h2>WooCommerce BuddyPress Integration Settings</h2>

	<div style="overflow: auto;">
		<span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>
	</div>
	<form method="post" action="options.php">
		<?php wp_nonce_field( 'update-options' ); ?>
		<?php settings_fields( 'wc4bp_options' ); ?>
		<?php do_settings_sections( 'wc4bp_options' ); ?>
	</form>

</div>
