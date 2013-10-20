<?php
/**
 * Ajax call back function to add a page 
 * 
 * @author Sven Lehnert 
 * @package WC4BP
 * @since 1.3
 */

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

add_action( 'wp_ajax_wc4bp_edit_entry', 'wc4bp_edit_entry' );
add_action( 'wp_ajax_nopriv_wc4bp_edit_entry', 'wc4bp_edit_entry' );
function wc4bp_edit_entry(){
	
	if(isset($_POST['wc4bp_page_id']))
		$wc4bp_page_id = $_POST['wc4bp_page_id'];

	if(empty($wc4bp_page_id))
		return;

	$options = get_option( 'wc4bp_options' );

	$attached_pages = '';
	if(isset( $options['selected_pages'][$wc4bp_page_id]['attached_page']))
		$attached_pages = $options['selected_pages'][$wc4bp_page_id]['attached_pages'];
		
	$tab_name = '';
	if(isset( $options['selected_pages'][$wc4bp_page_id]['tab_name']))
		$tab_name = $options['selected_pages'][$wc4bp_page_id]['tab_name'];
	
	$position = '';
	if(isset( $options['selected_pages'][$wc4bp_page_id]['position']))
		$position = $options['selected_pages'][$wc4bp_page_id]['position'];
	
	$main_nav = '';
	if(isset( $options['selected_pages'][$wc4bp_page_id]['main_nav']))
		$main_nav = $options['selected_pages'][$wc4bp_page_id]['main_nav'];
	
	$args = array( 
		'echo' => true,
		'sort_column'  => 'post_title',
		'show_option_none' => __( 'none', 'wc4bp' ),
		'name' => "wc4bp_page_id",
		'class' => 'postform',
		'selected' => $attached_pages
	);
	wp_dropdown_pages($args);
	?>

	<p><b>Tab Name? </b><input id='wc4bp_tab_name' name='wc4bp_tab_name' type='text' value='<?php echo $tab_name ?>' /></p>
	<p><b>Position: </b><input id='wc4bp_position' name='wc4bp_position' type='text' value='<?php echo $position ?>' /></p>
	<p><b>Main Nav?: </b> <input id='wc4bp_main_nav' name='wc4bp_main_nav' type='checkbox' value='1'/></p>
	<input type="button" value="Save" name="add_cpt4bp_page" class="button add_cpt4bp_page btn">
	<a href="#" class="add_cpt4bp_page">asd</a>
	<?php
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