<?php
/**
/**
 * Admin View: Template pages
 *
 */


?>
	<p><?php _e('Be careful! If you check this option, all settings will be deleted on the plugin deactivation.', 'wc4bp'); ?>
	</p>
	<br><?php _e('Yes I want to delete all Settings: ', 'wc4bp'); ?>
	<input type="checkbox" name="wc4bp_options_delete" value="delete" <?php checked( $wc4bp_options_delete, 'delete', true ) ?>>
<?php
submit_button();