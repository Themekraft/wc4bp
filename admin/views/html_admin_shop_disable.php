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
<p><?php _e('By default all account related WooCommerce pages are included into the BuddyPress member profiles.', 'wc4bp'); ?></p>

<p>
	<input name='wc4bp_options[tab_cart_disabled]' type='checkbox' value='1' <?php checked( $tab_cart_disabled, 1 ); ?> />
	<b><?php _e('Turn off \'Cart\' tab', 'wc4bp'); ?></b>
</p>
<p>
	<input name='wc4bp_options[tab_checkout_disabled]' type='checkbox' value='1' <?php checked( $tab_checkout_disabled, 1 ); ?> />
	<b><?php _e('Turn off \'Checkout\' tab.', 'wc4bp'); ?></b>
</p>
<p>
	<input name='wc4bp_options[tab_history_disabled]' type='checkbox' value='1' <?php checked( $tab_history_disabled, 1 ); ?> />
	<b><?php _e('Turn off \'History\' tab.', 'wc4bp'); ?></b>
</p>
<p>
	<input name='wc4bp_options[tab_track_disabled]' type='checkbox' value='1' <?php checked( $tab_track_disabled, 1 ); ?> />
	<b><?php _e('Turn off \'Track my order\' tab.', 'wc4bp'); ?></b>
</p>