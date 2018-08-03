<?php
_e( '<div><p>By default all related <b>WooCommerce Account Tabs</b> are included into the BuddyPress member profiles. When you <b>Turn off</b> one of the next, the Tab(s) will disappear. Also is possible to change the order for the tabs. </p></div>', 'wc4bp' );
/** @var wc4bp_admin $this */
foreach ( $tabs_array as $end_point_key => $end_point_name ) {
	$tab_select = $end_point_name['enable'];
	$position   = $end_point_name['position'];

	$text = sprintf( __( 'Turn off %s tab.', 'wc4bp' ), $end_point_name['label'] );
	echo "<p " . $this->disable_class_tag( 'p' ) . "><label><span class=\"dashicons dashicons-sort\"></span><input " . $this->disable_input_tag( 'checkbox' ) . " name='" . $end_point_name['name'] . "' type='checkbox' value='1' " . checked( $tab_select, 1, false ) . " /> <input class='wc4bp-tabs-position' type='hidden' name='" . $end_point_name['name_position'] . "' value='" . $position . "'><b>" . $text . "</b></label></p>";
}
