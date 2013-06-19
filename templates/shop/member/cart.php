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

	<?php do_action( 'bpshop_before_cart_body' ); ?>

	<?php
	if( bpshop_is_subpage( 'checkout' ) ) :
		if( bpshop_is_subsubpage( 'pay' ) ) :
			bpshop_load_template( 'shop/member/checkout/pay'	 );

		elseif( bpshop_is_subsubpage( 'thanks' ) ) :
			bpshop_load_template( 'shop/member/checkout/thanks'  );

		else :
			bpshop_load_template( 'shop/member/checkout/general' );

		endif;
	else :
	?>
		<h3><?php _e( 'Shopping Cart', 'bpshop' ); ?></h3>
		<?php echo do_shortcode( '[woocommerce_cart]' ); ?>
	<?php endif; ?>

	<?php do_action( 'bpshop_after_cart_body' ); ?>

</div><!-- #item-body -->