<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<p><?php esc_html_e( 'Be careful! If you check this option, all settings will be deleted on the plugin deactivation.', 'wc4bp' ); ?></p>
<br><?php esc_html_e( 'Yes, I want to delete all Settings: ', 'wc4bp' ); ?>
<input type="checkbox" name="wc4bp_options_delete" value="1" <?php checked( $wc4bp_options_delete, 1, true ); ?>>
<?php
submit_button();
