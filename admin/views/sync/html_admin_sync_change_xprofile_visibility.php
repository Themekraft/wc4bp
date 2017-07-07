<p>
	<?php _e( 'Set the default profile field visibility to', 'wc4bp' ); ?>
</p>
<?php $this->select_visibility_levels( 'default_visibility' ); ?>
<input type="submit" class="button" name="wc4bp_options_sync[change_xprofile_visibility_field_default]" value="<?php _e( "Change Now", "wc4bp" ); ?>">
<?php
if ( isset( $wc4bp_options_sync['change_xprofile_visibility_field_default'] ) ) {
	echo '<ul>';
	
	foreach ( $billing as $key => $field_id ) {
		bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
		echo sprintf(__( '<li>billing_%s default visibility changed to %s</li>', 'wc4bp' ),$key, $wc4bp_options_sync['default_visibility'] );
	}
	echo '</ul>';
	
	echo '<ul>';
	foreach ( $shipping as $key => $field_id ) {
		bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
		echo sprintf(__( '<li>shipping_%s default visibility changed to %s</li>', 'wc4bp' ),$key, $wc4bp_options_sync['default_visibility'] );;
	}
	echo '</ul>';
	_e( '<h3>All Done!</h3>', 'wc4bp' );
	
	unset( $wc4bp_options_sync['change_xprofile_visibility_field_default'] );
	update_option( 'wc4bp_options_sync', $wc4bp_options_sync );
}
