<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

class WC4BP_Activity_Stream {

	public function __construct() {
		add_action( 'wp_insert_comment', array( $this, 'wc4bp_stream_product_review' ), 10, 2 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'wc4bp_stream_order_complete' ) );
	}

	/**
	 * Adds an activity stream item when a user has written a new review to a product.
	 *
	 * @since   3.1.7
	 *
	 * @uses    bp_is_active() Checks that the Activity component is active
	 * @uses    bp_activity_add() Adds an entry to the activity component tables for a specific activity
	 *
	 * @param $comment_id
	 * @param $comment_data
	 *
	 * @return bool
	 */
	function wc4bp_stream_product_review( $comment_id, $comment_data ) {
		try {
			/**
			 * Determinate if the activity stream is enabled.
			 *
			 * @param boolean.
			 */
			$is_active = apply_filters( 'wc4bp_activate_stream_activity', true );

			if ( ! $is_active ) {
				return false;
			}

			if ( ! bp_is_active( 'activity' ) ) {
				return false;
			}

			// Get the product data
			$product = get_post( $comment_data->comment_post_ID );

			if ( $product->post_type != 'product' ) {
				return false;
			}

			/**
			 * Change the user who wrote the comment
			 *
			 * @param integer The user who wrote the comment.
			 */
			$user_id = apply_filters( 'wc4bp_loader_review_activity_user_id', $comment_data->user_id );

			// check that user enabled updating the activity stream
			if ( bp_get_user_meta( $user_id, 'notification_activity_shop_reviews', true ) == 'no' ) {
				return false;
			}

			// Reviews by guests never created an activity (the hook only ran for logged-in visitors), keep it that way now that it runs in every request.
			if ( empty( $user_id ) ) {
				return false;
			}

			$user_link = wc4bp_activity_user_link( $user_id );

			/**
			 * Modify the string to insert into the BuddyPress Activity Stream on Product Review
			 *
			 * @param string The stream text
			 * @param integer The user who write the comment
			 * @param WP_Post The comment data
			 * @param WC_Product
			 * @param string The action
			 */
			$stream = apply_filters(
				'wc4bp_stream_product_review',
				sprintf(
					/* translators: %1$s: $user_link; %2$s: get_permalink( $comment_data->comment_post_ID ); %3$s: $product->post_title. */ /* translators: %1$s: value; %2$s: value; %3$s: value. */ __( '%1$s wrote a review about <a href="%2$s">%3$s</a>', 'wc4bp' ),
					$user_link,
					get_permalink( $comment_data->comment_post_ID ),
					$product->post_title
				),
				$user_id,
				$comment_data,
				$product,
				'product_review'
			);

			// record the activity
			bp_activity_add(
				array(
					'user_id'   => $user_id,
					'content'   => $stream,
					'component' => wc4bp_Manager::get_shop_slug(),
					'type'      => 'new_shop_review',
				)
			);

			return true;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}

		return false;
	}

	/**
	 * Adds an activity stream item when a user has purchased a new product(s).
	 *
	 * @since   3.1.7
	 *
	 * @return bool
	 */
	public function wc4bp_stream_order_complete( $order_id ) {
		try {
			/**
			 * This filter is documented in /wc4bp-premium/class/core/wc4bp-helpers.php:137
			 */
			$stream_visibility = get_option( 'wc4bp_options_notifications' );
			if( isset( $stream_visibility['disable_activity_feed'] ) && $stream_visibility['disable_activity_feed'] === '1' ){
				return false;
			}
			
			$is_active = apply_filters( 'wc4bp_activate_stream_activity', true );

			if ( ! $is_active ) {
				return false;
			}

			if ( ! bp_is_active( 'activity' ) ) {
				return false;
			}

			$order = wc_get_order( $order_id );

			if ( ! $order || $order->get_status() != 'completed' ) {
				return false;
			}

			// The order can reach "completed" more than once (completed, back to processing, completed again): one activity per order.
			if ( $order->get_meta( '_wc4bp_purchase_activity_id' ) ) {
				return false;
			}

			if ( ! wc4bp_order_creates_purchase_activity( $order ) ) {
				return false;
			}

			// check that user enabled updating the activity stream
			if ( bp_get_user_meta( $order->get_customer_id(), 'notification_activity_shop_purchases', true ) == 'no' ) {
				return false;
			}

			// The request user is the admin, a webhook or nobody: attribute the activity to the order's customer.
			$user_link = wc4bp_activity_user_link( $order->get_customer_id() );

			// if several products - combine them, otherwise - display the product name
			$items = $order->get_items();
			$names = wc4bp_activity_product_links( $items );

			/**
			 * Modify the string to insert into the BuddyPress Activity Stream on Order Complete
			 *
			 * @param string The stream text
			 * @param integer The customer user id
			 * @param WC_Order
			 * @param WC_Order_Item_Product
			 * @param string The action
			 */
			$stream = apply_filters(
				'wc4bp_stream_order_complete',
				sprintf(
					/* translators: %1$s: $user_link; %2$s: implode( ', ', $names ). */ /* translators: %1$s: value; %2$s: value. */ __( '%1$s purchased %2$s', 'wc4bp' ),
					$user_link,
					implode( ', ', $names )
				),
				$order->get_user_id(),
				$order,
				$items,
				'order_complete'
			);
			// record the activity
			$activity_id = bp_activity_add(
				array(
					'user_id'   => $order->get_user_id(),
					'action'    => $stream,
					'component' => wc4bp_Manager::get_shop_slug(),
					'type'      => 'new_shop_purchase',
					'item_id'   => $order->get_id(),
				)
			);

			if ( ! $activity_id ) {
				return false;
			}

			$order->update_meta_data( '_wc4bp_purchase_activity_id', $activity_id );
			$order->save_meta_data();

			return true;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}

		return false;
	}
}

