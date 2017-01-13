<?php
/**
 * Admin View: Template pages
 *
 */
?>

	<p>
		<input name='wc4bp_options[tab_activity_disabled]' type='checkbox' value='1' <?php checked( $tab_activity_disabled, 1 ); ?> />
		<b><?php _e('Turn off \'Shop\' ', 'wc4bp'); ?></b>
		<?php _e('Tab inside "Settings" for the activity stream settings.', 'wc4bp'); ?>
	</p>