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

	<?php do_action( 'wc4bp_before_cart_body' ); ?>

	<?php
	if(  wc4bp_is_subpage( 'checkout' ) ) :
		if(  wc4bp_is_subsubpage( 'pay' ) ) :
			 wc4bp_load_template( 'shop/member/checkout/pay'	 );

		elseif(  wc4bp_is_subsubpage( 'thanks' ) ) :
			 wc4bp_load_template( 'shop/member/checkout/thanks'  );

		else :
			 wc4bp_load_template( 'shop/member/checkout/general' );

		endif;
	else :
	?>
		<h3><?php _e( 'Shopping Cart', 'wc4bp' ); ?></h3>
		<?php echo do_shortcode( '[woocommerce_cart]' ); ?>
	<?php endif; ?>

	<?php do_action( 'wc4bp_after_cart_body' ); ?>

</div><!-- #item-body -->