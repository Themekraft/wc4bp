<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC4BP_Activity_Stream {

    private $wc4bp_options;
	public function __construct() {
		add_action( 'wp_insert_comment', array( $this, 'wc4bp_stream_product_review' ), 10, 2 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'wc4bp_stream_order_complete' ) );
        add_filter('wc4bp_activate_stream_activity',array($this,'wc4bp_check_shop_settings_tab'),10,1);
        $this->wc4bp_options       = get_option( 'wc4bp_options' );
	}


    function wc4bp_check_shop_settings_tab($is_active)
    {
        if ( isset( $this->wc4bp_options['disable_shop_settings_tab'] ) ) {

            $is_active = false;

        }
        return $is_active;
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

			$user_link = bp_core_get_userlink( $user_id );

			/**
			 * Modify the string to insert into the BuddyPress Activity Stream on Product Review
			 *
			 * @param string The stream text
			 * @param integer The user who write the comment
			 * @param WP_Post The comment data
			 * @param WC_Product
			 * @param string The action
			 */
			$stream = apply_filters( 'wc4bp_stream_product_review',
				sprintf(
					__( '%s wrote a review about <a href="%s">%s</a>', 'wc4bp' ),
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
			bp_activity_add( array(
				'user_id'   => $user_id,
				'action'    => $stream,
				'component' => wc4bp_Manager::get_shop_slug(),
				'type'      => 'new_shop_review',
			) );

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
			$is_active = apply_filters( 'wc4bp_activate_stream_activity', true );

			if ( ! $is_active ) {
				return false;
			}

			if ( ! bp_is_active( 'activity' ) ) {
				return false;
			}

			$order = new WC_Order( $order_id );

			if ( $order->get_status() != 'completed' ) {
				return false;
			}

			// check that user enabled updating the activity stream
			if ( bp_get_user_meta( $order->get_customer_id(), 'notification_activity_shop_purchases', true ) == 'no' ) {
				return false;
			}

			$user_link = bp_core_get_userlink( $order->get_customer_id() );

			// if several products - combine them, otherwise - display the product name
			$items = $order->get_items();
			$names    = array();
			/** @var WC_Order_Item_Product $item */
			foreach ( $items as $item ) {
				$names[] = '<a href="' . $item->get_product()->get_permalink() . '">' . $item->get_product()->get_name() . '</a>';
			}

			/**
			 * Modify the string to insert into the BuddyPress Activity Stream on Order Complete
			 *
			 * @param string The stream text
			 * @param integer The customer user id
			 * @param WC_Order
			 * @param WC_Order_Item_Product
			 * @param string The action
			 */
			$stream = apply_filters( 'wc4bp_stream_order_complete',
				sprintf(
					__( '%s purchased %s', 'wc4bp' ),
					$user_link,
					implode( ', ', $names )
				),
				$order->get_user_id(),
				$order,
				$items,
				'order_complete'
			);
			// record the activity
			bp_activity_add( array(
				'user_id'   => $order->get_user_id(),
				'action'    => $stream,
				'component' => wc4bp_Manager::get_shop_slug(),
				'type'      => 'new_shop_purchase',
			) );

			return true;
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}

		return false;
	}
}