<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<?php /** @var wc4bp_admin $this */ ?>
<label>
	<p><?php esc_html_e( 'This option override the default tab to show when the user click in the Shop tab.', 'wc4bp' ); ?></p>
	<p <?php echo wp_kses_post( $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ) ); ?>>
		<select <?php echo wp_kses_post( $this->disable_input_tag( 'checkbox', wc4bp_base::$starter_plan_id ) ); ?> name='wc4bp_options[tab_shop_default]'>
			<?php
			if ( isset( $wc4bp_pages_options['selected_pages'] ) && is_array( $wc4bp_pages_options['selected_pages'] ) && count( $wc4bp_pages_options['selected_pages'] ) > 0 ) {
				foreach ( $wc4bp_pages_options['selected_pages'] as $key => $attached_page ) {
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( $wc4bp_options['tab_shop_default'], $key, false ) . '>' . esc_html( $attached_page['tab_name'] ) . '</option>';
				}
			} else {
				$wc4bp_options['tab_activity_disabled'] = 1;
				echo '<option value="default">' . esc_html__( 'You need at least one Page added to Member Profiles!', 'wc4bp' ) . '</option>';
			}
			?>
		</select>
	</p>
</label>
