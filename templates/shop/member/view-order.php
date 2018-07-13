<?php
/**
 * @package         WordPress
 * @subpackage      BuddyPress, WooCommerce
 * @author          GFireM
 * @copyright       2017, Themekraft
 * @link            https://github.com/Themekraft/BP-Shop-Integration
 * @license         http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
/** @var WP_Post $post */
global $bp, $wp_query, $post;
$post->post_name     = 'view-order';
$post->post_title    = 'Order Details';
$bp_action_variables = $bp->action_variables;
if ( ! empty( $bp_action_variables ) ) {
	if ( isset( $bp_action_variables[0] ) && ! empty( $bp_action_variables[1] ) && 'view-order' === $bp_action_variables[0] && is_numeric( $bp_action_variables[1] ) ) {
		$order_id = absint( $bp_action_variables[1] );
		echo '<div class="woocommerce">';
		woocommerce_account_view_order( $order_id );
		echo '</div>';
	}
} else {
	echo esc_attr( sprintf( '<div class="woocommerce-error">%s</div>', __( 'Please enter a valid order ID', 'wc4bp' ) ) );
}
