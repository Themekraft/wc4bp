<?php
/**
 * Pricing page filter registration for WooBuddy (wc4bp).
 *
 * @package wc4bp
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
		$config['heading']    = __( 'Get the WooBuddy Bundle', 'wc4bp' );
		$config['subheading'] = __( 'Unlock every WooBuddy product in one bundle, with a year of updates and support.', 'wc4bp' );

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
			array(
				'label' => __( 'BuddyPress Integration for WooCommerce', 'wc4bp' ),
				'url'   => 'https://themekraft.com/wordpress-products/woocommerce-buddypress-integration/',
			),
			array(
				'label' => __( 'BuddyPress Groups Integration for WooCommerce', 'wc4bp' ),
				'url'   => 'https://themekraft.com/wordpress-products/woocommerce-buddypress-groups/',
			),
			array(
				'label' => __( 'BuddyPress xProfile Integration for WooCommerce Checkout', 'wc4bp' ),
				'url'   => 'https://themekraft.com/wordpress-products/woobuddy-checkout-manager/',
			),
			array(
				'label' => __( 'BuddyPress Integration for WooCommerce Subscriptions', 'wc4bp' ),
				'url'   => 'https://themekraft.com/wordpress-products/woocommerce-subscriptions-buddypress/',
			),
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
