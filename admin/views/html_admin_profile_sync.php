<?php
/**
 * Admin View: Template pages
 *
 */

?>

<p>If you disable profile sync, the billing and shipping profile groups will be deleted.</p>
<p><i>This will also remove the Billing Address - Shipping Address Tabs from Profile/Edit and disable all sync
		settings</i></p>
<p><input name='wc4bp_options[tab_sync_disabled]' type='checkbox'
          value='1' <?php checked( $tab_sync_disabled, 1 ); ?> /> <b>Turn off WooCommerce BuddyPress Profile
		Sync.</b></p>
