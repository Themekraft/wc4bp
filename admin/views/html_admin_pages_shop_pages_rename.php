<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
?>

<p><b><?php _e('Rename Shop Parent Tab:', 'wc4bp'); ?></b><input id='text' name='wc4bp_options[shop_main_nav]' type='text' value='<?php echo $shop_main_nav; ?>'/></p>
<p><b><?php _e('Rename Shopping Cart:', 'wc4bp'); ?></b><input id='text' name='wc4bp_options[cart_sub_nav]' type='text' value='<?php echo $cart_sub_nav; ?>'/></p>
<p><b><?php _e('Rename History:', 'wc4bp'); ?></b><input id='text' name='wc4bp_options[history_sub_nav]' type='text' value='<?php echo $history_sub_nav; ?>'/></p>
<p><b><?php _e('Rename Track your order:', 'wc4bp'); ?></b><input id='text' name='wc4bp_options[track_sub_nav]' type='text' value='<?php echo $track_sub_nav; ?>'/></p>

submit_button();