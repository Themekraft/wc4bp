<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<p><input type="checkbox" name="wc4bp_options_notifications[notifications_settings]" value="1" 
<?php
if ( isset( $wc4bp_options_notifications['notifications_settings'] ) ) {
	checked( $wc4bp_options_notifications['notifications_settings'], 1, true ); }
?>
>
<b> <?php esc_html_e( 'Enable Purchase Notification. ', 'wc4bp' ); ?></b>
<?php esc_html_e( 'By default users will receive a notification when the order status changes to "Completed".', 'wc4bp' ); ?></p>

<p><input type="checkbox" name="wc4bp_options_notifications[disable_activity_feed]" value="1" 
<?php
if ( isset( $wc4bp_options_notifications['disable_activity_feed'] ) ) {
	checked( $wc4bp_options_notifications['disable_activity_feed'], 1, true ); }
?>
>
<b> <?php esc_html_e( 'Disable Activity Feed Notification.', 'wc4bp' ); ?></b>
<?php esc_html_e( 'By default when a user makes a purchase, this activity is shown in the stream. Check this option if you want to disable this feature.', 'wc4bp' ); ?></p>
