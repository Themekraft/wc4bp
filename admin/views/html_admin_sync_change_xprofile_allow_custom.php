<?php
/**

 */

?>

	<p><?php _e('Set custom visibility by user', 'wc4bp'); ?></p>
	<p>
		<select name="wc4bp_options_sync[custom_visibility]">
			<option value="allowed" <?php echo selected( $wc4bp_options_sync['custom_visibility'], 'allowed', false ); ?>>
				<?php _e('Let members change this field\'s visibility', 'wc4bp'); ?>
			</option>
			<option value="disabled" <?php echo selected( $wc4bp_options_sync['custom_visibility'], 'disabled', false ); ?>>
				<?php _e('Enforce the default visibility for all members', 'wc4bp'); ?>
			</option>
		</select>

		<input type="submit" class="button" name="wc4bp_options_sync[allow_custom_visibility]" value="Change Now">
	</p>