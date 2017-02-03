<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Wc4bp
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress/test/phpunit';
}

echo "TestDir".$_tests_dir."<br/>";

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	$v = dirname( dirname( __FILE__ ) );
	require $v . '/wc4bp-basic-integration.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
