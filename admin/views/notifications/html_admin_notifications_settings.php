<?php
//Leaven empty tag to let automation add the path disclosure line
?>
<p><input type="checkbox" name="wc4bp_options_notifications[notifications_settings]" value="1" <?php if( isset( $wc4bp_options_notifications['notifications_settings'] ) ) { checked( $wc4bp_options_notifications['notifications_settings'], 1, true ); } ?>>
<?php _e( '<b>Enable Purchase Notification</b>. ', 'wc4bp' ); ?>
<?php _e( 'By default users will receive a notification when the order status changes to "Completed".', 'wc4bp' ); ?></p>