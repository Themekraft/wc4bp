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
        <h2>WooCommerce BuddyPress Integration Settings</h2>
		
			<span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>

        <div style="margin: 30px 0 0 0; background: #f4f4f4; padding: 20px; overflow: auto; border-radius: 6px;">

	
			<div style="float: left; overflow: auto; border-right: 1px solid #ddd; padding: 0 20px 0 0;">
				<h3>Get Support.</h3> 
				<p><a class="button secondary" onClick="script: Zenbox.show(); return false;" class="button secondary"  href="#" title="Submit an email support ticket">Ask Question</a> <a title="View Documentation" target="_new" href="https://themekraft.zendesk.com/hc/en-us/categories/200005301-WooCommerce-BuddyPress-Integration" class="button">Documentation</a> </p>
			</div>	        
			
			<div style="float: left; overflow: auto; padding: 0 20px 0 20px; border-right: 1px solid #ddd;">
		        <h3>Contribute your ideas.</h3>
		        <p>Add ideas and vote in our <a title="Visit Ideas Forums" class="button button-secondary" href="https://themekraft.zendesk.com/hc/communities/public/topics/200001221-WooCommerce-BuddyPress-Integration-Ideas" target="_new">Ideas Forums</a></p>
			</div>	        
			
			<div style="float: left; overflow: auto; padding: 0 20px 0 20px;">
		        <h3>Discuss with others.</h3>
		        <p>Learn, share, discuss. Visit our <a title="Visit Community Members Forums" class="button button-secondary" href="https://themekraft.zendesk.com/hc/communities/public/topics/200001191-WooCommerce-BuddyPress-Integration-Trouble-Shooting" target="_new">Community Forums</a></p>
			</div>	        
			
		</div>
		<br>
		
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
    add_settings_section(	'section_general'	, ''							, ''	, 'wc4bp_options' );
	
	add_settings_field(		'tabs_disabled'	, '<p><b>Remove Shop Tabs</b></p>'	, 'wc4bp_shop_tabs_disable'	, 'wc4bp_options' , 'section_general' );
	//add_settings_field(		'tabs_rename'	, '<b>Rename Shop Profile Tabs</b>'	, 'wc4bp_shop_tabs_rename'	, 'wc4bp_options' , 'section_general' );
	add_settings_field(		'tabs_add'	, '<p><b>Add New Tabs</b></p>'	, 'wc4bp_shop_tabs_add'	, 'wc4bp_options' , 'section_general' );

}

/**
 * Important notice on top of the screen
 * 
 * @author Sven Lehnert
 * @package TK Loop Designer 
 * @since 1.0
 */ 
 
function wc4bp_general() {
	
    echo '<div style="padding: 20px 20px 20px 20px; overflow: auto; border-radius: 6px; background: #d9f4e1; border: 1px solid #abe8c9;">';
    echo '<h2 style="margin-bottom: 20px;">Auto Setup Complete!</h2>';
	echo '<p style="line-height: 180%; font-size: 14px;">
			All <i>account-related WooCommerce pages</i> have been included  
			into your <i>BuddyPress member profiles</i>.
			</p>';
    echo '<h3 style="margin-bottom: 30px; font-size: 15px; ">Below you have some extra settings to add or remove tabs from your member profiles.</h3>';
	echo '<a id="wcbp_tutorial" class="button button-secondary" href="#" style="font-size: 15px; padding: 8px 17px; height: auto;">Show me a tutorial!</a>
			<a id="wcbp_dismiss_setup" class="button button-secondary" href="#" style="font-size: 15px; padding: 8px 17px; height: auto;">Dismiss</a>
			</div>';
			
}

/**
 * Do you want to use the WordPress Customizer? This is the option to turn on/off the WordPress Customizer Support.   
 * 
 * @author Sven Lehnert 
 * @package TK Loop Designer
 * @since 1.0
 */
 
