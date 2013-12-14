<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

global $woocommerce;

?>
<h3><?php _e( 'Purchase History', 'bpshop' ); ?></h3>
<?php
$woocommerce->show_messages();
	 	
$customer_orders = get_posts( array(
    'numberposts'     => -1,
    'meta_key'        => '_customer_user',
    'meta_value'      => get_current_user_id(),
    'post_type'       => 'shop_order',
    'post_status'     => 'publish' 
) );

if( count( $customer_orders ) > 0 ) :
?>
<table class="shop_table my_account_orders">
	<thead>
		<tr>
			<th><span class="nobr"><?php _e( '#', 'bpshop' ); ?></span></th>
			<th><span class="nobr"><?php _e( 'Date', 'bpshop' ); ?></span></th>
			<th><span class="nobr"><?php _e( 'Ship to', 'bpshop' ); ?></span></th>
			<th><span class="nobr"><?php _e( 'Total', 'bpshop' ); ?></span></th>
			<th colspan="2"><span class="nobr"><?php _e( 'Status', 'bpshop' ); ?></span></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $customer_orders as $customer_order ) :
		    
        $order = new WC_Order();
        $order->populate( $customer_order );
		?>
		<tr class="order">
			<td><?php echo $order->id; ?></td>
			<td><time title="<?php echo date_i18n(get_option( 'date_format' ) .' '. get_option( 'time_format' ), strtotime($order->order_date)); ?>"><?php echo date_i18n( get_option( 'date_format' ) .' '. get_option( 'time_format' ), strtotime( $order->order_date ) ); ?></time></td>
			<td><address><?php if( $order->formatted_shipping_address ) echo $order->formatted_shipping_address; else echo '&ndash;'; ?></address></td>
			<td><?php echo woocommerce_price( $order->order_total ); ?></td>
			<td><?php echo $order->status; ?></td>
			<td style="text-align:right; white-space:nowrap;">
				<?php if( $order->status == 'pending' ) : ?>
					<a href="<?php echo $order->get_checkout_payment_url(); ?>" class="button pay"><?php _e( 'Pay', 'bpshop' ); ?></a>
					<a href="<?php echo $order->get_cancel_order_url(); ?>" class="button cancel"><?php _e( 'Cancel', 'bpshop' ); ?></a>
				<?php endif; ?>
				<a href="<?php echo add_query_arg( 'order', $order->id, get_permalink( get_option( 'woocommerce_view_order_page_id' ) ) ); ?>" class="button"><?php _e( 'View', 'bpshop' ); ?></a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else : ?>
	<p><?php _e( 'No recent purchases available.', 'bpshop' ); ?></p>
<?php endif; ?>