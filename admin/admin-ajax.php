<?php
/**
 * Ajax call back function to add a page
 *
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */
// add_action( 'wp_ajax_wc4bp_thickbox_add_page', 'wc4bp_thickbox_add_page' );
// add_action( 'wp_ajax_nopriv_wc4bp_thickbox_add_page', 'wc4bp_thickbox_add_page' );
//
// function wc4bp_thickbox_add_page(){
		// wc4bp_add_edit_entry_form('edit');
	// die();
// }

add_action( 'wp_ajax_wc4bp_edit_entry', 'wc4bp_edit_entry' );
add_action( 'wp_ajax_nopriv_wc4bp_edit_entry', 'wc4bp_edit_entry' );
function wc4bp_edit_entry(){
	wc4bp_add_edit_entry_form('edit');
	die();
}

add_action( 'wp_ajax_wc4bp_add_page', 'wc4bp_add_page' );
add_action( 'wp_ajax_nopriv_wc4bp_add_page', 'wc4bp_add_page' );

function wc4bp_add_page($wc4bp_page_id){

	if(isset($_POST['wc4bp_page_id']))
		$page_id = $_POST['wc4bp_page_id'];

	if(!empty($_POST['wc4bp_tab_name'])){
		$tab_name = $_POST['wc4bp_tab_name'];
	} else {
		$tab_name = get_the_title($page_id);
	}

	if(isset($_POST['wc4bp_position']))
		$position = $_POST['wc4bp_position'];

	if(isset($_POST['wc4bp_children']))
		$children = $_POST['wc4bp_children'];

	if(isset($_POST['wc4bp_tab_slug']))
		$tab_slug = $_POST['wc4bp_tab_slug'];

	if(empty($tab_slug))
		$tab_slug = sanitize_title($tab_name);

	if(empty($page_id))
		return;

	$wc4bp_pages_options = get_option('wc4bp_pages_options');

	$wc4bp_pages_options['selected_pages'][$tab_slug]['tab_name'] = $tab_name;
	$wc4bp_pages_options['selected_pages'][$tab_slug]['tab_slug'] = $tab_slug;
	$wc4bp_pages_options['selected_pages'][$tab_slug]['position'] = $position;
	$wc4bp_pages_options['selected_pages'][$tab_slug]['children'] = $children;
	$wc4bp_pages_options['selected_pages'][$tab_slug]['page_id']	= $page_id;
	
	update_option("wc4bp_pages_options", $wc4bp_pages_options);

	die();

}

/**
 * Ajax call back function to delete a form element
 *
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */

add_action('wp_ajax_wc4bp_delete_page', 'wc4bp_delete_page');
add_action('wp_ajax_nopriv_wc4bp_delete_page', 'wc4bp_delete_page');

function wc4bp_delete_page(){

	if(isset($_POST['wc4bp_tab_slug']))
		$wc4bp_tab_slug = $_POST['wc4bp_tab_slug'];

	if(empty($wc4bp_tab_slug))
		return;

	$wc4bp_pages_options = get_option('wc4bp_pages_options');
	unset( $wc4bp_pages_options['selected_pages'][$wc4bp_tab_slug] );

	update_option("wc4bp_pages_options", $wc4bp_pages_options);
    die();

}
?>
