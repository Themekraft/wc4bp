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

	<?php do_action( 'bpshop_before_member_body' ); ?>

	<div class="item-list-tabs no-ajax" id="subnav">
		<ul>
			<?php bp_get_options_nav(); ?>
			<?php do_action( 'bpshop_member_options_nav' ); ?>
		</ul>
	</div><!-- .item-list-tabs -->

	<h3><?php _e( 'Track your order', 'bpshop' ); ?></h3>

	<?php echo do_shortcode( '[woocommerce_order_tracking]' ); ?>

	<?php do_action( 'bpshop_after_member_body' ); ?>

</div><!-- #item-body -->