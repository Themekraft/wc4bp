<?php
/**
 * @package       	WordPress
 * @subpackage    	BuddyPress, Woocommerce
 * @author        	Boris Glumpler
 * @copyright    	2011, Themekraft
 * @link        	https://github.com/Themekraft/BP-Shop-Integration
 * @license        	http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

class WC4BP_Component extends BP_Component
{
    /**
     * Holds the ID of the component
	 *
	 * @var		string
     * @since   1.0
     */
	public $id = 'shop';

    /**
     * Start the shop component creation process
     *
     * @since     1.0
     */
    function __construct() {
        parent::start( $this->id, __( 'Shop', 'wc4bp' ), WC4BP_ABSPATH );

        $this->includes();

        add_action( 'bp_register_activity_actions',	array( &$this, 'register_activity_actions' ) );

				add_filter( 'bp_located_template', 			array( &$this, 'wc4bp_members_load_template_filter'), 10, 2);

    }

    /**
     * Register acctivity actions
     *
     * @since     1.0.4
     */
    function register_activity_actions() {
        if( ! bp_is_active( 'activity' ) )
            return false;

        bp_activity_set_action( $this->id, 'new_shop_review',   __( 'New review created', 'wc4bp' ) );
        bp_activity_set_action( $this->id, 'new_shop_purchase', __( 'New purchase made',   'wc4bp' ) );

        do_action( 'wc4bp_register_activity_actions' );
    }

    /**
     * Include files
     *
     * @since     1.0
     */
    function includes($includes = Array()) {

		$wc4bp_options			= get_option( 'wc4bp_options' );

        $includes = array(
            'wc4bp-helpers',
            'wc4bp-conditionals',
            'wc4bp-screen',
            'wc4bp-redirect',
            'wc4bp-deprecated',
        );

        foreach( $includes as $file )
            require( WC4BP_ABSPATH .'core/'. $file .'.php' );

		if ( ! class_exists( 'BP_Theme_Compat' ) )
    		require(  WC4BP_ABSPATH .'core/wc4bp-template-compatibility.php'  );

		if(!isset($wc4bp_options['tab_sync_disabled']) || class_exists('WC4BP_xProfile')){
    		require(  WC4BP_ABSPATH .'core/wc4bp-sync.php'  );
		}

	}

    /**
     * Setup globals
     *
     * @since     1.0
     * @global    object    $bp
     */
    function setup_globals($globals = Array()) {
        global $bp;

            $globals = array(
            'path'          => WC4BP_ABSPATH .'core',
            'slug'          => 'shop',
            'has_directory' => false
        );

        parent::setup_globals( $globals );
    }

    /**
     * Setup BuddyBar navigation
     *
     * @since    1.0
     * @global   object    $bp
     */
    function setup_nav($main_nav = Array(), $sub_nav = Array()) {

		$wc4bp_options			= get_option( 'wc4bp_options' );
		$wc4bp_pages_options	= get_option( 'wc4bp_pages_options' );

		if($wc4bp_options['tab_shop_default'] == 'default' ){
            $default_screen = 'wc4bp_screen_shopping_cart';
		} else {
            $default_screen = 'wc4bp_screen_plugins';
		}

    // Add 'Shop' to the main navigation
    $main_nav = array(
        'name'                    		=> apply_filters( 'bp_shop_link_label', __( 'Shop', 'wc4bp' ) ),
        'slug'                          => $this->slug,
        'position'                      => 70,
        'screen_function'               => $default_screen,
        'default_subnav_slug'           => 'home',
        'item_css_id'                   => $this->id,
        'show_for_displayed_user'       => false
    );

    $shop_link = trailingslashit( bp_loggedin_user_domain() . $this->slug );

		// Add the cart nav item
		if( ! isset( $wc4bp_options['tab_cart_disabled'])) {
	        $sub_nav[] = array(
	            'name'            => apply_filters( 'bp_shop_cart_link_label', __( 'Shopping Cart', 'wc4bp' ) ),
	            'slug'            => 'home',
	            'parent_url'      => $shop_link,
	            'parent_slug'     => $this->slug,
	            'screen_function' => 'wc4bp_screen_shopping_cart',
	            'position'        => 10,
	            'item_css_id'     => 'shop-cart',
	            'user_has_access' => bp_is_my_profile()
	        );
		}

		// Add the checkout nav item, if cart empty do not add.
		if( ! is_admin() && is_object(WC()->cart) && ! WC()->cart->is_empty() && ! isset( $wc4bp_options['tab_checkout_disabled'] ) ) {
		    $sub_nav[] = array(
		        'name'            => apply_filters( 'bp_checkout_link_label', __( 'Checkout', 'wc4bp' ) ),
		        'slug'            => 'checkout',
		        'parent_url'      => $shop_link,
		        'parent_slug'     => $this->slug,
		        'screen_function' => 'wc4bp_screen_shopping_checkout',
		        'position'        => 10,
		        'item_css_id'     => 'shop-checkout',
		        'user_has_access' => bp_is_my_profile()
		    );
		}

		// Add the checkout nav item
		if( ! isset( $wc4bp_options['tab_history_disabled'])) {

	        $sub_nav[] = array(
	            'name'            => apply_filters( 'bp_history_link_label', __( 'History', 'wc4bp' ) ),
	            'slug'            => 'history',
	            'parent_url'      => $shop_link,
	            'parent_slug'     => $this->slug,
	            'screen_function' => 'wc4bp_screen_history',
	            'position'        => 30,
	            'item_css_id'     => 'shop-history',
	            'user_has_access' => bp_is_my_profile()
	        );
		}
        // Add the checkout nav item
        if( ! isset( $wc4bp_options['tab_track_disabled'])) {
	        $sub_nav[] = array(
	            'name'            => apply_filters( 'bp_track_order_link_label', __( 'Track your order', 'wc4bp' ) ),
	            'slug'            => 'track',
	            'parent_url'      => $shop_link,
	            'parent_slug'     => $this->slug,
	            'screen_function' => 'wc4bp_screen_track_order',
	            'position'        => 30,
	            'item_css_id'     => 'shop-track',
	            'user_has_access' => bp_is_my_profile()
	        );
		}

    // Add shop settings subpage
    if( ! isset( $wc4bp_options['tab_activity_disabled'])) {
      $sub_nav[] = array(
          'name'            => apply_filters( 'bp_shop_settings_link_label', __( 'Shop', 'wc4bp' ) ),
          'slug'            => 'shop',
          'parent_url'      => trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug()),
          'parent_slug'     => bp_get_settings_slug(),
          'screen_function' => 'wc4bp_screen_settings',
          'position'        => 30,
          'item_css_id'     => 'shop-settings',
          'user_has_access' => bp_is_my_profile()
      );
		}
		$position = 40;

		if(isset($wc4bp_pages_options['selected_pages']) && is_array($wc4bp_pages_options['selected_pages'])){
			foreach ($wc4bp_pages_options['selected_pages'] as $key => $attached_page) {
				$position++;
				$sub_nav[] = array(
		            'name'            => $attached_page['tab_name'],
		            'slug'            => $attached_page['tab_slug'],
		            'parent_url'      => $shop_link,
		            'parent_slug'     => $this->slug,
		            'screen_function' => 'wc4bp_screen_plugins',
		            'position'        => $position,
		            'item_css_id'     => 'shop-cart',
		            'user_has_access' => bp_is_my_profile()
		        );
		 	}
		}

		$sub_nav = apply_filters( 'bp_shop_sub_nav', $sub_nav, $shop_link, $this->slug );
    do_action( 'bp_shop_setup_nav' );
    parent::setup_nav( $main_nav, $sub_nav );
}

