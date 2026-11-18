<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * This file is to handle notifications within wc4bp
 */

 /**
  * Adding the new component for purchase notifications
  *
  * @author Romeli
  */
add_filter( 'bp_notifications_get_registered_components', 'wc4bp_purchased_notifications_register_component' );
function wc4bp_purchased_notifications_register_component( $component_names = array() ) {
	if ( ! is_array( $component_names ) ) {
		$component_names = array();
	}
	array_push( $component_names, 'purchase_completed' );
	return $component_names;
}

/**
 * Formating the purchase component message
 *
 * @author Romeli
 */
add_filter( 'bp_notifications_get_notifications_for_user', 'wc4bp_format_purchased_notifications', 10, 5 );
function wc4bp_format_purchased_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	if ( 'send_purchase_notification' === $action ) {
		if ( ! empty( $item_id ) ) {
			$order = wc_get_order( $item_id );
			if ( empty( $order ) ) {
				$message = __( 'A user bought a product', 'wc4bp' );
				return $message;
			}
			$names     = array();
			$message   = /* translators: %1$s: value; %2$s: value. */ /* translators: %1$s: value; %2$s: value. */ __( 'The user %1$s has bought %2$s', 'wc4bp' );
			$user_link = bp_core_get_userlink( $order->get_customer_id() );
			foreach ( $order->get_items() as $item_id => $item ) {
				$product = $item->get_product();
				if ( ! $product instanceof WC_Product ) {
					continue;
				}
				$names[] = '<a href="' . $item->get_product()->get_permalink() . '">' . $item->get_product()->get_name() . '</a>';
			}
			$notification = sprintf( $message, $user_link, implode( ', ', $names ) );
		}
		return $notification;
	}
	return $action;
}

/**
 * Firing the new notification
 *
 * Every user gets one notification per order when the order reaches the configured status. The users are
 * notified in batches by Action Scheduler jobs, so the request that changes the status (checkout, gateway
 * webhook, admin) does not insert one row per user.
 *
 * @author Romeli
 */
add_action( 'woocommerce_order_status_changed', 'wc4bp_send_purchase_notification', 99, 3 );
function wc4bp_send_purchase_notification( $order_id, $old_status = '', $new_status = '' ) {
	try {
		$send_notifications = get_option( 'wc4bp_options_notifications' );
		if ( ! is_array( $send_notifications ) || ! isset( $send_notifications['notifications_settings'] ) || '1' !== $send_notifications['notifications_settings'] ) {
			return false;
		}
		$order_status = empty( $send_notifications['notifications_order_status'] ) ? 'completed' : $send_notifications['notifications_order_status'];
		if ( '' !== $new_status && $new_status !== $order_status ) {
			return false;
		}
		if ( ! function_exists( 'bp_is_active' ) || ! bp_is_active( 'notifications' ) ) {
			return false;
		}
		$order = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order || $order->get_status() !== $order_status ) {
			return false;
		}
		// The order can reach the status more than once (completed, back to processing, completed again): notify once per order.
		if ( $order->get_meta( '_wc4bp_purchase_notification_sent' ) ) {
			return false;
		}
		$order->update_meta_data( '_wc4bp_purchase_notification_sent', time() );
		$order->save_meta_data();

		if ( ! wc4bp_enqueue_purchase_notification_batch( $order->get_id(), 0 ) ) {
			// No Action Scheduler: notify every user in this request, still one batch of IDs at a time.
			$after_user_id = 0;
			do {
				$after_user_id = wc4bp_send_purchase_notification_batch( $order->get_id(), $after_user_id, false );
			} while ( $after_user_id );
		}

		return true;
	} catch ( Exception $exception ) {
		WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
	}

	return false;
}

/**
 * Number of users notified per batch.
 *
 * @return int
 */
function wc4bp_purchase_notification_batch_size() {
	return max( 1, (int) apply_filters( 'wc4bp_purchase_notification_batch_size', 500 ) );
}

/**
 * Queue the batch of users that comes after $after_user_id.
 *
 * @param int $order_id      Order the users are notified about.
 * @param int $after_user_id Last user ID already notified.
 *
 * @return bool False when Action Scheduler is not available or the job could not be queued.
 */
function wc4bp_enqueue_purchase_notification_batch( $order_id, $after_user_id ) {
	if ( ! function_exists( 'as_enqueue_async_action' ) || ( class_exists( 'ActionScheduler' ) && method_exists( 'ActionScheduler', 'is_initialized' ) && ! ActionScheduler::is_initialized() ) ) {
		return false;
	}

	return (bool) as_enqueue_async_action( 'wc4bp_send_purchase_notification_batch', array( (int) $order_id, (int) $after_user_id ), 'wc4bp' );
}

/**
 * Notify one batch of users about an order: the users whose ID is greater than $after_user_id, in ID order.
 *
 * @param int  $order_id      Order the users are notified about.
 * @param int  $after_user_id Last user ID already notified.
 * @param bool $queue_next    Queue the next batch as another Action Scheduler job.
 *
 * @return int The last user ID notified when more users may follow, 0 when this was the last batch.
 */
add_action( 'wc4bp_send_purchase_notification_batch', 'wc4bp_send_purchase_notification_batch', 10, 2 );
function wc4bp_send_purchase_notification_batch( $order_id, $after_user_id = 0, $queue_next = true ) {
	$order_id      = (int) $order_id;
	$after_user_id = (int) $after_user_id;
	if ( $order_id <= 0 || ! function_exists( 'bp_notifications_add_notification' ) ) {
		return 0;
	}

	$batch_size = wc4bp_purchase_notification_batch_size();
	// Page by ID instead of offset, so users added or deleted between batches do not shift the pages.
	$after_id_where = static function ( $query ) use ( $after_user_id ) {
		global $wpdb;
		$query->query_where .= $wpdb->prepare( ' AND ID > %d', $after_user_id );
	};
	add_action( 'pre_user_query', $after_id_where );
	$user_ids = get_users(
		array(
			'fields'      => 'ID',
			'orderby'     => 'ID',
			'order'       => 'ASC',
			'number'      => $batch_size,
			'count_total' => false,
		)
	);
	remove_action( 'pre_user_query', $after_id_where );

	foreach ( $user_ids as $user_id ) {
		bp_notifications_add_notification(
			array(
				'user_id'          => (int) $user_id,
				'item_id'          => $order_id,
				'component_name'   => 'purchase_completed',
				'component_action' => 'send_purchase_notification',
				'date_notified'    => bp_core_current_time(),
				'is_new'           => 1,
			)
		);
	}

	if ( count( $user_ids ) < $batch_size ) {
		return 0;
	}

	$last_user_id = (int) end( $user_ids );
	if ( $queue_next && ! wc4bp_enqueue_purchase_notification_batch( $order_id, $last_user_id ) ) {
		// The next job could not be queued: finish here instead of leaving users without the notification.
		do {
			$last_user_id = wc4bp_send_purchase_notification_batch( $order_id, $last_user_id, false );
		} while ( $last_user_id );
	}

	return $last_user_id;
}
