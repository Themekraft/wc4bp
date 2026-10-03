<?php
/**
 * Backward compatibility for WooBuddy add-ons.
 *
 * Until 3.5.x the Freemius SDK lived in this folder, and the add-ons (xProfile
 * Checkout Manager, Subscriptions, Groups) still load it from here. Since 3.6.0
 * the SDK is a Composer dependency in vendor/, so this file forwards to it.
 *
 * @package WooBuddy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__, 3 ) . '/vendor/freemius/wordpress-sdk/start.php';
