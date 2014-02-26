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

	<?php if( wc4bp_is_subpage( 'checkout' ) ){  ?>
        <?php echo do_shortcode( '[woocommerce_checkout]' ); ?>
    <?php } else{ ?>
	    <?php echo do_shortcode( '[woocommerce_cart]' ); ?>
	<?php } ?>

	<?php do_action( 'wc4bp_after_cart_body' ); ?>

</div><!-- #item-body -->