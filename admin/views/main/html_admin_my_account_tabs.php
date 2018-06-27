<?php
/** @var wc4bp_admin $this */
_e( '<p>By default all account related <b>WooCommerce My Account</b> Tabs are included into the BuddyPress member profiles. When you <b>Turn off</b> one of the next, the Tab(s) will disappear.</p>', 'wc4bp' );
foreach ( WC4BP_MyAccount::get_available_endpoints() as $end_point_key => $end_point_name ) {
	$tab_select = 0;
	if ( isset( $this->wc4bp_options[ 'wc4bp_endpoint_' . $end_point_key ] ) ) {
		$tab_select = $this->wc4bp_options[ 'wc4bp_endpoint_' . $end_point_key ];
	}
	
	$text = sprintf( __('Turn off %s tab.', 'wc4bp'), $end_point_name );
	echo "<p " . $this->disable_class_tag( 'p' ) . "><label><input " . $this->disable_input_tag( 'checkbox' ) . " name='wc4bp_options[wc4bp_endpoint_" . $end_point_key . "]' type='checkbox' value='1' " . checked( $tab_select, 1, false ) . " /> <b>" . $text . "</b></label></p>";
}
