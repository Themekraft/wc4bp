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
			<?php do_action( 'wc4bp_before_extra_content_body' ); ?>
		  <div id="wc4bp-hidden-content" style="display:none;"></div>
		  <div id="extra-content-tab"></div>
		  <div id="extra-content-complement" style="display:none;"></div>
			<?php do_action( 'wc4bp_after_extra_content_body' ); ?>
	   </div>
	</div>
</div>
