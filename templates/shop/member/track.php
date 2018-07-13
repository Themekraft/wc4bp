<?php
/**
 * @package        WordPress
 * @subpackage    BuddyPress, WooCommerce
 * @author        Boris Glumpler
 * @copyright    2011, Themekraft
 * @link        https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

?>
<div class="entry-content">
    <div id="item-body" role="main">
        <div class="woocommerce">
			<?php do_action( 'wc4bp_before_track_body' ); ?>

            <h3><?php _e( 'Track your order', 'wc4bp' ); ?></h3>

			<?php do_action( 'wc4bp_after_track_heading' ); ?>

			<?php echo do_shortcode( '[woocommerce_order_tracking]' ); ?>

			<?php do_action( 'wc4bp_after_track_body' ); ?>
        </div>
    </div><!-- #item-body -->
</div>
