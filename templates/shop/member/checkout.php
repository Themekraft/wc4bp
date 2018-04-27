<?php
/**
 * @package        WordPress
 * @subpackage    BuddyPress, WooCommerce
 * @author        Kishore Sahoo
 * @copyright    2011, Themekraft
 * @link        https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
?>
<div class="entry-content">
    <div id="item-body" role="main">
        <div class="woocommerce">
			<?php do_action( 'wc4bp_before_checkout_body' ); ?>

			<?php echo do_shortcode( '[woocommerce_checkout]' ); ?>

			<?php do_action( 'wc4bp_after_checkout_body' ); ?>
        </div>
    </div><!-- #item-body -->
</div>
