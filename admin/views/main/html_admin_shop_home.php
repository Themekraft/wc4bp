<p><?php _e( 'Select the tab you want to use as your Shop Home.', 'wc4bp' ); ?></p>
<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>></p>
<select <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ); ?>  name='wc4bp_options[tab_shop_default]'>
	<?php
	if ( isset( $wc4bp_pages_options['selected_pages'] ) && is_array( $wc4bp_pages_options['selected_pages'] ) && count( $wc4bp_pages_options['selected_pages'] ) > 0 ) {
		echo '<option value="default" ' . selected( $wc4bp_options['tab_shop_default'], 'default', false ) . '>Default</option>';
		foreach ( $wc4bp_pages_options['selected_pages'] as $key => $attached_page ) {
			echo '<option value="' . $key . '" ' . selected( $wc4bp_options['tab_shop_default'], $key, false ) . '>' . $attached_page['tab_name'] . '</option>';
		}
	} else {
		echo '<option value="default">' . __( 'You need at least one Page added to Member Profiles!', 'wc4bp' ) . '</option>';
	}
	?>

</select>
