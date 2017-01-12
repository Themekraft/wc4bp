<?php
/**
 * Admin View: Template pages
 *
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