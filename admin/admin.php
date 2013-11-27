<?php

/**
 * Adding the Admin Page
 * 
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */ 

add_action( 'admin_menu', 'wc4bp_admin_menu' );

function wc4bp_admin_menu() {
    add_menu_page( 'WooCommerce for BuddyPress', 'WC4BP Options', 'edit_posts', 'wc4bp_options_page', 'wc4bp_screen' );
}

/**
 * The Admin Page
 * 
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */ 
 
function wc4bp_screen() { ?>

    <div class="wrap">

        <div id="icon-options-general" class="icon32"><br></div>
        <h2>WooCommerce for BuddyPress</h2>
		
		<p style="margin: 30px 0; font-size: 15px;">
		    Need help? <a class="button secondary" href="http://support.themekraft.com/" target="_blank">Documentation</a> <a class="button secondary" href=/#" target="_blank" title="Submit an email support ticket">Ask Question</a>
		    <span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_blank">Themekraft</a>.</span>
        </p>
		
        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-options' ); ?>
            <?php settings_fields( 'wc4bp_options' ); ?>
            <?php do_settings_sections( 'wc4bp_options' ); ?>
            
        </form>

    </div><?php

}

/**
 * Register the admin settings
 * 
 * @author Sven Lehnert
 * @package TK Loop Designer 
 * @since 1.0
 */ 
 
add_action( 'admin_init', 'wc4bp_register_admin_settings' );

