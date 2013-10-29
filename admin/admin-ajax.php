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
		$wc4bp_page_id = $_POST['wc4bp_page_id'];
	
	if(isset($_POST['wc4bp_tab_name']))
		$tab_name = $_POST['wc4bp_tab_name'];
	
	if(isset($_POST['wc4bp_position']))
		$position = $_POST['wc4bp_position'];
	
	if(isset($_POST['wc4bp_main_nav']))
		$main_nav = $_POST['wc4bp_main_nav'];
	
	if(empty($wc4bp_page_id))
		return;
	
	$wc4bp_options = get_option('wc4bp_options');
	
	$wc4bp_options['selected_pages'][$wc4bp_page_id]['tab_name'] = $tab_name;
	$wc4bp_options['selected_pages'][$wc4bp_page_id]['position'] = $position;
	$wc4bp_options['selected_pages'][$wc4bp_page_id]['main_nav'] = $main_nav; 	
	
	update_option("wc4bp_options", $wc4bp_options);

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
		
	if(isset($_POST['wc4bp_page_id']))
		$wc4bp_page_id = $_POST['wc4bp_page_id'];

	if(empty($wc4bp_page_id))
		return;

	$wc4bp_options = get_option('wc4bp_options');
	unset( $wc4bp_options['selected_pages'][$wc4bp_page_id] );
   
	update_option("wc4bp_options", $wc4bp_options);
    die();

}
?>