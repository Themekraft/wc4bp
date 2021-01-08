<?php

class checkStoreUrlCest {
	// tests
	public function tryToTest( AcceptanceTester $I ) {
		$I->wantTo( 'check the Store slug' );

		//Login as admin
		$I->loginAs( 'admin', 'admin' );
		$I->amOnAdminPage( '/' );
		$I->see( 'Dashboard' );

		$I->amOnAdminPage( 'admin.php?page=wc4bp-options-page' );
		$I->see( 'WooCommerce BuddyPress Integration' );

		//Setting the default store url
		$I->see( 'WC4BP Settings' );
		$I->see( 'Change the Shop Url.   This option is useful when you want to change the Url of the Shop.' );
		$I->fillField( "input[name*='tab_my_account_shop_url'][name^='wc4bp_options']", 'shop' );
		$I->makeScreenshot( 'Update the store url to default value' );
		$I->scrollTo( 'input[type="submit"]' );
		$I->click( 'input[type="submit"]' );

		//Login as user1
		$I->seeUserInDatabase( array( 'user_login' => 'user1' ) );
		$user1 = $I->haveFriend( 'user1' );
		$user1->does( function ( AcceptanceTester $I ) {
			$I->wantTo( 'User1 check the buddypress default url' );
			$I->loginAs( 'user1', 'user1' );
			$I->amOnPage( '/members/user1' );
			$I->see( 'user1' );
			$I->expect( 'User1 to see the Shop tab' );
			$I->See( 'Shop' );
			$I->scrollTo( '.entry-content' );
			$I->click( 'li#shop-personal-li' );
			$I->see( 'Orders' );
			$I->seeInCurrentUrl( 'shop' );
		} );


		//Check the store is enabled
		$I->amOnPage( '/members/admin/' );
		$I->expect( 'to have the default shop url' );
		$I->See( 'Shop' );
		$I->scrollTo( '.entry-content' );
		$I->click( 'li#shop-personal-li' );
		$I->see( 'Orders' );
		$I->seeInCurrentUrl( 'shop' );

		//Check teh store is disabled
		$I->amOnAdminPage( 'admin.php?page=wc4bp-options-page' );
		$I->see( 'WooCommerce BuddyPress Integration' );

		$I->see( 'WC4BP Settings' );
		$I->see( 'Change the Shop Url.   This option is useful when you want to change the Url of the Shop.' );
		$I->fillField( "input[name*='tab_my_account_shop_url'][name^='wc4bp_options']", 'store111' );
		$I->makeScreenshot( 'Changing the store url' );
		$I->scrollTo( 'input[type="submit"]' );
		$I->click( 'input[type="submit"]' );

		//Check the new Store url
		$I->see( 'Change the Shop Url.   This option is useful when you want to change the Url of the Shop.' );
		$I->seeInField( "input[name*='tab_my_account_shop_url'][name^='wc4bp_options']", 'store111' );

		//Check the new store url in the frontend admin and user1
		$I->amOnPage( '/members/admin/' );
		$I->expect( 'to not see the Shop tab' );
		$I->See( 'Shop' );
		$I->scrollTo( '.entry-content' );
		$I->click( 'li#store111-personal-li' );
		$I->see( 'Orders' );
		$I->seeInCurrentUrl( 'store111' );

		$user1->does( function ( AcceptanceTester $I ) {
			$I->wantTo( 'User1 check the new store url' );
			$I->loginAs( 'user1', 'user1' );
			$I->amOnPage( '/members/user1' );
			$I->see( 'user1' );
			$I->See( 'Shop' );
			$I->scrollTo( '.entry-content' );
			$I->click( 'li#store111-personal-li' );
			$I->see( 'Orders' );
			$I->seeInCurrentUrl( 'store111' );
		} );
	}
}
