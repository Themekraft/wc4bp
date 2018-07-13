<?php
/**
 * @package        WordPress
 * @subpackage    BuddyPress, WooCommerce
 * @author        Boris Glumpler
 * @copyright    2011, Themekraft
 * @link        https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Screen function to display the shopping cart
 *
 * Template can be changed via the <code> wc4bp_template_member_home</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since    1.0
 * @uses    bp_core_load_template()
 * @uses    apply_filters()
 */
function wc4bp_screen_cart() {
	/**
	 * Change the path the screen
	 *
	 * @param string The current path
	 */
	$screen_path = apply_filters( 'wc4bp_template_member_cart', 'shop/member/cart' );
	bp_core_load_template( $screen_path );
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
function wc4bp_screen_checkout() {
	/**
	 * Change the path the screen
	 *
	 * @param string The current path
	 */
	$screen_path = apply_filters( 'wc4bp_template_member_checkout', 'shop/member/checkout' );
	bp_core_load_template( $screen_path );
}

/**
 * Screen function to display the purchase history
 *
 * Template can be changed via the <code> wc4bp_template_member_history</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since    1.0
 * @uses    bp_core_load_template()
 * @uses    apply_filters()
 */
function wc4bp_screen_history() {
	/**
	 * Change the path the screen
	 *
	 * @param string The current path
	 */
	$screen_path = apply_filters( 'wc4bp_template_member_history', 'shop/member/history' );
	bp_core_load_template( $screen_path );
}

/**
 * Screen function for tracking an order
 *
 * Template can be changed via the <code> wc4bp_template_member_track_order</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since    1.0
 * @uses    bp_core_load_template()
 * @uses    apply_filters()
 */
function wc4bp_screen_track() {
	/**
	 * Change the path the screen
	 *
	 * @param string The current path
	 */
	$screen_path = apply_filters( 'wc4bp_template_member_track', 'shop/member/track' );
	bp_core_load_template( $screen_path );
}

/**
 * Screen function to display the order list
 *
 * Template can be changed via the <code> wc4bp_template_member_order</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since    1.0
 * @uses    bp_core_load_template()
 * @uses    apply_filters()
 */
function wc4bp_screen_orders() {
	/**
	 * Change the path the screen
	 *
	 * @param string The current path
	 */
	$screen_path = apply_filters( 'wc4bp_template_member_order', 'shop/member/orders' );
	bp_core_load_template( $screen_path );
}

/**
 * Screen function to display the download list
 *
 * Template can be changed via the <code> wc4bp_template_member_download</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since    1.0
 * @uses    bp_core_load_template()
 * @uses    apply_filters()
 */
function wc4bp_screen_downloads() {
	/**
	 * Change the path the screen
	 *
	 * @param string The current path
	 */
	$screen_path = apply_filters( 'wc4bp_template_member_download', 'shop/member/download' );
	bp_core_load_template( $screen_path );
}

/**
 * Screen function to display the edit account
 *
 * Template can be changed via the <code> wc4bp_template_member_edit_account</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since    1.0
 * @uses    bp_core_load_template()
 * @uses    apply_filters()
 */
function wc4bp_screen_edit_account() {
	/**
	 * Change the path the screen
	 *
	 * @param string The current path
	 */
	$screen_path = apply_filters( 'wc4bp_template_member_edit_account', 'shop/member/edit-account' );
	bp_core_load_template( $screen_path );
}

/**
 * Screen function to display the edit address
 *
 * Template can be changed via the <code> wc4bp_template_member_edit_address</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since    1.0
 * @uses    bp_core_load_template()
 * @uses    apply_filters()
 */
function wc4bp_screen_edit_address() {
	/**
	 * Change the path the screen
	 *
	 * @param string The current path
	 */
	$screen_path = apply_filters( 'wc4bp_template_member_edit_address', 'shop/member/edit-address' );
	bp_core_load_template( $screen_path );
}

/**
 * Screen function to display the payment methods
 *
 * Template can be changed via the <code> wc4bp_template_member_payment_methods</code>
 * filter hook. Note that template files can also be copied to the current theme.
 *
 * @since    1.0
 * @uses    bp_core_load_template()
 * @uses    apply_filters()
 */
function wc4bp_screen_payment_methods() {
	/**
	 * Change the path the screen
	 *
	 * @param string The current path
	 */
	$screen_path = apply_filters( 'wc4bp_template_member_payment_methods', 'shop/member/payment-methods' );
	bp_core_load_template( $screen_path );
}

/**
 * Display shop settings that can be changed by a user
 * Save the settings
 *
 * @since    unknown
 */
function wc4bp_screen_settings() {
	try {
		if ( ! bp_is_settings_component() || bp_current_action() !== wc4bp_Manager::get_shop_slug() ) {
			return false;
		}
		/**
		 * Start the setting for the setting screen
		 */
		do_action( 'wc4bp_screen_settings' );
		$wc4bp_values = Request_Helper::get_post_param( 'wc4bp' );
		if ( ! empty( $wc4bp_values ) ) {
			// default values
			$yes_no = array( 'yes', 'no' );

			// check that we got valid data
			$review2 = '';
			if ( ! empty( $wc4bp_values['reviews_2_activity'] ) ) {
				$review2 = $wc4bp_values['reviews_2_activity'];
			}
			$purchases = '';
			if ( ! empty( $wc4bp_values['purchases_2_activity'] ) ) {
				$purchases = $wc4bp_values['purchases_2_activity'];
			}
			$reviews_2_activity   = ( in_array( $review2, $yes_no, true ) ) ? $review2 : 'yes';
			$purchases_2_activity = ( in_array( $purchases, $yes_no, true ) ) ? $purchases : 'yes';

			/**
			 * Before update user settings
             *
             * @param int The user id
             * @param var The value
			 */
			do_action( 'wc4bp_pre_update_user_settings', bp_displayed_user_id(), $wc4bp_values );

			// save them
			bp_update_user_meta( bp_displayed_user_id(), 'notification_activity_shop_reviews', $reviews_2_activity );
			bp_update_user_meta( bp_displayed_user_id(), 'notification_activity_shop_purchases', $purchases_2_activity );

			/**
			 * After update user settings
			 *
			 * @param int The user id
			 * @param var The value
			 */
			do_action( 'wc4bp_post_update_user_settings', bp_displayed_user_id(), $wc4bp_values );

			// Set the feedback messages
			bp_core_add_message( __( 'Changes saved.', 'wc4bp' ) );

			// and clear the POST to make the QA happy :)
			bp_core_redirect( wc4bp_get_settings_link() );
		}

		add_action( 'bp_template_title', 'wc4bp_screen_settings_title' );
		add_action( 'bp_template_content', 'wc4bp_screen_settings_content' );

		bp_core_load_template( apply_filters( 'wc4bp_screen_settings', 'members/single/plugins' ) );

	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );

		return false;
	}

	return true;
}