/**
 * Whether a completed order gets a "bought" activity.
 *
 * Guest orders (no customer account) and WooCommerce Subscriptions renewal orders do not. Guest checkouts never
 * created an activity while the hook only ran for logged-in visitors, and renewals are completed automatically
 * every billing cycle, so posting them would repeat the same purchase in the stream. The initial subscription
 * order, resubscribes and switches are purchases the customer made at checkout, so they still post.
 *
 * @param WC_Order $order
 *
 * @return bool
 */
function wc4bp_order_creates_purchase_activity( $order ) {
	$create = true;

	if ( (int) $order->get_customer_id() <= 0 ) {
		$create = false;
	} elseif ( function_exists( 'wcs_order_contains_renewal' ) && wcs_order_contains_renewal( $order ) ) {
		$create = false;
	}

	/**
	 * Decide whether a completed order gets a purchase activity.
	 *
	 * @param bool     $create False for guest orders and subscription renewal orders.
	 * @param WC_Order $order
	 */
	return (bool) apply_filters( 'wc4bp_create_purchase_activity', $create, $order );
}

/**
 * Link to a user's profile, or "A guest" when there is no such user (guest orders).
 *
 * @param int $user_id The user the activity belongs to, not the current user.
 *
 * @return string
 */
function wc4bp_activity_user_link( $user_id ) {
	$user_id = absint( $user_id );
	if ( $user_id > 0 && get_userdata( $user_id ) ) {
		return bp_core_get_userlink( $user_id );
	}

	return __( 'A guest', 'wc4bp' );
}

/**
 * Product links for the order items, skipping items whose product no longer exists.
 *
 * @param WC_Order_Item[] $order_items
 *
 * @return string[]
 */
function wc4bp_activity_product_links( $order_items ) {
	$names = array();
	/** @var WC_Order_Item_Product $item */
	foreach ( $order_items as $item ) {
		$product = is_callable( array( $item, 'get_product' ) ) ? $item->get_product() : false;
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		$names[] = '<a href="' . $product->get_permalink() . '">' . $product->get_name() . '</a>';
	}

	return $names;
}

add_filter( 'wc4bp_stream_order_complete', 'wc4bp_callback_stream_order_complete', 10, 5 );

/**
 * Override the Activity Stream for Order Complete
 *
 * @param string          $text_output
 * @param int             $user_id_from_order
 * @param WC_Order        $order
 * @param WC_ORder_Item[] $order_items
 * @param string          $stream_action
 *
 * @return string
 */
function wc4bp_callback_stream_order_complete( $text_output, $user_id_from_order, $order, $order_items, $stream_action ) {
	$stream_visibility = get_option( 'wc4bp_options_notifications' );
	if( isset( $stream_visibility['disable_activity_feed'] ) && $stream_visibility['disable_activity_feed'] === '1' ){
		return false;
	}
	$user_link = wc4bp_activity_user_link( $user_id_from_order );
	$names     = wc4bp_activity_product_links( $order_items );

	return sprintf(
		/* translators: %1$s: $user_link; %2$s: implode( ', ', $names ). */ /* translators: %1$s: value; %2$s: value. */ __( 'The user: %1$s bought %2$s', 'wc4bp' ),
		$user_link,
		implode( ', ', $names )
	);
}
