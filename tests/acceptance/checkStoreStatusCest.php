<?php

class checkStoreStatusCest {
	// tests
	public function tryToTest( AcceptanceTester $I ) {
		$I->wantTo( 'check the Store when is enabled or disabled' );

		//Login as user1
		$I->seeUserInDatabase( array( 'user_login' => 'user1' ) );
		$user1 = $I->haveFriend( 'user1' );
		$user1->does( function ( AcceptanceTester $I ) {
			$I->wantTo( 'User1 check the buddypress his profile' );
			$I->loginAs( 'user1', 'user1' );
			$I->amOnPage( '/members/user1' );
			$I->see( 'user1' );
			$I->expect('User1 to see the Shop tab');
			$I->See( 'Shop' );
		} );

		//Login as admin
		$I->loginAs( 'admin', 'admin' );
		$I->amOnAdminPage( '/' );
		$I->see( 'Dashboard' );

		//Check the store is enabled
		$I->amOnPage( '/members/admin/' );
		$I->expect('to see the Shop tab');
		$I->See( 'Shop' );

		//Disable the store
		$I->amOnAdminPage( 'admin.php?page=wc4bp-options-page' );
		$I->see( 'WooCommerce BuddyPress Integration' );

		$I->see( 'WC4BP Settings' );
		$I->see( 'Turn off Shop. Disable the BuddyPress Shop Tab and WooCommerce My Account will work normally.' );
		$I->checkOption( "input[name*='tab_activity_disabled'][name^='wc4bp_options']" );
		$I->seeCheckboxIsChecked( "input[name*='tab_activity_disabled'][name^='wc4bp_options']" );
		$I->makeScreenshot( 'Disabling the store' );

		$I->amOnPage( '/members/admin/' );
		$I->expect('to not see the Shop tab');
		$I->canSee( 'Shop' );

		$user1->does( function ( AcceptanceTester $I ) {
			$I->wantTo( 'User1 check the buddypress his profile' );
			$I->loginAs( 'user1', 'user1' );
			$I->amOnPage( '/members/user1' );
			$I->see( 'user1' );
			$I->expect('User1 to not see the Shop tab');
			$I->canSee( 'Shop' );
		} );
	}
}