function wc4bp_shop_tabs_disable(){ 
	$wc4bp_options			= get_option( 'wc4bp_options' ); 
	$wc4bp_pages_options	= get_option( 'wc4bp_pages_options' ); 
	
	// echo '<pre>';
	// print_r($wc4bp_pages_options);
	// echo '</pre>';
	
	$tab_cart_disabled = 0;
	if(isset( $wc4bp_options['tab_cart_disabled']))
		$tab_cart_disabled = $wc4bp_options['tab_cart_disabled'];
	
	$tab_history_disabled = 0;
	if(isset( $wc4bp_options['tab_history_disabled']))
		$tab_history_disabled = $wc4bp_options['tab_history_disabled'];
		
	$tab_track_disabled = 0;
	if(isset( $wc4bp_options['tab_track_disabled']))
		$tab_track_disabled = $wc4bp_options['tab_track_disabled'];
	
	$tab_activity_disabled = 0;
	if(isset( $wc4bp_options['tab_activity_disabled']))
		$tab_activity_disabled = $wc4bp_options['tab_activity_disabled'];
	
	$tab_sync_disabled = 0;
	if(isset( $wc4bp_options['tab_sync_disabled']))
		$tab_sync_disabled = $wc4bp_options['tab_sync_disabled'];
	
	$page_template = '';
	if(!empty( $wc4bp_options['page_template']))
		$page_template = $wc4bp_options['page_template'];
	?>
	
	<h3>Remove Shop Tabs in Member Profiles</h3>
	
	<p>By default all account related WooCommerce pages are included into the BuddyPress member profiles.</p>
	<p><i>You defined these pages in the Page Setup in <a href="<?php echo get_admin_url(); ?>admin.php?page=woocommerce_settings&tab=pages" title="You defined these pages exactly here!" target="_new">WooCommerce > Settings > Pages</a></i></p>	
    <br>
    
    <p><input name='wc4bp_options[tab_cart_disabled]' type='checkbox' value='1' <?php checked( $tab_cart_disabled, 1  ) ; ?> /> <b>Turn off "Cart" tab. </b></p>
	<p><input name='wc4bp_options[tab_history_disabled]' type='checkbox' value='1' <?php checked( $tab_history_disabled, 1  ) ; ?> /> <b>Turn off "History" tab. </b></p>
	<p><input name='wc4bp_options[tab_track_disabled]' type='checkbox' value='1' <?php checked( $tab_track_disabled, 1  ) ; ?> /> <b>Turn off "Track my order" tab. </b> </p>
	
	<p><input name='wc4bp_options[tab_activity_disabled]' type='checkbox' value='1' <?php checked( $tab_activity_disabled, 1  ) ; ?> /> <b>Turn off "Shop" Tab</b> <i>inside</i> "Settings" for the activity stream settings. </p>
	<hr />
	WARNING: if you disable profile sync, the adress and shipping profile groups will be deleted.
	
	Woocommerce and BuddyPress is synced so all the user data should be available to the WooCommerce account fields.
	How ever, you should decide for one way and stay with it. Do not change this too often. It can mess up your user profile data.
	
	<p><input name='wc4bp_options[tab_sync_disabled]' type='checkbox' value='1' <?php checked( $tab_sync_disabled, 1  ) ; ?> /> <b>Turn off WooCommerce BuddyPress Profile Sync.</b> This will also remove the Billing Address - Shipping Address Tabs from Profile/Edit. </p>
	<hr />
	
	<?php
	// not ready jet
	if(isset($tab_sync_disabled) && TRUE == $tab_sync_disabled){
		include_once( dirname( __FILE__ ) .'/wc4bp-activate.php' );
		wc4bp_cleanup();
	} else {
		include_once( dirname( __FILE__ ) .'/wc4bp-activate.php' );
		wc4bp_activate();
	}
	
	 ?>
	<p>	
		<b>Overwrite the Content of your Shop Home/Main Tab</b><br>
		<i>Select the tab you want to use as your Shop Home.  </i><br>
		<select name='wc4bp_options[tab_shop_default]'>
		<?php
			if(isset($wc4bp_pages_options['selected_pages']) && is_array($wc4bp_pages_options['selected_pages']) && count( $wc4bp_pages_options['selected_pages'] ) > 0 ){
				echo '<option value="default" '.selected( $wc4bp_options['tab_shop_default'], $key ).'>Default</option>';
				foreach ($wc4bp_pages_options['selected_pages'] as $key => $attached_page) {
					echo '<option value="'.$key.'" '.selected( $wc4bp_options['tab_shop_default'], $key ).'>'.$attached_page['tab_name'].'</option>';
				}
			} else {
				echo '<option value="default" '.selected( $wc4bp_options['tab_shop_default'], $key ).'>You need at least one Page added to Member Profiles!</option>';
			}
		?>

		</select>
	</p>
	<hr />
	<p>
		<b>Change the page template to be used for the attached pages.</b><br>
		<i>For example 'content', 'page' would check for a template content-page.php 
			and if content-page.php not exists it would look for content.php.
			
			Please keep in mind that you need to use a template part. without the header and footer added. just the loop item! ;)
		</i><br>
		<input name='wc4bp_options[page_template]' type='text' value="<?php echo $page_template ?>" />
		
	</p>
	<hr />
	
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
	
	<p><b>Rename Shop Parent Tab:</b><input id='text' name='wc4bp_options[shop_main_nav]' type='text' value='<?php echo $shop_main_nav; ?>' /></p>
	<p><b>Rename Shopping Cart:</b><input id='text' name='wc4bp_options[cart_sub_nav]' type='text' value='<?php echo $cart_sub_nav; ?>' /></p>
	<p><b>Rename History:</b><input id='text' name='wc4bp_options[history_sub_nav]' type='text' value='<?php echo $history_sub_nav; ?>' /></p>
	<p><b>Rename Track your order:</b><input id='text' name='wc4bp_options[track_sub_nav]' type='text' value='<?php echo $track_sub_nav; ?>' /></p>
	
	<?php

	submit_button(); 

}
function wc4bp_shop_tabs_add(){
	wc4bp_get_forms_table();
	
}

