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
			<?php do_action( 'wc4bp_before_edit_address_body' ); ?>
			<?php echo do_shortcode( '[edit-address]' ); ?>
			<?php do_action( 'wc4bp_after_edit_address_body' ); ?>
        </div>
    </div>
</div>