/**
 * Set up the Toolbar
 *
 * @global BuddyPress $bp The one true BuddyPress instance
 */
function setup_admin_bar($wp_admin_nav = Array()) {
		global $bp;

		$wc4bp_options			= get_option( 'wc4bp_options' );
		$wc4bp_pages_options	= get_option( 'wc4bp_pages_options' );

		$wp_admin_nav = array();

		if ( is_user_logged_in() ) {
			$user_domain   = bp_loggedin_user_domain();
			$settings_link = trailingslashit( $user_domain . BP_SETTINGS_SLUG );

			// Shop settings menu
			$wp_admin_nav[] = array(
				'parent' => 'my-account-settings',
				'id'     => 'my-account-settings-shop',
				'title'  => apply_filters( 'bp_shop_settings_nav_link_label', __( 'Shop', 'wc4bp' ) ),
				'href'   => trailingslashit( $settings_link . 'shop' )
			);

			$shop_link = trailingslashit( $user_domain . $this->id );

			// Shop menu items
			$wp_admin_nav[] = array(
				'parent' => $bp->my_account_menu_id,
				'id'     => 'my-account-' . $this->id,
				'title'  => apply_filters( 'bp_shop_nav_link_label', __( 'Shop', 'wc4bp' ) ),
				'href'   => trailingslashit( $shop_link ),
				'meta'	 => array( 'class'  => 'menupop')
			);

			if( ! isset( $wc4bp_options['tab_cart_disabled'])) {
				$wp_admin_nav[] = array(
					'parent' => 'my-account-' . $this->id,
					'id'     => 'my-account-' . $this->id . '-cart',
					'title'  => apply_filters( 'bp_shop_cart_nav_link_label', __( 'Shopping Cart', 'wc4bp' ) ),
					'href'   => trailingslashit( $shop_link )
				);
			}

			if( ! is_admin() && is_object(WC()->cart) && ! WC()->cart->is_empty() && ! isset( $wc4bp_options['tab_checkout_disabled'] ) ) {
				$wp_admin_nav[] = array(
					'parent' => 'my-account-' . $this->id,
					'id'     => 'my-account-' . $this->id . '-checkout',
					'title'  => apply_filters( 'bp_checkout_nav_link_label', __( 'Checkout', 'wc4bp' ) ),
					'href'   => trailingslashit( $shop_link . 'checkout' )
				);
			}

			if( ! isset( $wc4bp_options['tab_history_disabled'])) {
				$wp_admin_nav[] = array(
					'parent' => 'my-account-' . $this->id,
					'id'     => 'my-account-' . $this->id . '-history',
					'title'  => apply_filters( 'bp_history_nav_link_label', __( 'History', 'wc4bp' ) ),
					'href'   => trailingslashit( $shop_link . 'history' )
				);
			}

			if( ! isset( $wc4bp_options['tab_track_disabled'])) {
				$wp_admin_nav[] = array(
					'parent' => 'my-account-' . $this->id,
					'id'     => 'my-account-' . $this->id . '-track',
					'title'  => apply_filters( 'bp_track_order_nav_link_label', __( 'Track your order', 'wc4bp' ) ),
					'href'   => trailingslashit( $shop_link . 'track' )
				);
			}

			if(isset($wc4bp_pages_options['selected_pages']) && is_array($wc4bp_pages_options['selected_pages'])){
				foreach ($wc4bp_pages_options['selected_pages'] as $key => $attached_page) {

					$wp_admin_nav[] = array(
						'parent' => 'my-account-' . $this->id,
						'id'     => 'my-account-' . $this->id . '-'.$attached_page['tab_slug'],
						'title'  => $attached_page['tab_name'],
						'href'   => trailingslashit( $shop_link . $attached_page['tab_slug'] )
					);

			 	}
			}

		parent::setup_admin_bar( $wp_admin_nav );
	}
}

