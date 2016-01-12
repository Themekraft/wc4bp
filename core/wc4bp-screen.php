<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
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
 * Template can be changed via the <code> wc4bp_template_member_home</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since 	1.0
 * @uses	bp_core_load_template()
 * @uses	apply_filters()
 */
function  wc4bp_screen_shopping_cart() {
	bp_core_load_template( apply_filters( 'wc4bp_template_member_shopping_cart', 'shop/member/cart' ) );
}

/**
 * Screen function to display the checkout
 *
 * Template can be changed via the <code> wc4bp_template_member_checkout</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since   1.0
 * @uses    bp_core_load_template()
 * @uses    apply_filters()
 */
function  wc4bp_screen_shopping_checkout() {
    bp_core_load_template( apply_filters( 'wc4bp_template_member_checkout', 'shop/member/checkout' ) );
}

/**
 * Screen function to display the purchase history
 *
 * Template can be changed via the <code> wc4bp_template_member_history</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since 	1.0
 * @uses	bp_core_load_template()
 * @uses	apply_filters()
 */
function  wc4bp_screen_history() {
	bp_core_load_template( apply_filters( 'wc4bp_template_member_history', 'shop/member/history' ) );
}

/**
 * Screen function for tracking an order
 *
 * Template can be changed via the <code> wc4bp_template_member_track_order</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since 	1.0
 * @uses	bp_core_load_template()
 * @uses	apply_filters()
 */
function  wc4bp_screen_track_order() {
	bp_core_load_template( apply_filters( 'wc4bp_template_member_track_order', 'shop/member/track' ) );
}

/**
 * Display shop settings that can be changed by a user
 * Save the settings
 *
 * @since 	unknown
 */
function  wc4bp_screen_settings() {
    if( ! bp_is_settings_component() || bp_current_action() != 'shop' )
        return false;

    do_action( 'wc4bp_screen_settings' );

    if( isset( $_POST['wc4bp'] ) && ! empty( $_POST['wc4bp'] ) ){
        // default values
        $yes_no = array( 'yes','no' );

        // check that we got valid data
        $reviews_2_activity   = ( in_array( $_POST['wc4bp']['reviews_2_activity'],   $yes_no ) ) ? $_POST['wc4bp']['reviews_2_activity']   : 'yes';
        $purchases_2_activity = ( in_array( $_POST['wc4bp']['purchases_2_activity'], $yes_no ) ) ? $_POST['wc4bp']['purchases_2_activity'] : 'yes';

        do_action( 'wc4bp_pre_update_user_settings', bp_displayed_user_id(), $_POST['wc4bp'] );

        // save them
        bp_update_user_meta( bp_displayed_user_id(), 'notification_activity_shop_reviews',   $reviews_2_activity );
        bp_update_user_meta( bp_displayed_user_id(), 'notification_activity_shop_purchases', $purchases_2_activity );

        do_action( 'wc4bp_post_update_user_settings', bp_displayed_user_id(), $_POST['wc4bp'] );

        // Set the feedback messages
        bp_core_add_message( __( 'Changes saved.', 'wc4bp' ) );

        // and clear the POST to make the QA happy :)
        bp_core_redirect(  wc4bp_get_settings_link() );
    }

	add_action( 'bp_template_title', 'wc4bp_screen_settings_title' );
	add_action( 'bp_template_content', 'wc4bp_screen_settings_content' );

    bp_core_load_template( apply_filters( 'wc4bp_screen_settings', 'members/single/plugins' ) );
}

/**
 * The main title for the Shop Settings page
 *
 * @since 	unknown
 */
function  wc4bp_screen_settings_title() {
    _e( 'Shop Settings', 'wc4bp' );
}

/**
 * Content of the Settings page
 *
 * @since 	unknown
 * @uses 	bp_is_settings_component()
 * @uses 	bp_current_action()
 * @uses 	bp_get_user_meta()
 * @uses 	do_action()
 */