function wc4bp_get_forms_table() {
	//$wc4bp_options			= get_option( 'wc4bp_options' ); 
	$wc4bp_pages_options	= get_option( 'wc4bp_pages_options' ); 
	
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
		table.wp-list-table th.manage-column {
			width: auto;
		}
				
    </style>
    
    <h3>Add Pages to Member Profiles</h3>
    
    <p>Integrate other pages (for example from other WooCommerce extensions) into your BuddyPress member profiles.</p>
	<p><i>This will redirect the page to the correct profile page and add a menu item in the profile.</i></p>
    
	<?php wc4bp_thickbox_page_form(); ?>
	<table class="wp-list-table widefat fixed posts">
		
		<thead>
			<tr>
				<th scope="col" id="page" class="manage-column column-comment column-n" style="">Page</th>
				<th scope="col" id="children" class="manage-column column-status" style="">Including Children?</th>
				<th scope="col" id="name" class="manage-column column-description" style="">Tab Name</th>
				<th scope="col" id="slug" class="manage-column column-description" style="">Tab Slug</th>
				<th scope="col" id="position" class="manage-column column-status" style="">Position</th>

			</tr>
		</thead>
		<tbody id="the-list">
		<?php
		if(isset($wc4bp_pages_options['selected_pages']) && is_array($wc4bp_pages_options['selected_pages'])){
			foreach ($wc4bp_pages_options['selected_pages'] as $key => $attached_page) { ?>
				<tr id="post-<?php echo $key ?>" class="post-<?php echo $key ?> type-page status-publish hentry alternate iedit author-self wc4bp_tr" valign="bottom">	
					<td class="column-name">
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
					<td class="column-slug">
						<?php echo isset($attached_page['children']) && $attached_page['children'] > 0 ? 'Yes': 'No'; ?>
					</td>
					<td class="slug column-slug">
						<?php echo isset($attached_page['tab_name']) ? $attached_page['tab_name']: '--'; ?>
					</td>
					<td class="slug column-slug">
						<?php echo isset($attached_page['tab_slug']) ? $attached_page['tab_slug']: '--'; ?>
					</td>
					<td class="slug column-slug">
						<?php echo !empty($attached_page['position']) ? $attached_page['position']: '--'; ?>
					</td>
				</tr>
			<?php
		 	}
		} 
	
	echo '</tbody></table>';
}

function wc4bp_thickbox_page_form(){
	//$options = get_option( 'wc4bp_options' ); ?>
	
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

	$wc4bp_pages_options	= get_option( 'wc4bp_pages_options' ); 
	
	if(isset($wc4bp_tab_slug)){
		
		if(isset( $wc4bp_pages_options['selected_pages'][$wc4bp_tab_slug]['tab_name']))
			$tab_name = $wc4bp_pages_options['selected_pages'][$wc4bp_tab_slug]['tab_name'];

		if(isset( $wc4bp_pages_options['selected_pages'][$wc4bp_tab_slug]['children']))
			$children = $wc4bp_pages_options['selected_pages'][$wc4bp_tab_slug]['children'];

		if(isset( $wc4bp_pages_options['selected_pages'][$wc4bp_tab_slug]['position']))
			$position = $wc4bp_pages_options['selected_pages'][$wc4bp_tab_slug]['position'];
		
		if(isset( $wc4bp_pages_options['selected_pages'][$wc4bp_tab_slug]['page_id']))
			$page_id = $wc4bp_pages_options['selected_pages'][$wc4bp_tab_slug]['page_id'];
			
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
	<?php wp_dropdown_pages($args); ?>
	<input id='wc4bp_children' name='wc4bp_children' type='checkbox' value='1'/ <?php checked($children, 1 ); ?>>&nbsp;<b>Include Children?</b></p> 
	<p><b>Tab Name</b><i>If empty same as Pagename</i><br>

	<input id='wc4bp_tab_name' name='wc4bp_tab_name' type='text' value='<?php echo $tab_name ?>' /></p>
	<p><b>Position</b><br>
	<small><i>Just enter a number like 1, 2, 3..</i></small><br>
	<input id='wc4bp_position' name='wc4bp_position' type='text' value='<?php echo $position ?>' /></p>

	
	<?php if(isset($wc4bp_tab_slug)) ?>
		<input type="hidden" id="wc4bp_tab_slug" value="<?php echo $wc4bp_tab_slug ?>" />
	
	<input type="button" value="Save" name="add_cpt4bp_page" class="button add_cpt4bp_page btn">
	<?php
}
?>