<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress,woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

class BPSHOP_Component extends BP_Component {

	/**
	 * Start the shop component creation process
	 *
	 * @todo	Move self::includes() out of constructor once the BP bug
	 * 			(hook priority) has been resolved to allow use of parent
	 * 			method
	 * @since 	1.0
	 */
	function __construct()
	{
		parent::start( 'shop', __( 'Woocommerce Integration', 'bpshop' ), BPSHOP_ABSPATH );
		
		$this->includes();
	}
	
	/**
	 * Include files
	 *
	 * @since 	1.0
	 */
	function includes()
	{
		$includes = array(
			'bpshop-helpers',
			'bpshop-conditionals',
			'bpshop-screen',
			'bpshop-redirect',
			'bpshop-synch'
		);
		
		foreach( $includes as $file )
			require( BPSHOP_ABSPATH .'core/'. $file .'.php' );
	}

	/**
	 * Setup globals
	 *
	 * @since 	1.0
	 * @global 	object	$bp
	 */
	function setup_globals()
	{
		global $bp;

		$globals = array(
			'path'          => BPSHOP_ABSPATH .'core',
			'slug'          => 'shop',
			'has_directory' => false
		);

		parent::setup_globals( $globals );
	}

	/**
	 * Setup BuddyBar navigation
	 *
	 * @since	1.0
	 * @global 	object	$bp
	 */
	function setup_nav()
	{
		global $bp;

		// Add 'Shop' to the main navigation
		$main_nav = array(
			'name'                		  => __( 'Shop', 'bpshop' ),
			'slug'                		  => $this->slug,
			'position'            		  => 70,
			'screen_function'     		  => 'bpshop_screen_shopping_cart',
			'default_subnav_slug' 		  => 'cart',
			'item_css_id'         		  => $this->id,
			'show_for_displayed_user' 	  => false
		);

		$shop_link = trailingslashit( $bp->loggedin_user->domain . $this->slug );

		// Add the cart nav item
		$sub_nav[] = array(
			'name'            => __( 'Shopping Cart', 'bpshop' ),
			'slug'            => 'cart',
			'parent_url'      => $shop_link,
			'parent_slug'     => $this->slug,
			'screen_function' => 'bpshop_screen_shopping_cart',
			'position'        => 10,
			'item_css_id'     => 'shop-cart',
			'user_has_access' => bp_is_my_profile()
		);

		// Add the checkout nav item
		$sub_nav[] = array(
			'name'            => __( 'History', 'bpshop' ),
			'slug'            => 'history',
			'parent_url'      => $shop_link,
			'parent_slug'     => $this->slug,
			'screen_function' => 'bpshop_screen_history',
			'position'        => 30,
			'item_css_id'     => 'shop-history',
			'user_has_access' => bp_is_my_profile()
		);

		// Add the checkout nav item
		$sub_nav[] = array(
			'name'            => __( 'Track your order', 'bpshop' ),
			'slug'            => 'track',
			'parent_url'      => $shop_link,
			'parent_slug'     => $this->slug,
			'screen_function' => 'bpshop_screen_track_order',
			'position'        => 30,
			'item_css_id'     => 'shop-track',
			'user_has_access' => bp_is_my_profile()			
		);
								
		do_action( 'bp_shop_setup_nav' );
		
		parent::setup_nav( $main_nav, $sub_nav );
	}
}

// Create the shop component
$bp->shop = new BPSHOP_Component();
?>