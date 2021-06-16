<?php
//Leaven empty tag to let automation add the path disclosure line
?>
<?php /** @var wc4bp_admin $this */ ?>
<label>
	<p><?php _e( 'Select in which order status the notification will be sent.', 'wc4bp' ); ?></p>
	<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>>
		<select <?php echo $this->disable_input_tag( 'select', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options_notifications[notifications_order_status]'>
		<option value='procesing' <?php if( isset( $wc4bp_options_notifications['notifications_order_status'] ) ){ selected( $wc4bp_options_notifications['notifications_order_status'], 'procesing' ); } ?>>Processing</option>
        <option value='pending-payment' <?php if( isset( $wc4bp_options_notifications['notifications_order_status'] ) ){ selected( $wc4bp_options_notifications['notifications_order_status'], 'pending-payment' ); } ?>>Pending Payment</option>
        <option value='completed' <?php if( isset( $wc4bp_options_notifications['notifications_order_status'] ) ){ selected( $wc4bp_options_notifications['notifications_order_status'], 'completed' ); } ?>>Completed</option>
        <option value='on-hold' <?php if( isset( $wc4bp_options_notifications['notifications_order_status'] ) ){ selected( $wc4bp_options_notifications['notifications_order_status'], 'on-hold' ); } ?>>On Hold</option>

		</select>
	</p>
</label>
<?php submit_button();
