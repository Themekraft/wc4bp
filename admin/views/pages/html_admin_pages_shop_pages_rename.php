<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<p><b><?php esc_html_e( 'Rename Shop Parent Tab:', 'wc4bp' ); ?></b><input id='text' name='wc4bp_options[shop_main_nav]' type='text' value='<?php echo esc_attr( $shop_main_nav ); ?>'/></p>
<p><b><?php esc_html_e( 'Rename Shopping Cart:', 'wc4bp' ); ?></b><input id='text' name='wc4bp_options[cart_sub_nav]' type='text' value='<?php echo esc_attr( $cart_sub_nav ); ?>'/></p>
<p><b><?php esc_html_e( 'Rename History:', 'wc4bp' ); ?></b><input id='text' name='wc4bp_options[history_sub_nav]' type='text' value='<?php echo esc_attr( $history_sub_nav ); ?>'/></p>
<p><b><?php esc_html_e( 'Rename Track your order:', 'wc4bp' ); ?></b><input id='text' name='wc4bp_options[track_sub_nav]' type='text' value='<?php echo esc_attr( $track_sub_nav ); ?>'/></p>

submit_button();
