<?php
_e( '<p>By default all account related WooCommerce pages are included into the BuddyPress member profiles.</p>', 'wc4bp' );
foreach ( WC4BP_MyAccount::get_available_endpoints() as $end_point_key => $end_point_name ) {
	$tab_select = 0;
	if ( isset( $wc4bp_options[ 'wc4bp_endpoint_' . $end_point_key ] ) ) {
		$tab_select = $wc4bp_options[ 'wc4bp_endpoint_' . $end_point_key ];
	}
	echo "<p " . $this->disable_class_tag( 'p' ) . "><input " . $this->disable_input_tag( 'checkbox' ) . " name='wc4bp_options[wc4bp_endpoint_" . $end_point_key . "]' type='checkbox' value='1' " . checked( $tab_select, 1, false ) . " /> <b>Turn off '" . $end_point_name . "' tab. </b></p>";
}