function wc4bp_register_admin_settings() {
	        
    register_setting( 'wc4bp_options', 'wc4bp_options' );
    
    // Settings fields and sections
    add_settings_section(	'section_general'	, ''							, 'wc4bp_general'	, 'wc4bp_options' );
	
	add_settings_field(		'tabs_disabled'	, '<p><b>Disable Shop Profile Tabs</b></p>
												<p>By default all account related WooCommerce pages are included into the BuddyPress member profiles.</p>
												<p><i>You defined these pages in the Page Setup in WooCommerce > Settings > Pages</i></p>'	, 'wc4bp_shop_tabs_disable'	, 'wc4bp_options' , 'section_general' );
	//add_settings_field(		'tabs_rename'	, '<b>Rename Shop Profile Tabs</b>'	, 'wc4bp_shop_tabs_rename'	, 'wc4bp_options' , 'section_general' );
	add_settings_field(		'tabs_add'	, '<b>Add other pages to profiles</b>
											<p>Integrate other pages (for example from other WooCommerce extensions) into your BuddyPress member profiles.</p>
											<p><i>This will redirect the page to the crorect profile page and add a menu item in the profile.<br> </i></p>'	, 'wc4bp_shop_tabs_add'	, 'wc4bp_options' , 'section_general' );

}

/**
 * Important notice on top of the screen
 * 
 * @author Sven Lehnert
 * @package TK Loop Designer 
 * @since 1.0
 */ 
 
function wc4bp_general() {
	
    echo '<p><i> WooCommerce BuddyPress Integration Setup </i></p><br>';
			
}

/**
 * Do you want to use the WordPress Customizer? This is the option to turn on/off the WordPress Customizer Support.   
 * 
 * @author Sven Lehnert 
 * @package TK Loop Designer
 * @since 1.0
 */
 
function wc4bp_shop_tabs_disable(){ 
	$options = get_option( 'wc4bp_options' ); 
	
	// echo '<pre>';
	// print_r($options);
	// echo '</pre>';
	
	$tab_cart_disabled = 0;
	if(isset( $options['tab_cart_disabled']))
		$tab_cart_disabled = $options['tab_cart_disabled'];
	
	$tab_history_disabled = 0;
	if(isset( $options['tab_history_disabled']))
		$tab_history_disabled = $options['tab_history_disabled'];
		
	$tab_track_disabled = 0;
	if(isset( $options['tab_track_disabled']))
		$tab_track_disabled = $options['tab_track_disabled'];
	?>
    <p><input id='checkbox' name='wc4bp_options[tab_cart_disabled]' type='checkbox' value='1' <?php checked( $tab_cart_disabled, 1  ) ; ?> /> <b>Turn off "Cart" tab. </b></p>
	<p><input id='checkbox' name='wc4bp_options[tab_history_disabled]' type='checkbox' value='1' <?php checked( $tab_history_disabled, 1  ) ; ?> /> <b>Turn off "History" tab. </b></p>
	<p><input id='checkbox' name='wc4bp_options[tab_track_disabled]' type='checkbox' value='1' <?php checked( $tab_track_disabled, 1  ) ; ?> /> <b>Turn off "Track my order" tab. </b> </p>
	
	<p><input id='checkbox' name='wc4bp_options[tab_activity_disabled]' type='checkbox' value='1' <?php checked( $tab_activity_disabled, 1  ) ; ?> /> <b>Turn off "Shop" Tab</b> <i>inside</i> "Settings" for the activity stream settings. </p>
	<hr />
	<p><input id='checkbox' name='wc4bp_options[tab_sync_disabled]' type='checkbox' value='1' <?php checked( $tab_sync_disabled, 1  ) ; ?> /> <b>Turn off WooCommerce BuddyPress Profile sync.</b> This will also remove the Billing Address - Shipping Address Tabs from Profile/Edit. </p>
	
	<?php
	
	submit_button(); 

}
function wc4bp_shop_tabs_rename(){ 
	$options = get_option( 'wc4bp_options' );
	
	$shop_main_nav = '';
	if(isset( $options['shop_main_nav']))
		$shop_main_nav = $options['shop_main_nav'];
	
	$cart_sub_nav = '';
	if(isset( $options['cart_sub_nav']))
		$cart_sub_nav = $options['cart_sub_nav'];
	
	$history_sub_nav = '';
	if(isset( $options['history_sub_nav']))
		$history_sub_nav = $options['history_sub_nav'];
	
	$track_sub_nav = '';
	if(isset( $options['track_sub_nav']))
		$track_sub_nav = $options['track_sub_nav']; ?>
	
	<p><b>Raname Shop main Tab:</b><input id='text' name='wc4bp_options[shop_main_nav]' type='text' value='<?php echo $shop_main_nav; ?>' /></p>
	<p><b>Raname Shopping Cart:</b><input id='text' name='wc4bp_options[cart_sub_nav]' type='text' value='<?php echo $cart_sub_nav; ?>' /></p>
	<p><b>Raname History:</b><input id='text' name='wc4bp_options[history_sub_nav]' type='text' value='<?php echo $history_sub_nav; ?>' /></p>
	<p><b>Raname Track your order:</b><input id='text' name='wc4bp_options[track_sub_nav]' type='text' value='<?php echo $track_sub_nav; ?>' /></p>
	
	<?php

	submit_button(); 

}
function wc4bp_shop_tabs_add(){
	wc4bp_get_forms_table();
	
}

function wc4bp_get_forms_table() {
	$options = get_option( 'wc4bp_options' );
	
	// echo '<pre>';
	// print_r($options);
	// echo '</pre>';
	?>
	 <style type="text/css">
	 .wc4bp_editinline{
	 	color: #bc0b0b;
	 	cursor: pointer;
	 }
		table #the-list tr .wc4bp-row-actions { opacity:0 }
		table #the-list tr:hover .wc4bp-row-actions { opacity:1 }
				
    </style>
	<?php wc4bp_thickbox_page_form(); ?>
	<table class="wp-list-table widefat fixed posts">
		
		<thead>
			<tr>
				<th scope="col" id="name" class="manage-column column-comment column-n" style="">Page</th>
				<th scope="col" id="slug" class="manage-column column-description" style="">Tab Name</th>
				<th scope="col" id="attached-post-type" class="manage-column column-status" style="">Position</th>
				<th scope="col" id="attached-page" class="manage-column column-status" style="">Main Nav?</th>
			</tr>
		</thead>
		<tbody id="the-list">
		<?php
		if(isset($options['selected_pages']) && is_array($options['selected_pages'])){
			foreach ($options['selected_pages'] as $key => $attached_page) { ?>
				<tr id="post-<?php echo $key ?>" class="post-<?php echo $key ?> type-page status-publish hentry alternate iedit author-self wc4bp_tr" valign="bottom">	
					<td class="slug column-slug">
						<?php echo  get_the_title($attached_page['page_id']); ?>
						<div class="wc4bp-row-actions">
							<span class="wc4bp_inline hide-if-no-js">
								<input id="<?php echo $attached_page['tab_slug'] ?>" alt="#TB_inline?height=300&amp;width=400&amp;inlineId=add_page" title="an existing page to your BuddyPress member profiles" class="thickbox_edit wc4bp_editinline cptfbp_thickbox" type="button" value="Edit" />
							</span>
							<span class="trash">
								<span id="<?php echo $key ?>" class="wc4bp_delete_page" title="Delete this item">Delete</span>
							</span>
						</div>
					</td>
					<td class="slug column-slug">
						<?php echo isset($attached_page['tab_name']) ? $attached_page['tab_name']: '--'; ?>
					</td>
					<td class="slug column-slug">
						<?php echo isset($attached_page['position']) ? $attached_page['position']: '--'; ?>
					</td>
					<td class="slug column-slug">
						<?php echo isset($attached_page['position']) ? 'Yes': 'No'; ?>
					</td>
				</tr>
			<?php
		 	}
		} 
	
	echo '</tbody></table>';
}

function wc4bp_thickbox_page_form(){
	$options = get_option( 'wc4bp_options' ); ?>
	
	<div style="margin: 0 0 20px 0;"> 
		<input alt="#TB_inline?height=300&amp;width=400&amp;inlineId=add_page" title="Add an existing page to your BuddyPress member profiles" class="button button-secondary cptfbp_thickbox cptfbp_thickbox_add " type="button" value="Add a page to your BuddyPress Member Profiles" />  
	</div>
	<div id="add_page" style="display:none"></div>

	<?php
}

function wc4bp_add_edit_entry_form($edit = ''){
	
	$wc4bp_page_id	= '';	
	$tab_name 		= '';
	$position		= '';
	$main_nav		= '';
	
	if(isset($_POST['wc4bp_tab_slug']))
		$wc4bp_tab_slug = $_POST['wc4bp_tab_slug'];

	$options = get_option( 'wc4bp_options' );
	
	if(isset($wc4bp_tab_slug)){
		
		if(isset( $options['selected_pages'][$wc4bp_tab_slug]['tab_name']))
			$tab_name = $options['selected_pages'][$wc4bp_tab_slug]['tab_name'];

		if(isset( $options['selected_pages'][$wc4bp_tab_slug]['position']))
			$position = $options['selected_pages'][$wc4bp_tab_slug]['position'];

		if(isset( $options['selected_pages'][$wc4bp_tab_slug]['main_nav']))
			$main_nav = $options['selected_pages'][$wc4bp_tab_slug]['main_nav'];
		
		if(isset( $options['selected_pages'][$wc4bp_tab_slug]['page_id']))
			$page_id = $options['selected_pages'][$wc4bp_tab_slug]['page_id'];
			
	}
//	echo $wc4bp_page_id;
	$args = array( 
		'echo' => true,
		'sort_column'  => 'post_title',
		'show_option_none' => __( 'none', 'wc4bp' ),
		'name' => "wc4bp_page_id",
		'class' => 'postform',
		'selected' => $page_id
	); ?>
	
	<p><b>Choose an existing page</b><br>
	<?php wp_dropdown_pages($args); ?></p>

	<p><b>Tab Name</b><br>
		<?php echo '--'.$wc4bp_tab_slug; ?>
	<input id='wc4bp_tab_name' name='wc4bp_tab_name' type='text' value='<?php echo $tab_name ?>' /></p>
	<p><b>Position</b><br>
	<small><i>Just enter a number like 1, 2, 3..</i></small><br>
	<input id='wc4bp_position' name='wc4bp_position' type='text' value='<?php echo $position ?>' /></p>
	<p><input id='wc4bp_main_nav' name='wc4bp_main_nav' type='checkbox' value='1'/>&nbsp;<b>Top Level Nav?</b></p> 
	
	<?php if(isset($wc4bp_tab_slug)) ?>
		<input type="hidden" id="wc4bp_tab_slug" value="<?php echo $wc4bp_tab_slug ?>" />
	
	<input type="button" value="Save" name="add_cpt4bp_page" class="button add_cpt4bp_page btn">
	<?php
}
?>