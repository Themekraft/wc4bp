<?php

use Codeception\Util\Locator;

class checkProfileFieldSyncVisibilityCest {
	public function _before( AcceptanceTester $I, \Page\AdminPageTabProfiledSync $page ) {
		$I->loginAs( 'admin', 'admin' );
		$I->amOnAdminPage( '/' );
		$I->see( 'Dashboard' );

		//Check the profile field integration is active
		$I->expect( 'the profile field integration is active' );
		$I->amOnAdminPage( $page::$setting_url );
		$I->see( 'Turn off WooCommerce BuddyPress Profile Sync.' );
		$I->cantSeeCheckboxIsChecked( $page::$dropdown_tab_sync_disabled );
	}

	// tests
	public function checkUserProfileFieldVisibilityEveryone( AcceptanceTester $I, \Page\AdminPageTabProfiledSync $page ) {
		$I->wantTo( 'set the Profile Field Visibility for all Users' );

		//Change All user Field visibility to public
		$I->expect( 'Change All user Field visibility to Everyone(public)' );
		$I->amOnAdminPage( $page::tab( 'page-sync' ) );
		$I->see( 'Set the Profile Field Visibility for all Users' );
		$I->selectOption( $page::$wc4bp_set_bp_field_visibility_options, array( 'value' => 'public' ) );
		$I->scrollTo( 'form' );
		$I->click( $page::$wc4bp_set_bp_field_visibility_button );

		//Checking my user fields in frontend
		$I->amOnPage( '/members/admin/profile/edit/group/4/' );
		$I->see( 'First Name' );
		$I->see( 'Company' );
		$I->scrollTo( 'div.field_522' );
		$visibility = $I->grabTextFrom( '#field-visibility-settings-toggle-521 span' );
		assert( $visibility === 'Everyone' );
		$visibility = $I->grabTextFrom( '#field-visibility-settings-toggle-523 span' );
		assert( $visibility === 'Everyone' );
	}

	// tests
	public function checkProfileFieldVisibilityOnlyMe( AcceptanceTester $I, \Page\AdminPageTabProfiledSync $page ) {
		$I->wantTo( 'set the Profile Field Visibility to Only Me' );

		//Change All user Field visibility to public
		$I->expect( 'Change All user Field visibility to Only Me(adminsonly)' );
		$I->amOnAdminPage( $page::tab( 'page-sync' ) );
		$I->see( 'Set the Profile Field Visibility for all Users' );
		$I->selectOption( $page::$wc4bp_set_bp_field_visibility_options, array( 'value' => 'adminsonly' ) );
		$I->scrollTo( 'form' );
		$I->click( $page::$wc4bp_set_bp_field_visibility_button );

		//Checking my user fields in frontend
		$I->amOnPage( '/members/admin/profile/edit/group/4/' );
		$I->see( 'First Name' );
		$I->see( 'Company' );
		$I->scrollTo( 'div.field_522' );
		$visibility = $I->grabTextFrom( '#field-visibility-settings-toggle-521 span' );
		assert( $visibility === 'Only Me' );
		$visibility = $I->grabTextFrom( '#field-visibility-settings-toggle-523 span' );
		assert( $visibility === 'Only Me' );
	}

	// tests
	public function checkProfileFieldVisibilityAllMembers( AcceptanceTester $I, \Page\AdminPageTabProfiledSync $page ) {
		$I->wantTo( 'set the Profile Field Visibility to All Members' );

		//Change All user Field visibility to public
		$I->expect( 'Change All user Field visibility to All Members(loggedin)' );
		$I->amOnAdminPage( $page::tab( 'page-sync' ) );
		$I->see( 'Set the Profile Field Visibility for all Users' );
		$I->selectOption( $page::$wc4bp_set_bp_field_visibility_options, array( 'value' => 'loggedin' ) );
		$I->scrollTo( 'form' );
		$I->click( $page::$wc4bp_set_bp_field_visibility_button );

		//Checking my user fields in frontend
		$I->amOnPage( '/members/admin/profile/edit/group/4/' );
		$I->see( 'First Name' );
		$I->see( 'Company' );
		$I->scrollTo( 'div.field_522' );
		$visibility = $I->grabTextFrom( '#field-visibility-settings-toggle-521 span' );
		assert( $visibility === 'All Members' );
		$visibility = $I->grabTextFrom( '#field-visibility-settings-toggle-523 span' );
		assert( $visibility === 'All Members' );
	}
}
