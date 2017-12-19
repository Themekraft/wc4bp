<h3><?php _e( 'Add Pages to Member Profiles', 'wc4bp' ); ?></h3>

<p><?php _e( 'Integrate other pages (for example from other WooCommerce extensions) into your BuddyPress member profiles.', 'wc4bp' ); ?></p>
<p><i><?php _e( 'This will redirect the page to the correct profile page and add a menu item in the profile.', 'wc4bp' ); ?></i></p><br>

<?php $this->wc4bp_thickbox_page_form(); ?>
<table class="wp-list-table widefat fixed posts">
    <thead>
    <tr>
        <th scope="col" id="page" class="manage-column column-comment column-n" style=""><?php _e( 'Page', 'wc4bp' ); ?></th>
        <th scope="col" id="children" class="manage-column column-status" style=""><?php _e( 'Including Children?', 'wc4bp' ); ?></th>
        <th scope="col" id="name" class="manage-column column-description" style=""><?php _e( 'Tab Name', 'wc4bp' ); ?></th>
        <th scope="col" id="slug" class="manage-column column-description" style=""><?php _e( 'Tab Slug', 'wc4bp' ); ?></th>
        <th scope="col" id="position" class="manage-column column-status" style=""><?php _e( 'Position', 'wc4bp' ); ?></th>
    </tr>
    </thead>
    <tbody id="the-list">
	<?php
	
	if ( isset( $wc4bp_pages_options['selected_pages'] ) && is_array( $wc4bp_pages_options['selected_pages'] ) ) :
		foreach ( $wc4bp_pages_options['selected_pages'] as $key => $attached_page ):
			?>
            <tr id="post-<?php echo $key ?>" class="post-<?php echo $key ?> type-page status-publish hentry alternate iedit author-self wc4bp_tr" valign="bottom">
                <td class="column-name">
					<?php echo get_the_title( $attached_page['page_id'] ); ?>
                    <div class="wc4bp-row-actions">
                        <span class="wc4bp_inline hide-if-no-js">
                            <input id="<?php echo $attached_page['tab_slug'] ?>" alt="#TB_inline?height=300&amp;width=400&amp;inlineId=add_page" title="<?php _e( 'an existing page to your BuddyPress member profiles', 'wc4bp' ); ?>" class="thickbox_edit wc4bp_editinline cptfbp_thickbox" type="button" value="<?php _e( 'Edit', 'wc4bp' ); ?> "/>
                        </span>
                        <span class="trash">
                            <span id="<?php echo esc_attr( $attached_page['page_id'] ) ?>" class="wc4bp_delete_page" title="<?php _e( 'Delete this item', 'wc4bp' ); ?>"> <?php _e( 'Delete', 'wc4bp' ); ?></span>
                        </span>
                    </div>
                </td>
                <td class="column-slug">
					<?php echo isset( $attached_page['children'] ) && $attached_page['children'] > 0 ? 'Yes' : 'No'; ?>
                </td>
                <td class="slug column-slug">
					<?php echo isset( $attached_page['tab_name'] ) ? $attached_page['tab_name'] : '--'; ?>
                </td>
                <td class="slug column-slug">
					<?php
					$slug = apply_filters( 'editable_slug', $attached_page['tab_slug'], get_post( $attached_page['page_id'] ) );
					echo isset( $attached_page['tab_slug'] ) ? esc_html( $slug ) : '--';
					?>
                </td>
                <td class="slug column-slug">
					<?php echo ! empty( $attached_page['position'] ) ? $attached_page['position'] : '--'; ?>
                </td>
            </tr>
		<?php endforeach; ?>
	<?php endif; ?>
<?php echo '</tbody></table>';