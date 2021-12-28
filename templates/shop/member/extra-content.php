<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

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
			<?php do_action( 'wc4bp_before_extra_content_body' ); ?>
            <div style="overflow: auto; margin: 15px auto; max-width: 1000px;">
				<iframe id="myIframe" scrolling="no" src="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" style="border: 0px none; margin-left: -10px; height: 1000px; margin-top: -100px; width: 950px;">
				</iframe>
			</div>
			<?php do_action( 'wc4bp_after_extra_content_body' ); ?>
        </div>
    </div>
</div>
