
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

	<?php

	if ( isset( $wc4bp_options_sync['allow_custom_visibility'] ) ) {
	echo '<ul>';
		foreach ( $billing as $key => $field_id ) {
		bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', $wc4bp_options_sync['custom_visibility'] );
		echo __('<li>billing_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['custom_visibility'] . '</li>', 'wc4bp' );
		}
		echo '</ul>';

	echo '<ul>';
		foreach ( $shipping as $key => $field_id ) {
		bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', $wc4bp_options_sync['visibility_levels'] );
		echo __('<li>shipping_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['custom_visibility'] . '</li>', 'wc4bp' );
		}
		echo '</ul>';

	echo __('<h3>All Done!</h3>', 'wc4bp' );
	unset( $wc4bp_options_sync['allow_custom_visibility'] );
	update_option( 'wc4bp_options_sync', $wc4bp_options_sync );
	}

	?>