<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<p>
	<?php esc_attr_e( 'Set the default visibility for all WooCommerce Fields inside BuddyPress.', 'wc4bp' ); ?>
</p>
<?php $this->select_visibility_levels( 'default_visibility' ); ?>
<input type="submit" class="button" name="wc4bp_options_sync[change_xprofile_visibility_field_default]" value="<?php esc_html_e( 'Change Now', 'wc4bp' ); ?>">
<?php
if ( bp_is_active( 'xprofile' ) ) {
	if ( isset( $wc4bp_options_sync['change_xprofile_visibility_field_default'] ) ) {
		$allowed = array(
			'ul' => array(
				'class' => array(),
				'id'    => array(),
			),
			'li' => array(
				'class' => array(),
				'id'    => array(),
			),
		);
		echo '<ul>';
		foreach ( $billing as $key => $field_id ) {
			bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
			echo wp_kses( sprintf( __( '<li>billing_%1$s default visibility changed to %2$s</li>', 'wc4bp' ), $key, $wc4bp_options_sync['default_visibility'] ), $allowed );
		}
		echo '</ul>';
		echo '<ul>';
		foreach ( $shipping as $key => $field_id ) {
			bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
			echo wp_kses( sprintf( __( '<li>shipping_%1$s default visibility changed to %2$s</li>', 'wc4bp' ), $key, $wc4bp_options_sync['default_visibility'] ), $allowed );

		}
		echo '</ul>';
		esc_html_e( '<h3>All Done!</h3>', 'wc4bp' );
		unset( $wc4bp_options_sync['change_xprofile_visibility_field_default'] );
		update_option( 'wc4bp_options_sync', $wc4bp_options_sync );
	}
}
