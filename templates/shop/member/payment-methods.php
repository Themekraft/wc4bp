<?php
/**
 * @package         WordPress
 * @subpackage      BuddyPress, WooCommerce
 * @author          GFireM
 * @copyright       2017, Themekraft
 * @link            https://github.com/Themekraft/BP-Shop-Integration
 * @license         http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
?>
<div class="entry-content">
    <div id="item-body" role="main">
        <div class="woocommerce">
			<?php do_action( 'wc4bp_before_payment_methods_body' ); ?>
			<?php echo do_shortcode( '[payment-methods]' ); ?>
			<?php do_action( 'wc4bp_after_payment_methods_body' ); ?>
        </div>
    </div>
</div>
