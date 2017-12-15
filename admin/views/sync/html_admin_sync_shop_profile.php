<p>
    <b><?php _e( 'Sync WooCommerce Customer Billing and Shipping Address with BuddyPress.', 'wc4bp' ); ?></b>
</p>
<p>
	<?php _e( 'The billing and shipping address fields are WooCommerce default user fields. You can sync all default WooCommerce
            customer fields with BuddyPress.', 'wc4bp' ); ?>
</p>
<p>
	<?php _e( 'During checkout or if a user edits his profile all fields will be synced automatically.', 'wc4bp' ); ?>
</p>
<p>
	<?php _e( 'If you have already customers and start using WooCommerce BuddyPress Integration on an existing store, you need 
            to sync your user once.', 'wc4bp' ); ?>
</p>
<p>
	<?php _e( 'The Customer Billing and Shipping Address fields will be created in BuddyPress during the plugin installation,
            but the user sync can take a while depends on the size of your user base and needs to be done once after the
            first install.', 'wc4bp' ); ?>
</p>

<br>
<a onclick="document.getElementById('wc_default_fields').style.display='';return false;" href="" style="text-decoration:none;border-bottom:1px dotted blue;">
	<?php _e( ' Default WooCommerce Checkout Fields', 'wc4bp' ); ?>
</a>
<br/>

<div id="wc_default_fields" style="display:none;margin:15px 15px 0px 15px;padding:5px;border:1px solid #aaa;">
    <b><p><?php _e( 'Customer Billing Address', 'wc4bp' ); ?></p></b>
    <ul>
        <li><?php _e( 'First name', 'wc4bp' ); ?></li>
        <li><?php _e( 'Last name', 'wc4bp' ); ?></li>
        <li><?php _e( 'Company', 'wc4bp' ); ?></li>
        <li><?php _e( 'Address 1', 'wc4bp' ); ?></li>
        <li><?php _e( 'Address 2', 'wc4bp' ); ?></li>
        <li><?php _e( 'City', 'wc4bp' ); ?></li>
        <li><?php _e( 'Postcode', 'wc4bp' ); ?></li>
        <li><?php _e( 'State/County ', 'wc4bp' ); ?><i><?php _e( '(Country or state code)', 'wc4bp' ); ?></i></li>
        <li><?php _e( 'Country(2 letter Country code)', 'wc4bp' ); ?></li>
        <li><?php _e( 'Telephone', 'wc4bp' ); ?></li>
        <li><?php _e( 'Email', 'wc4bp' ); ?></li>
    </ul>
    <b><p><?php _e( 'Customer Shipping Address', 'wc4bp' ); ?></p></b>
    <ul>
        <li><?php _e( 'First name', 'wc4bp' ); ?></li>
        <li><?php _e( 'Last name', 'wc4bp' ); ?></li>
        <li><?php _e( 'Company', 'wc4bp' ); ?></li>
        <li><?php _e( 'Address 1', 'wc4bp' ); ?></li>
        <li><?php _e( 'Address 2', 'wc4bp' ); ?></li>
        <li><?php _e( 'City', 'wc4bp' ); ?></li>
        <li><?php _e( 'Postcode', 'wc4bp' ); ?></li>
        <li><?php _e( 'State/County ', 'wc4bp' ); ?><i><?php _e( '(Country or state code)', 'wc4bp' ); ?></i></li>
        <li><?php _e( 'Country(2 letter Country code)', 'wc4bp' ); ?></li>
    </ul>
    <a onclick="document.getElementById('div_name2').style.display='none';return false;" href="" style="text-decoration:none;border-bottom:1px dotted blue;">hide</a>
</div>

<br>
<input type="button" id="wc4bp_sync_wc_user_with_bp_ajax" name="wc4bp_options_sync[wc_bp_sync]" class="button wc_bp_sync_all_user_data" value="<?php _e( "Sync Now", "wc4bp" ); ?>">
