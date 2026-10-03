jQuery( document ).ready( function ( $ ) {
	// Keep the Go Pro menu item last in the WooBuddy menu. Its color and the
	// pricing page itself come from the shared pricing-page submodule.
	var goPro = $( 'a[href="admin.php?page=wc4bp_bundle_screen"]' );
	goPro.parent().insertAfter( '#toplevel_page_wc4bp-options-page > ul > li:last-child' );
} );