/**
 * The main title for the Shop Settings page
 *
 * @since    unknown
 */
function wc4bp_screen_settings_title() {
	_e( 'Shop Settings', 'wc4bp' );
}

/**
 * Content of the Settings page
 */
function wc4bp_screen_settings_content() {
	try {
		if ( ! $shop_reviews = bp_get_user_meta( bp_displayed_user_id(), 'notification_activity_shop_reviews', true ) ) {
			$shop_reviews = 'yes';
		}
		if ( ! $shop_purchases = bp_get_user_meta( bp_displayed_user_id(), 'notification_activity_shop_purchases', true ) ) {
			$shop_purchases = 'yes';
		}
		?>
        <form action="<?php wc4bp_settings_link() ?>" method="POST">

            <table class="notification-settings" id="shop-notification-settings">
                <thead>
                <tr>
                    <th class="icon"></th>
                    <th class="title"><?php _e( 'Activity Stream', 'wc4bp' ) ?></th>
                    <th class="yes"><?php _e( 'Yes', 'wc4bp' ) ?></th>
                    <th class="no"><?php _e( 'No', 'wc4bp' ) ?></th>
                </tr>
                </thead>

                <tbody>
                <tr id="shop-notification-settings-reviews">
                    <td></td>
                    <td><?php _e( 'Post to activity stream all reviews written by me', 'wc4bp' ) ?></td>
                    <td class="yes"><input type="radio" name="wc4bp[reviews_2_activity]" value="yes" <?php checked( $shop_reviews, 'yes', true ) ?>/></td>
                    <td class="no"><input type="radio" name="wc4bp[reviews_2_activity]" value="no" <?php checked( $shop_reviews, 'no', true ) ?>/></td>
                </tr>
                <tr id="shop-notification-settings-purchases">
                    <td></td>
                    <td><?php _e( 'Post to activity stream all purchases I\'ve made', 'wc4bp' ) ?></td>
                    <td class="yes"><input type="radio" name="wc4bp[purchases_2_activity]" value="yes" <?php checked( $shop_purchases, 'yes', true ) ?>/></td>
                    <td class="no"><input type="radio" name="wc4bp[purchases_2_activity]" value="no" <?php checked( $shop_purchases, 'no', true ) ?>/></td>
                </tr>

				<?php
				/**
				 * Setting screen for Activity Stream executed
				 */
                do_action( 'wc4bp_screen_notification_activity_settings' );
                ?>
                </tbody>
            </table>

            <div class="submit">
                <input type="submit" name="submit" value="<?php _e( 'Save Changes', 'wc4bp' ); ?>" id="submit" class="auto">
            </div>

        </form>
		<?php
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
	}
}