/**
 * WC4BP template loader.
 * @since 1.0
 */
function  wc4bp_members_load_template_filter($found_template, $templates) {
  	global $bp;

    if ( !bp_is_current_component( 'shop' ) )
	    return $found_template;

 		bp_register_template_stack('wc4bp_members_get_template_directory', 14);

		$found_template = locate_template('members/single/plugins.php', false, false);

		$wc4bp_options			= get_option( 'wc4bp_options' );

		if ($bp->current_action == 'home') {
			if(isset( $wc4bp_options['tab_cart_disabled']) && $wc4bp_options['tab_shop_default'] != 'default'){
				$bp->current_action = $wc4bp_options['tab_shop_default'];
				add_action('bp_template_content', create_function('', "
				bp_get_template_part( 'shop/member/plugin' );
				"));
			} else{
				add_action('bp_template_content', create_function('', "
				bp_get_template_part( 'shop/member/cart' );
				"));
			}
		} elseif ($bp->current_action == 'checkout') {
			add_action('bp_template_content', create_function('', "
			bp_get_template_part( 'shop/member/checkout' );
			"));
		} elseif ($bp->current_action == 'history') {
			add_action('bp_template_content', create_function('', "
			bp_get_template_part( 'shop/member/history' );
			"));
		} elseif ($bp->current_action == 'track') {
			add_action('bp_template_content', create_function('', "
			bp_get_template_part( 'shop/member/track' );
			"));
		} else {
			add_action('bp_template_content', create_function('', "
			bp_get_template_part( 'shop/member/plugin' );
			"));
		}

        return apply_filters('wc4bp_members_load_template_filter', $found_template);
	}
}

/**
 * Get the WC4BP template directory
 *
 * @package WC4BP
 * @since 0.1 beta
 *
 * @uses apply_filters()
 * @return string
 */
function  wc4bp_members_get_template_directory() {
	return apply_filters('wc4bp_members_get_template_directory', constant('WC4BP_ABSPATH_TEMPLATE_PATH'));
}

// Create the shop component
global $bp;
$bp->shop = new WC4BP_Component();
