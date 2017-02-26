<input id="continue_update_paged" type="hidden" value="<?php echo $paged ?>">
<p>
	<?php _e( 'If the Update stops for some reason you can continue the update manually', 'wc4bp' ); ?>
</p>
<input type="button" id="continue_update" value="Continue Updating from here">
<table class="wp-list-table widefat fixed users">
    <thead>
    <tr>
        <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
            <label class="screen-reader-text" for="cb-select-all-1"><?php _e( "Select All", "wc4bp" ); ?></label>
            <input id="cb-select-all-1" type="checkbox">
        </th>
        <th scope="col" id="username" class="manage-column column-username sortable desc" style="">
            <span><?php _e( 'Username', 'wc4bp' ); ?></span>
        </th>
        <th scope="col" id="name" class="manage-column column-name sortable desc" style=""><a
            <span><?php _e( 'Name', 'wc4bp' ); ?></span>
        </th>
        <th scope="col" id="email" class="manage-column column-email sortable desc" style="">
            <span><?php _e( 'E-mail', 'wc4bp' ); ?></span>
        </th>
        <th scope="col" id="role" class="manage-column column-role" style="">
			<?php _e( 'Role', 'wc4bp' ); ?>
        </th>
    </tr>
    </thead>

    <tbody id="result" data-wp-lists="list:user">
	
	<?php
	foreach ( $query as $q ) {
		if ( $update_type == 'wc4bp_sync_wc_user_with_bp_ajax' ) {
			$this->wc4bp_sync_from_admin( $q->ID );
		}
		if ( $update_type == 'wc4bp_set_bp_field_visibility' ) {
			$this->wc4bp_change_xprofile_visibility_by_user_ajax( $q->ID );
		}
		?>

        <tr id="user-1" class="alternate">
            <th scope="row" class="check-column">
                <label class="screen-reader-text" for="cb-select-1"><?php _e( "Select admin", "wc4bp" ); ?></label>
                <input type="checkbox" name="users[]" id="user_1" class="administrator" value="1">
            </th>
            <td class="username column-username"><?php echo get_avatar( $q->ID, 40 ); ?> <strong>
					<?php echo get_the_author_meta( 'user_nicename', $q->ID ); ?>
                </strong><br>
                <div class="row-actions"></div>
            </td>
            <td class="name column-name">
				<?php echo get_the_author_meta( 'display_name', $q->ID ); ?>
            </td>
            <td class="email column-email">
                <a href="<?php echo get_the_author_meta( 'user_email', $q->ID ); ?>" title="E-mail: <?php echo get_the_author_meta( 'user_email', $q->ID ); ?>">
					<?php echo get_the_author_meta( 'user_email', $q->ID ); ?>
                </a>
            </td>
            <td class="role column-role">
				<?php echo implode( ',', get_the_author_meta( 'roles', $q->ID ) ); ?>
            </td>
        </tr>
	<?php } ?>
    </tbody>
</table>

<?php
die();