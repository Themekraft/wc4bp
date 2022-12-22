<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<?php
echo wp_kses_post( '<div><p>By default all related <b>WooCommerce Account Tabs</b> are included into the BuddyPress member profiles. When you <b>Turn off</b> one of the next, the Tab(s) will disappear. Also is possible to change the order for the tabs. </p></div>' );
/** @var wc4bp_admin $this */
foreach ( $tabs_array as $end_point_key => $end_point_name ) {
	$tab_select = $end_point_name['enable'];
	$position   = $end_point_name['position'];
	$user_label = ( isset( $end_point_name['user_label'] ) ) ? $end_point_name['user_label'] : $end_point_name['label'];
	$text       = sprintf( __( 'Turn off %s tab.', 'wc4bp' ), $end_point_name['label'] );
	echo '<p ' . wp_kses_post( $this->disable_class_tag( 'p' ) ) . '>' .
		 '<label><span class="dashicons dashicons-sort"></span>' .
		 '<input ' . wp_kses_post( $this->disable_input_tag( 'checkbox' ) ) . " name='" . esc_attr( $end_point_name['name'] ) . "' type='checkbox' value='1' " . checked( $tab_select, 1, false ) . ' />' .
		 "<input class='wc4bp-tabs-position' type='hidden' name='" . esc_attr( $end_point_name['name_position'] ) . "' value='" . esc_attr( $position ) . "'><b>" . esc_html( $text ) . '</b>' .
		 "</label> Or set tab's name <input " . wp_kses_post( $this->disable_input_tag( 'text' ) ) . " type='text' name='" . esc_attr( $end_point_name['name_label'] ) . "' value='" . esc_attr__( $user_label ) . "'></p>";
}