/**
 * Setup the tracked order
 *
 * @since    1.0.8
 */
function wc4bp_setup_tracking_order() {
	try {
		if ( ! wc4bp_is_page( 'track' ) ) {
			return false;
		}
		$track = Request_Helper::get_post_param( 'track' );
		if ( ! empty( $track ) ) {
			global $current_order;
			check_admin_referer( 'bp-shop_order_tracking' );
			$post_order_id    = Request_Helper::get_post_param( 'track', 0, 'intval' );
			$post_order_email = Request_Helper::get_post_param( 'order_email' );
			$order_id         = empty( $post_order_id ) ? 0 : $post_order_id;
			$order_email      = empty( $post_order_email ) ? '' : $post_order_email;
			if ( ! $order_id ) {
				echo '<p class="woocommerce_error">' . __( 'Please enter a valid order ID', 'wc4bp' ) . '</p>';
			} elseif ( ! $order_email ) {
				echo '<p class="woocommerce_error">' . __( 'Please enter a valid order email', 'wc4bp' ) . '</p>';
			} else {
				$order = new WC_Order( apply_filters( 'woocommerce_shortcode_order_tracking_order_id', $order_id ) );
				if ( $order->get_id() && $order_email ) {
					if ( strtolower( $order->get_billing_email() ) === strtolower( $order_email ) ) {
						$current_order = $order;
					} else {
						echo '<p class="woocommerce_error">' . __( 'You are not allowed to view this order.', 'wc4bp' ) . '</p>';
					}
				} else {
					echo '<p class="woocommerce_error">' . __( 'Sorry, we could not find that order id in our database.', 'wc4bp' ) . '</p>';
				}
			}
		}
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
	}
}

//add_action( 'wc4bp_after_track_heading', 'wc4bp_setup_tracking_order' );

/**
 * Output the tracked order
 *
 * @since    1.0.8
 */
function wc4bp_output_tracking_order() {
	try {
		global $current_order;

		if ( $current_order instanceof WC_Order ) {
			do_action( 'woocommerce_track_order', $current_order->get_id() );
			echo '<h3>' . __( 'Your Order', 'wc4bp' ) . '<h3>';

			wc_get_template( 'order/tracking.php', array(
				'order' => $current_order
			) );
		}
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
	}
}

add_action( 'wc4bp_after_track_body', 'wc4bp_output_tracking_order' );

function wc4bp_screen_plugins() {
	if ( bp_displayed_user_id() && bp_is_current_component( wc4bp_Manager::get_shop_slug() ) && bp_current_action() ) {
		bp_core_load_template( apply_filters( 'wc4bp_template_member_plugin', 'shop/member/plugin' ) );
	}
}
