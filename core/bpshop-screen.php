<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress,Jigoshop
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Screen function to display the shopping cart
 * 
 * Template can be changed via the <code>bpshop_template_member_home</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since 	1.0
 * @uses	bp_core_load_template()
 * @uses	apply_filters()
 */
function bpshop_screen_shopping_cart()
{
	bp_core_load_template( apply_filters( 'bpshop_template_member_shopping_cart', 'shop/member/home' ) );
}

/**
 * Screen function to display the purchase history
 * 
 * Template can be changed via the <code>bpshop_template_member_history</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since 	1.0
 * @uses	bp_core_load_template()
 * @uses	apply_filters()
 */
function bpshop_screen_history()
{
	bp_core_load_template( apply_filters( 'bpshop_template_member_history', 'shop/member/home' ) );
}

/**
 * Screen function for tracking an order
 * 
 * Template can be changed via the <code>bpshop_template_member_track_order</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since 	1.0
 * @uses	bp_core_load_template()
 * @uses	apply_filters()
 */
function bpshop_screen_track_order()
{
	bp_core_load_template( apply_filters( 'bpshop_template_member_track_order', 'shop/member/home' ) );
}
?>