function  wc4bp_screen_settings_content() {
    if( ! $shop_reviews = bp_get_user_meta( bp_displayed_user_id(), 'notification_activity_shop_reviews', true ) )
        $shop_reviews = 'yes';

    if( ! $shop_purchases = bp_get_user_meta( bp_displayed_user_id(), 'notification_activity_shop_purchases', true ) )
        $shop_purchases = 'yes';

    ?>
    <form action="<?php  wc4bp_settings_link() ?>" method="POST">

        <table class="notification-settings" id="shop-notification-settings">
            <thead>
                <tr>
                    <th class="icon"></th>
                    <th class="title"><?php _e( 'Activity Stream', 'wc4bp' ) ?></th>
                    <th class="yes"><?php _e( 'Yes', 'wc4bp' ) ?></th>
                    <th class="no"><?php _e( 'No', 'wc4bp' )?></th>
                </tr>
            </thead>

            <tbody>
                <tr id="shop-notification-settings-reviews">
                    <td></td>
                    <td><?php _e( 'Post to activity stream all reviews written by me', 'wc4bp' ) ?></td>
                    <td class="yes"><input type="radio" name=" wc4bp[reviews_2_activity]" value="yes" <?php checked( $shop_reviews, 'yes', true ) ?>/></td>
                    <td class="no"><input type="radio" name=" wc4bp[reviews_2_activity]" value="no" <?php checked( $shop_reviews, 'no', true ) ?>/></td>
                </tr>
                <tr id="shop-notification-settings-purchases">
                    <td></td>
                    <td><?php _e( 'Post to activity stream all purchases I\'ve made', 'wc4bp' ) ?></td>
                    <td class="yes"><input type="radio" name=" wc4bp[purchases_2_activity]" value="yes" <?php checked( $shop_purchases, 'yes', true ) ?>/></td>
                    <td class="no"><input type="radio" name=" wc4bp[purchases_2_activity]" value="no" <?php checked( $shop_purchases, 'no', true ) ?>/></td>
                </tr>

                <?php do_action( 'wc4bp_screen_notification_activity_settings' ); ?>
            </tbody>
        </table>

        <?php do_action( 'wc4bp_screen_notification_settings' ); ?>

        <div class="submit">
            <input type="submit" name="submit" value="<?php _e( 'Save Changes', 'wc4bp' ); ?>" id="submit" class="auto">
        </div>

    </form>
	<?php
}

/**
 * Setup the tracked order
 *
 * @since 	1.0.8
 */
function  wc4bp_setup_tracking_order() {
	if( !  wc4bp_is_page( 'track' ) )
		return false;

	if( isset( $_POST['track'] ) ) :
		global $current_order;

		check_admin_referer( 'bp-shop_order_tracking' );

		$order_id 	 = empty( $_POST['orderid'] ) ? 0 : esc_attr( $_POST['orderid'] );
		$order_email = empty( $_POST['order_email'] ) ? '' : esc_attr( $_POST['order_email']) ;

		if ( ! $order_id ) {
			echo '<p class="woocommerce_error">' . __('Please enter a valid order ID', 'wc4bp') . '</p>';

		} elseif ( ! $order_email ) {
			echo '<p class="woocommerce_error">' . __('Please enter a valid order email', 'wc4bp') . '</p>';

		} else {
			$order = new WC_Order( apply_filters( 'woocommerce_shortcode_order_tracking_order_id', $order_id ) );

			if( $order->id && $order_email ) {
				if( strtolower( $order->billing_email ) == strtolower( $order_email ) )
					$current_order = $order;
				else
					echo '<p class="woocommerce_error">' . __('You are not allowed to view this order.', 'wc4bp') . '</p>';

			} else {
				echo '<p class="woocommerce_error">' . __('Sorry, we could not find that order id in our database.', 'wc4bp') . '</p>';
			}
		}
	endif;
}
//add_action( 'wc4bp_after_track_heading', 'wc4bp_setup_tracking_order' );

/**
 * Output the tracked order
 *
 * @since 	1.0.8
 */
function  wc4bp_output_tracking_order() {
	global $current_order;

	if( $current_order instanceof WC_Order ) :
		do_action( 'woocommerce_track_order', $current_order->id );
		echo '<h3>'. __( 'Your Order', 'wc4bp' ) .'<h3>';

        wc_get_template( 'order/tracking.php', array(
			'order' => $current_order
		) );
	endif;
}
add_action( 'wc4bp_after_track_body', 'wc4bp_output_tracking_order' );

function  wc4bp_screen_plugins(){

    if ( bp_displayed_user_id() && bp_is_current_component( 'shop' ) && bp_current_action() ) {

        bp_core_load_template( apply_filters( 'wc4bp_template_member_plugin', 'shop/member/plugin' ) );

	}
}
