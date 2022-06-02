<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<p>
	<b><?php esc_html_e( 'Sync WooCommerce Customer Billing and Shipping Address with BuddyPress.', 'wc4bp' ); ?></b>
</p>
<p>
	<?php esc_html_e( 'The billing and shipping address fields are WooCommerce default user fields. You can sync all default WooCommerce customer fields with BuddyPress.', 'wc4bp' ); ?>
</p>
<p>
	<?php esc_html_e( 'During checkout or if a user edits his profile all fields will be synced automatically.', 'wc4bp' ); ?>
</p>
<p>
	<?php esc_html_e( 'If you have already customers and start using WooBuddy -> WooCommerce BuddyPress Integration on an existing store, you need to sync your user once.', 'wc4bp' ); ?>
</p>
<p>
	<?php esc_html_e( 'The Customer Billing and Shipping Address fields will be created in BuddyPress during the plugin installation, but the user sync can take a while depends on the size of your user base and needs to be done once after the first install.', 'wc4bp' ); ?>
</p>

<br>
<a onclick="document.getElementById('wc_default_fields').style.display='';return false;" href="" style="text-decoration:none;border-bottom:1px dotted blue;">
	<?php esc_html_e( ' Default WooCommerce Checkout Fields', 'wc4bp' ); ?>
</a>
<br/>

<div id="wc_default_fields" style="display:none;margin:15px 15px 0px 15px;padding:5px;border:1px solid #aaa;">
	<b><p><?php esc_html_e( 'Customer Billing Address', 'wc4bp' ); ?></p></b>
	<ul>
		<li><?php esc_html_e( 'First name', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Last name', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Company', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Address 1', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Address 2', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'City', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Postcode', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'State/County ', 'wc4bp' ); ?><i><?php esc_html_e( '(Country or state code)', 'wc4bp' ); ?></i></li>
		<li><?php esc_html_e( 'Country(2 letter Country code)', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Telephone', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Email', 'wc4bp' ); ?></li>
	</ul>
	<b><p><?php esc_html_e( 'Customer Shipping Address', 'wc4bp' ); ?></p></b>
	<ul>
		<li><?php esc_html_e( 'First name', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Last name', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Company', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Address 1', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Address 2', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'City', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'Postcode', 'wc4bp' ); ?></li>
		<li><?php esc_html_e( 'State/County ', 'wc4bp' ); ?><i><?php esc_html_e( '(Country or state code)', 'wc4bp' ); ?></i></li>
		<li><?php esc_html_e( 'Country(2 letter Country code)', 'wc4bp' ); ?></li>
	</ul>
	<a onclick="document.getElementById('div_name2').style.display='none';return false;" href="" style="text-decoration:none;border-bottom:1px dotted blue;">hide</a>
</div>

<br>
<input type="button" id="wc4bp_sync_wc_user_with_bp_ajax" name="wc4bp_options_sync[wc_bp_sync]" class="button wc_bp_sync_all_user_data" value="<?php esc_html_e( 'Sync Now', 'wc4bp' ); ?>">
