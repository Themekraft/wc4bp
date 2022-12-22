<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<p><?php esc_html_e( 'Allow if the user can change the Field visibility.', 'wc4bp' ); ?></p>
<p>
	<select name="wc4bp_options_sync[custom_visibility]">
		<option value="allowed" <?php echo selected( $wc4bp_options_sync['custom_visibility'], 'allowed', false ); ?>>
			<?php esc_html_e( 'Let members change this field\'s visibility', 'wc4bp' ); ?>
		</option>
		<option value="disabled" <?php echo selected( $wc4bp_options_sync['custom_visibility'], 'disabled', false ); ?>>
			<?php esc_html_e( 'Enforce the default visibility for all members', 'wc4bp' ); ?>
		</option>
	</select>

	<input type="submit" class="button" name="wc4bp_options_sync[allow_custom_visibility]" value="<?php esc_html_e( 'Change Now', 'wc4bp' ); ?>">
</p>

<?php
if ( bp_is_active( 'xprofile' ) ) {
	if ( isset( $wc4bp_options_sync['allow_custom_visibility'] ) ) {
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
			bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', $wc4bp_options_sync['custom_visibility'] );
			echo wp_kses( sprintf( __( '<li>billing_%1$s default visibility changed to %2$s</li>', 'wc4bp' ), $key, $wc4bp_options_sync['custom_visibility'] ), $allowed );
		}
		echo '</ul>';
		echo '<ul>';
		foreach ( $shipping as $key => $field_id ) {
			bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', $wc4bp_options_sync['custom_visibility'] );
			echo wp_kses( sprintf( __( '<li>shipping_%1$s default visibility changed to %2$s</li>', 'wc4bp' ), $key, $wc4bp_options_sync['custom_visibility'] ), $allowed );
		}
		echo '</ul>';
		esc_html_e( '<h3>All Done!</h3>', 'wc4bp' );
		unset( $wc4bp_options_sync['allow_custom_visibility'] );
		update_option( 'wc4bp_options_sync', $wc4bp_options_sync );
	}
}
