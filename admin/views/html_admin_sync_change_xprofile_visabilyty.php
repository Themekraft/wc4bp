
<p>
	<?php _e('Set the default profile field viability to', 'wc4bp'); ?>
</p>
	<?php $this->select_visibility_levels( 'default_visibility' ); ?>
	<input type="submit" class="button" name="wc4bp_options_sync[change_xprofile_visabilyty_field_default]" value="Change now">

<?php
if ( isset( $wc4bp_options_sync['change_xprofile_visabilyty_field_default'] ) ) {
	echo '<ul>';

	foreach ( $billing as $key => $field_id ) {
		bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
		echo __('<li>billing_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['default_visibility'] . '</li>', 'wc4bp' );
	}
	echo '</ul>';

	echo '<ul>';
	foreach ( $shipping as $key => $field_id ) {
		bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
		echo __('<li>shipping_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['default_visibility'] . '</li>', 'wc4bp' );
	}
	echo '</ul>';
	echo  __('<h3>All Done!</h3>', 'wc4bp' );

	unset( $wc4bp_options_sync['change_xprofile_visabilyty_field_default'] );
	update_option( 'wc4bp_options_sync', $wc4bp_options_sync );
}