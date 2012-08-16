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

class BPSHOP_Component extends BP_Component
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
     * @todo    Move self::includes() out of constructor once the BP bug
     *             (hook priority) has been resolved to allow use of parent
     *             method
     * @since     1.0
     */
    function __construct() {
        parent::start( $this->id, __( 'Woocommerce Integration', 'bpshop' ), BPSHOP_ABSPATH );
        
        $this->includes();

        add_action( 'bp_register_activity_actions', array( &$this, 'register_activity_actions' ) );
    }

    /**
     * Register acctivity actions
     *
     * @since     1.0.4
     */
    function register_activity_actions() {
        if( ! bp_is_active( 'activity' ) )
            return false;

        bp_activity_set_action( $this->id, 'new_shop_review',   __( 'New review created', 'bpshop' ) );
        bp_activity_set_action( $this->id, 'new_shop_purchase', __( 'New purchase made',   'bpshop' ) );

        do_action( 'bpshop_register_activity_actions' );
    }
    
    /**
     * Include files
     *
     * @since     1.0
     */
    function includes() {
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
     * @since     1.0
     * @global    object    $bp
     */
    function setup_globals() {
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
     * @since    1.0
     * @global   object    $bp
     */
    function setup_nav() {
        // Add 'Shop' to the main navigation
        $main_nav = array(
            'name'                          => __( 'Shop', 'bpshop' ),
            'slug'                          => $this->slug,
            'position'                      => 70,
            'screen_function'               => 'bpshop_screen_shopping_cart',
            'default_subnav_slug'           => 'cart',
            'item_css_id'                   => $this->id,
            'show_for_displayed_user'       => false
        );

        $shop_link = trailingslashit( bp_loggedin_user_domain() . $this->slug );

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

        // Add shop settings subpage
        $sub_nav[] = array(
            'name'            => __( 'Shop', 'bpshop' ),
            'slug'            => 'shop',
            'parent_url'      => trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug()),
            'parent_slug'     => bp_get_settings_slug(),
            'screen_function' => 'bpshop_screen_settings',
            'position'        => 30,
            'item_css_id'     => 'shop-settings',
            'user_has_access' => bp_is_my_profile()
        );
                                
        do_action( 'bp_shop_setup_nav' );
        
        parent::setup_nav( $main_nav, $sub_nav );
    }
}

// Create the shop component
$bp->shop = new BPSHOP_Component();
?>