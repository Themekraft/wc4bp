<p>
    <b><?php _e( 'Choose an existing page', 'wc4bp' ); ?></b>
    <br>
	<?php wp_dropdown_pages( $args ); ?>
    <input id='wc4bp_children' name='wc4bp_children' type='checkbox' value='1' <?php checked( $children, 1 ); ?> />&nbsp;
    <b><?php _e( 'Include Children?', 'wc4bp' ); ?></b>
</p>
<p>
    <b><?php _e( 'Tab Name', 'wc4bp' ); ?></b>
    <i><?php _e( 'If empty same as Pagename', 'wc4bp' ); ?></i>
    <br>
    <input id='wc4bp_tab_name' name='wc4bp_tab_name' type='text' value='<?php echo esc_attr( $tab_name ) ?>'/>
</p>
<p>
    <b><?php _e( 'Position', 'wc4bp' ); ?></b>
    <br>
    <small><i><?php _e( 'Just enter a number like 1, 2, 3..', 'wc4bp' ); ?></i></small>
    <br>
    <input id='wc4bp_position' name='wc4bp_position' type='text' value='<?php echo esc_attr( $position ) ?>'/>
</p>
<?php if ( isset( $wc4bp_tab_slug ) ) {
	echo '<input type="hidden" id="wc4bp_tab_slug" value="' . esc_attr( $wc4bp_tab_slug ) . '" />';
} ?>

<input type="button" value="<?php _e( 'Save', 'wc4bp' ); ?>" name="add_cpt4bp_page" class="button add_cpt4bp_page btn">