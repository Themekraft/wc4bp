<?php


/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */

function wc4bp_screen_pages() { ?>

    <div class="wrap">

    <div id="icon-options-general" class="icon32"><br></div>
    <h2>WooCommerce BuddyPress Integration Settings</h2>

    <div style="overflow: auto;">

        <span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>

    </div>
    <br>

    <form method="post" action="options.php">
        <?php wp_nonce_field( 'update-options' ); ?>
        <?php settings_fields( 'wc4bp_options_pages' ); ?>
        <?php do_settings_sections( 'wc4bp_options_pages' ); ?>

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

add_action( 'admin_init', 'wc4bp_register_admin_pages_settings' );

function wc4bp_register_admin_pages_settings() {

    register_setting( 'wc4bp_options_pages', 'wc4bp_options_pages' );

    // Settings fields and sections
    add_settings_section(	'section_general'	, ''							, 'wc4bp_shop_pages_add'	, 'wc4bp_options_pages' );

    //add_settings_field(		'pages_add'	, '<b>Add New pages</b>' , 'wc4bp_shop_pages_add'	, 'wc4bp_options_pages' , 'section_general' );

}

function wc4bp_shop_pages_add(){
    wc4bp_get_forms_table();

}

function wc4bp_shop_pages_rename(){
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


function wc4bp_get_forms_table() {
    //6$wc4bp_options			= get_option( 'wc4bp_options' );
    $wc4bp_pages_options	= get_option( 'wc4bp_pages_options' );

    // echo '<pre>';
    // print_r($wc4bp_pages_options);
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
            padding: 20px 0px 20px 10px;
        }

    </style>

    <h3>Add Pages to Member Profiles</h3>

    <p>Integrate other pages (for example from other WooCommerce extensions) into your BuddyPress member profiles.</p>
    <p><i>This will redirect the page to the correct profile page and add a menu item in the profile.</i></p><br>

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

    $children = 0;
    $page_id = '';
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


    <?php if(isset($wc4bp_tab_slug))
        echo '<input type="hidden" id="wc4bp_tab_slug" value="' . $wc4bp_tab_slug . '" />';
    ?>

    <input type="button" value="Save" name="add_cpt4bp_page" class="button add_cpt4bp_page btn">
<?php
}
?>