<?php
/**
 * Pricing page filter registration for WooBuddy (wc4bp).
 *
 * @package BuddyPress Integration for WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wc4bp_pricing_page_config' ) ) {
	/**
	 * @param array<string,mixed> $config
	 * @return array<string,mixed>
	 */
	function wc4bp_pricing_page_config( $config ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || ! str_contains( $screen->id, 'wc4bp_bundle_screen' ) ) {
			return $config;
		}

		$config['heading']    = __( 'Go Pro to make the profile Shop your own', 'wc4bp' );
		$config['subheading'] = __( 'The free version moves the WooCommerce customer area into member profiles. Pro gives you control over every tab and lets other plugins work alongside it.', 'wc4bp' );

		$config['bundle'] = array(
			'product_id' => '8055',
			'plan_id'    => '13219',
			'public_key' => 'pk_f87ef30cf437e0452c20e2da5444b',
			'name'       => __( 'WooBuddy Bundle', 'wc4bp' ),
		);

		$bullets = array(
			array(
				'label'     => __( 'All WooBuddy products included', 'wc4bp' ),
				'highlight' => true,
			),
			__( 'Hide, rename and reorder the My Account tabs in the profile', 'wc4bp' ),
			__( 'Rename the Shop tab, change its URL and choose which tab it opens on', 'wc4bp' ),
			__( 'Add any WordPress page as a Shop sub-tab', 'wc4bp' ),
			__( 'Show pages that other plugins add to My Account (beta)', 'wc4bp' ),
			__( 'Send customers to a profile page after checkout and choose which order status triggers notifications', 'wc4bp' ),
			__( 'Turn off the Shop tab, the My Account redirect or the profile sync when another plugin needs them', 'wc4bp' ),
			__( 'Unlock WooBuddy Groups and WooBuddy Subscriptions', 'wc4bp' ),
			__( 'One year of support', 'wc4bp' ),
			__( 'One year of updates', 'wc4bp' ),
		);

		$config['tiers'] = array(
			array(
				'id'       => 'personal',
				'name'     => __( 'Personal Plan', 'wc4bp' ),
				'sites'    => __( 'One Site', 'wc4bp' ),
				'licenses' => '1',
				'price'    => '99.99',
				'bullets'  => $bullets,
			),
			array(
				'id'        => 'professional',
				'name'      => __( 'Professional Plan', 'wc4bp' ),
				'sites'     => __( 'Five Sites', 'wc4bp' ),
				'licenses'  => '5',
				'price'     => '149.99',
				'highlight' => true,
				'bullets'   => $bullets,
			),
			array(
				'id'       => 'agency',
				'name'     => __( 'Agency Plan', 'wc4bp' ),
				'sites'    => __( 'Unlimited Sites', 'wc4bp' ),
				'licenses' => 'unlimited',
				'price'    => '249.99',
				'bullets'  => $bullets,
			),
		);

		return $config;
	}
}
add_filter( 'tk_pricing_page_config', 'wc4bp_pricing_page_config' );
