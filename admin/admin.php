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
    add_menu_page( 'WooCommerce for BuddyPress', 'WC4BP Settings', 'manage_options', 'wc4bp-options-page', 'wc4bp_screen' );

    add_submenu_page( 'wc4bp-options-page'  , 'WC4BP Integrate Pages'     , 'Integrate Pages'       , 'manage_options', 'wc4bp-options-page-pages'   , 'wc4bp_screen_pages' );

//    add_submenu_page( 'wc4bp-options-page'  , 'BuddyPress Profile'     , 'BuddyPress xProfile'       , 'manage_options', 'wc4bp-options-page-xprofile'   , 'wc4bp_screen_xprofile' );

    $wc4bp_options = get_option( 'wc4bp_options' );
    if(!isset($wc4bp_options['tab_sync_disabled']))
        add_submenu_page( 'wc4bp-options-page'  , 'WC4BP Profile Fields Sync'  , 'Profile Fields Sync'    , 'manage_options', 'wc4bp-options-page-sync'   , 'wc4bp_screen_sync' );



    do_action('wc4bp_add_submenu_page');
    add_submenu_page( 'wc4bp-options-page'  , 'Delete'     , 'Delete'       , 'manage_options', 'wc4bp-options-page-delete'   , 'wc4bp_screen_delete' );

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

        <div style="overflow: auto;">
            <span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>
            <div style="margin: 30px 0 0 0; padding: 20px; overflow: auto; background-color: #fff; border-left: 4px solid #2ea2cc; -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);">
                <div style="float: left; overflow: auto; border-right: 1px solid #ddd; padding: 0 20px 0 0;">
                    <h3>Get eMail Support.</h3>
                    <p><a class="button secondary" target="_blank"  href="https://themekraft.zendesk.com/hc/en-us/requests/new" title="Submit an email support ticket">Ask Question</a> </p>
                </div>
                <div style="float: left; overflow: auto; padding: 0 20px 0 20px; border-right: 1px solid #ddd;">
                    <h3>Tell us your Ideas!</h3>
                    <p>Add your ideas and vote for others <a title="Visit Ideas Forums" class="button button-secondary" href="https://themekraft.zendesk.com/hc/communities/public/topics/200001221-WooCommerce-BuddyPress-Integration-Ideas" target="_new">Ideas Forums</a></p>
                </div>
                <div style="float: left; overflow: auto; padding: 0 20px 0 20px;">
                    <h3>Documentation</h3>
                    <p>Find Help in the Knowledge-Base <a title="Documentation" class="button button-secondary" href="https://themekraft.zendesk.com/hc/en-us/categories/200005301-WooCommerce-BuddyPress-Integration" target="_new">Documentation</a></p>
                </div>
            </div>
        </div>
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
    add_settings_section(	'section_general2'	, ''							, ''	, 'wc4bp_options' );

    add_settings_field(		'tabs_disabled'	, '<b>Remove Shop Tabs</b>'	                                            , 'wc4bp_shop_tabs_disable'	, 'wc4bp_options' , 'section_general' );
    add_settings_field(		'profile sync'	, '<b>Turn off the profile sync</b>'	                                , 'wc4bp_turn_off_profile_sync'	, 'wc4bp_options' , 'section_general' );
    add_settings_field(		'overwrite'	    , '<b>Overwrite the Content of your Shop Home/Main Tab</b>'	            , 'wc4bp_overwrite_default_shop_home_tab'	, 'wc4bp_options' , 'section_general' );
    add_settings_field(		'template'	    , '<b>Change the page template to be used for the attached pages.</b>'	, 'wc4bp_page_template'	, 'wc4bp_options' , 'section_general' );

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

	?>
	<p>By default all account related WooCommerce pages are included into the BuddyPress member profiles.</p>

    <p><input name='wc4bp_options[tab_cart_disabled]' type='checkbox' value='1' <?php checked( $tab_cart_disabled, 1  ) ; ?> /> <b>Turn off "Cart" tab. </b></p>
	<p><input name='wc4bp_options[tab_history_disabled]' type='checkbox' value='1' <?php checked( $tab_history_disabled, 1  ) ; ?> /> <b>Turn off "History" tab. </b></p>
	<p><input name='wc4bp_options[tab_track_disabled]' type='checkbox' value='1' <?php checked( $tab_track_disabled, 1  ) ; ?> /> <b>Turn off "Track my order" tab. </b> </p>
	
	<p><input name='wc4bp_options[tab_activity_disabled]' type='checkbox' value='1' <?php checked( $tab_activity_disabled, 1  ) ; ?> /> <b>Turn off "Shop" Tab</b> <i>inside</i> "Settings" for the activity stream settings. </p>

	<?php

}

function wc4bp_turn_off_profile_sync(){
    $wc4bp_options = get_option( 'wc4bp_options' );

    $tab_sync_disabled = 0;
    if(isset( $wc4bp_options['tab_sync_disabled']))
        $tab_sync_disabled = $wc4bp_options['tab_sync_disabled']; ?>


    <p>If you disable profile sync, the billing and shipping profile groups will be deleted.</p>
    <p><i>This will also remove the Billing Address - Shipping Address Tabs from Profile/Edit and disable all sync settings</i></p>
    <p><input name='wc4bp_options[tab_sync_disabled]' type='checkbox' value='1' <?php checked( $tab_sync_disabled, 1  ) ; ?> /> <b>Turn off WooCommerce BuddyPress Profile Sync.</b></p>

    <?php

    if(isset($tab_sync_disabled) && TRUE == $tab_sync_disabled){
        include_once( dirname( __FILE__ ) .'/wc4bp-activate.php' );
        wc4bp_cleanup();
    } else {
        include_once( dirname( __FILE__ ) .'/wc4bp-activate.php' );
        wc4bp_activate();
    }


}

function wc4bp_overwrite_default_shop_home_tab(){
    $wc4bp_options = get_option( 'wc4bp_options' );
    $wc4bp_pages_options	= get_option( 'wc4bp_pages_options' );?>

    <p>Select the tab you want to use as your Shop Home.</p>
    <select name='wc4bp_options[tab_shop_default]'>
    <?php
        if(isset($wc4bp_pages_options['selected_pages']) && is_array($wc4bp_pages_options['selected_pages']) && count( $wc4bp_pages_options['selected_pages'] ) > 0 ){
            echo '<option value="default" '.selected( $wc4bp_options['tab_shop_default'], 'default', false ).'>Default</option>';
            foreach ($wc4bp_pages_options['selected_pages'] as $key => $attached_page) {
                echo '<option value="'.$key.'" '.selected( $wc4bp_options['tab_shop_default'], $key, false ).'>'.$attached_page['tab_name'].'</option>';
            }
        } else {
            echo '<option value="default"> You need at least one Page added to Member Profiles!</option>';
        }
    ?>

    </select>

    <?php
}


function wc4bp_page_template(){
    $wc4bp_options = get_option( 'wc4bp_options' );

    $page_template = '';
    if(!empty( $wc4bp_options['page_template']))
        $page_template = $wc4bp_options['page_template']; ?>

    <p>Please keep in mind that you need to use a template part.<br> Without the header and footer added. Just the loop item!</p>
    <p>For example 'content', 'page' would check for a template content-page.php and if content-page.php not exists it would look for content.php.</p>
    <input name='wc4bp_options[page_template]' type='text' value="<?php echo $page_template ?>" />


    <?php
    submit_button();
}

?>
