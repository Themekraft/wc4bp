<?php
/**
 * Admin View: Template pages
 *
 */
?>

<p>TATIBy default all account related WooCommerce pages are included into the BuddyPress member profiles.</p>

<p><input name='wc4bp_options[tab_cart_disabled]' type='checkbox'
          value='1' <?php checked( $tab_cart_disabled, 1 ); ?> /> <b>Turn off "Cart" tab. </b></p>
<p><input name='wc4bp_options[tab_checkout_disabled]' type='checkbox'
          value='1' <?php checked( $tab_checkout_disabled, 1 ); ?> /> <b>Turn off "Checkout" tab. </b></p>
<p><input name='wc4bp_options[tab_history_disabled]' type='checkbox'
          value='1' <?php checked( $tab_history_disabled, 1 ); ?> /> <b>Turn off "History" tab. </b></p>
<p><input name='wc4bp_options[tab_track_disabled]' type='checkbox'
          value='1' <?php checked( $tab_track_disabled, 1 ); ?> /> <b>Turn off "Track my order" tab. </b></p>