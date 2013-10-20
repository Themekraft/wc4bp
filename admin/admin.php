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

        <div id="icon-themes" class="icon32"><br></div>
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
	
	add_settings_field(		'tabs_disabled'	, '<b>Disablel Shop Profile Tabs</b>
												<p>by default all WooCommerse Pages are Included into the BuddyPress Member Profiles.</p>
												<p>You defind you WooCommerce pages in the Page Setup under WooCommerce/Settings/Pages</p>'	, 'wc4bp_shop_tabs_disable'	, 'wc4bp_options' , 'section_general' );
	//add_settings_field(		'tabs_rename'	, '<b>Rename Shop Profile Tabs</b>'	, 'wc4bp_shop_tabs_rename'	, 'wc4bp_options' , 'section_general' );
	add_settings_field(		'tabs_add'	, '<b>Add other Pages to Profiles</b>
											<p><i>Integrate your WooCommerce Extention Pages with BuddyPress Member Profiles.</p>
											<p>This will redirect the Page to the corect Profile Page.<br> </i></p>'	, 'wc4bp_shop_tabs_add'	, 'wc4bp_options' , 'section_general' );

}

/**
 * Important notice on top of the screen
 * 
 * @author Sven Lehnert
 * @package TK Loop Designer 
 * @since 1.0
 */ 
 
function wc4bp_general() {
	
    echo '<p><i> WooCommerce for BuddyPress Setup </i></p><br>';
			
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
	
	$tab_shop_disabled = 0;
	if(isset( $options['tab_shop_disabled']))
		$tab_shop_disabled = $options['tab_shop_disabled'];
	
	$tab_history_disabled = 0;
	if(isset( $options['tab_history_disabled']))
		$tab_history_disabled = $options['tab_history_disabled'];
		
	$tab_track_disabled = 0;
	if(isset( $options['tab_track_disabled']))
		$tab_track_disabled = $options['tab_track_disabled'];
	?>
    <p><b>Turn off Cart Tab: </b> <input id='checkbox' name='wc4bp_options[tab_shop_disabled]' type='checkbox' value='1' <?php checked( $tab_shop_disabled, 1  ) ; ?> /></p>
	<p><b>Turn off History Tab: </b> <input id='checkbox' name='wc4bp_options[tab_history_disabled]' type='checkbox' value='1' <?php checked( $tab_history_disabled, 1  ) ; ?> /></p>
	<p><b>Turn off Track my order Tab: </b> <input id='checkbox' name='wc4bp_options[tab_track_disabled]' type='checkbox' value='1' <?php checked( $tab_track_disabled, 1  ) ; ?> /></p>
	
	<p><b>Turn off Shop Tab under Settings/Shop for th Activity Stream settings: </b> <input id='checkbox' name='wc4bp_options[tab_track_disabled]' type='checkbox' value='1' <?php checked( $tab_track_disabled, 1  ) ; ?> /></p>
	<p><b>Turn off WooCommerce BuddyPress Profiel sync: This will also remove the Billing Address - Shipping Address Tabs from Profile/Edit
 </b> <input id='checkbox' name='wc4bp_options[tab_track_disabled]' type='checkbox' value='1' <?php checked( $tab_track_disabled, 1  ) ; ?> /></p>
	
	<?php

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
	//wc4bp_add_page_form();
	wc4bp_get_forms_table();
	
}

function wc4bp_get_forms_table() {
	$options = get_option( 'wc4bp_options' );?>
	 <style type="text/css">
	 .wc4bp_editinline{
	 	color: #bc0b0b;
	 	cursor: pointer;
	 }
		table #the-list tr .wc4bp-row-actions { opacity:0 }
		table #the-list tr:hover .wc4bp-row-actions { opacity:1 }
				
    </style>
    <a href="#" class="add_cpt4bp_page">asd</a>
	<table class="wp-list-table widefat fixed posts">
		
		<thead>
			<tr>
				<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
					<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
					<input id="cb-select-all-1" type="checkbox">
				</th>
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
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-<?php echo $key ?>"><?php echo $key; ?></label>
						<input type="checkbox" name="post[]" value="<?php echo $key ?>" id="cb-select-<?php echo $key ?>">
					</th>		
					<td class="slug column-slug">
						<?php echo  get_the_title($key); ?>
						<div class="wc4bp-row-actions">
							<span class="wc4bp_inline hide-if-no-js">
								<span id="#wc4bp_inline_<?php echo $key ?>" class="wc4bp_editinline" title="Edit this item inline">Edit</span> |
							</span>
							<span class="trash">
								<span id="<?php echo $key ?>" class="wc4bp_delete_page" title="Delete this item">Delete</span>
							</span>
						</div>
	
						<div id="wc4bp_inline_<?php echo $key ?>" class="hidden">

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

function wc4bp_add_page_form(){
	$options = get_option( 'wc4bp_options' );
	
	
	$args = array( 
		'echo' => true,
		'sort_column'  => 'post_title',
		'show_option_none' => __( 'none', 'wc4bp' ),
		'name' => "wc4bp_page_id",
		'class' => 'postform',
	);
	wp_dropdown_pages($args);
	?>

	<p><b>Tab Name? </b><input id='wc4bp_tab_name' name='wc4bp_tab_name' type='text' value='' /></p>
	<p><b>Position: </b><input id='wc4bp_position' name='wc4bp_position' type='text' value='' /></p>
	<p><b>Main Nav?: </b> <input id='wc4bp_main_nav' name='wc4bp_main_nav' type='checkbox' value='1'/></p>
	<input type="button" value="Save" name="add_cpt4bp_page" class="button add_cpt4bp_page btn">	

	<?php
}
?>