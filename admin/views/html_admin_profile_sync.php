<?php
/**
 * Admin View: Template pages
 *
 */

?>

<p><?php _e('If you disable profile sync, the billing and shipping profile groups will be deleted.', 'wc4bp'); ?></p>
<p>
	<i><?php _e('This will also remove the Billing Address - Shipping Address Tabs from Profile/Edit and disable all sync settings', 'wc4bp'); ?> </i>
</p>
<p><input name='wc4bp_options[tab_sync_disabled]' type='checkbox' value='1' <?php checked( $tab_sync_disabled, 1 ); ?> />
<p><?php _e('Turn off WooCommerce BuddyPress Profile Sync.', 'wc4bp'); ?></p>
</p>
