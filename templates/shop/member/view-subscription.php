<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Victor Marin
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
?>
<div id="item-body" role="main">
    <?php do_action( 'wc4bp_before_checkout_body' ); ?>

    <?php echo do_shortcode( '[woo_subscriptions_view_page]' ); ?>

    <?php do_action( 'wc4bp_after_checkout_body' ); ?>

</div><!-- #item-bo