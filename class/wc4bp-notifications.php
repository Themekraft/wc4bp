<?php
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
		if( ! empty( $item_id ) ){
			$order         = wc_get_order( $item_id );
            if ( empty( $order ) ) {
                $message       = __( 'A user bought a product','wc4bp' );
                return $message;
            }
			$names         = array();
			$message       = __( 'The user %s has bought %s','wc4bp' );
            $user_link = bp_core_get_userlink( $order->get_customer_id() );
			foreach( $order->get_items() as $item_id => $item ){
                $product = $item->get_product();
                if ( ! $product instanceof WC_Product ) {
                    continue;
                }
				$names[]   = '<a href="' . $item->get_product()->get_permalink() . '">' . $item->get_product()->get_name() . '</a>';
			}
			$notification  = sprintf( $message,$user_link, implode( ', ',$names ) );
		}
    }
	return $notification;
}

/**
 * Firing the new notification
 *
 * @author Romeli
 */
add_action( 'woocommerce_order_status_changed', 'wc4bp_send_purchase_notification', 99, 4 );
function wc4bp_send_purchase_notification( $order_id ) {
    try {
        $send_notifications = get_option( 'wc4bp_options_notifications' );
        if( array_key_exists( 'notifications_settings', $send_notifications ) && $send_notifications['notifications_settings'] === '1' ){
            $order_status = $send_notifications['notifications_order_status'];
            if( ! isset( $order_status) || empty( $order_status )){
                $order_status = 'completed';
            }
            $notification = array();
            $users        = get_users();
            $order        = new WC_Order( $order_id );
            $current_order_status = $order->get_status();
            if( $order instanceof WC_Order ){
                $item_id  = $order->get_id();
                if ( $current_order_status != $order_status ) {
                    return false;
                }
                foreach ( $users as $user ) {
                    $notification=bp_notifications_add_notification( array(
                        'user_id'           => $user->ID,
                        'item_id'           => $item_id,
                        'component_name'    => 'purchase_completed',
                        'component_action'  => 'send_purchase_notification',
                        'date_notified'     => bp_core_current_time(),
                        'is_new'            => 1,
                    ) );
                }
            }
        }
        else{
            return false;
        }
    } catch ( Exception $exception ) {
        WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
    }
}
