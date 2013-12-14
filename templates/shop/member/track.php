<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

?>
<div id="item-body" role="main">

	<?php do_action( 'bpshop_before_track_body' ); ?>

	<h3><?php _e( 'Track your order', 'bpshop' ); ?></h3>

	<?php do_action( 'bpshop_after_track_heading' ); ?>

	<form action="" method="post" class="track_order">
		<?php wp_nonce_field( 'bp-shop_order_tracking' ) ?>

		<p><?php _e('To track your order please enter your Order ID in the box below and press return. This was given to you on your receipt and in the confirmation email you should have received.', 'bpshop'); ?></p>

		<p class="form-row form-row-first"><label for="orderid"><?php _e('Order ID', 'bpshop'); ?></label> <input class="input-text" type="text" name="orderid" id="orderid" placeholder="<?php _e('Found in your order confirmation email.', 'bpshop'); ?>" /></p>
		<p class="form-row form-row-last"><label for="order_email"><?php _e('Billing Email', 'bpshop'); ?></label> <input class="input-text" type="text" name="order_email" id="order_email" placeholder="<?php _e('Email you used during checkout.', 'bpshop'); ?>" /></p>
		<div class="clear"></div>

		<p class="form-row">
			<input type="submit" class="button" name="track" value="<?php _e('Track', 'bpshop'); ?>" />
		</p>

	</form>

	<?php do_action( 'bpshop_after_track_body' ); ?>

</div><!-- #item-body -->