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
<?php 

  ?>
    <script>
        jQuery( document ).ready(function() {
            jQuery("#wc4bp-extra-content-frame").load(function(){
              var woocommerceContent = jQuery("#wc4bp-extra-content-frame").contents().find(".woocommerce")
              jQuery("#wc4bp-extra-content-frame").contents().find('body').html(woocommerceContent);
              jQuery("#wc4bp-extra-content-frame").contents().find('.wc-MyAccount-navigation-heading').hide();
              jQuery("#wc4bp-extra-content-frame").contents().find('.bsMyAccount').css('border','none');
            });
    });
    </script>
  <?php

?>

<div class="entry-content">
    <div id="item-body" role="main">
        <div class="woocommerce">
			<?php do_action( 'wc4bp_before_extra_content_body' ); ?>
            <div style="overflow: auto; max-width: 1000px;">
				<iframe id="wc4bp-extra-content-frame" scrolling="no" src="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" style="border: none; margin-top: -20px; height: 1000px; width: 950px;">
				</iframe>
			</div>
			<?php do_action( 'wc4bp_after_extra_content_body' ); ?>
        </div>
    </div>
</div>
