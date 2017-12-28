<?php /** @var wc4bp_admin $this */ ?>
<p <?php echo $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ); ?>></p>
<label>
    <p><?php _e( 'This option override the default tab to show when the user click in the Shop tab.', 'wc4bp' ); ?></p>
    <select <?php echo $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ); ?> name='wc4bp_options[tab_shop_default]'>
		<?php
		if ( isset( $wc4bp_pages_options['selected_pages'] ) && is_array( $wc4bp_pages_options['selected_pages'] ) && count( $wc4bp_pages_options['selected_pages'] ) > 0 ) {
			$wc4bp_pages_options['selected_pages'] = array_merge( array( 'default' => array( 'tab_name' => __( 'Default', 'wc4bp' ) ) ), $wc4bp_pages_options['selected_pages'] );
			foreach ( $wc4bp_pages_options['selected_pages'] as $key => $attached_page ) {
				echo '<option value="' . $key . '" ' . selected( $wc4bp_options['tab_shop_default'], $key, false ) . '>' . $attached_page['tab_name'] . '</option>';
			}
		} else {
			$wc4bp_options["tab_activity_disabled"] = 1;
			echo '<option value="default">' . _e( 'You need at least one Page added to Member Profiles!', 'wc4bp' ) . '</option>';
		}
		?>

    </select>
</label>
