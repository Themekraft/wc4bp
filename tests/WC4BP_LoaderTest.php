<?php


class WC4BP_LoaderTest extends WP_UnitTestCase {
	
	function test_is_load(){
		$this->assertNotEmpty($GLOBALS['wc4bp_loader']);
	}
}
