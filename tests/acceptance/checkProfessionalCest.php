<?php

class checkProfessionalCest {

	// tests
	public function tryToTest( AcceptanceTester $I ) {
		$I->wantTo( 'check the professional version is active' );

		$I->loginAs( 'admin', 'admin' );
		$I->amOnAdminPage( '/' );
		$I->see( 'Dashboard' );

		$I->amOnAdminPage( 'admin.php?page=wc4bp-options-page' );
		$I->see( 'WooCommerce BuddyPress Integration' );
		$I->seeElementInDOM( '#adminmenu li#toplevel_page_wc4bp-options-page ul.wp-submenu li a[href="admin.php?page=wc4bp-options-page-account"]' );
	}
}
