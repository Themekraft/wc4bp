<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Sven Lehnert
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
?>
<div id="item-body" role="main">

	<?php
	global $bp;
	
	$wc4bp_options			= get_option( 'wc4bp_options' );
	$wc4bp_pages_options	= get_option( 'wc4bp_pages_options' );

	if(isset($bp->action_variables[0])){
		$wp_query = new wp_query(
			array(
		        'name'      => $bp->action_variables[0],
		        'post_type' => 'page'
		    )
		);
	} else {
		$wp_query = new wp_query(
			array(
		        'p'      => $wc4bp_pages_options['selected_pages'][$bp->current_action]['page_id'],
		        'post_type' => 'page'
		    )
		);
	}

	if ( empty($wc4bp_options['page_template']) ){
		if(locate_template( 'content-page.php', true, false )){
			get_template_part( 'content', 'page' );
		} else {
			echo $wp_query->pages[0]->post_content;
		}
	} else {
		get_template_part( $wc4bp_options['page_template'] );
	} 
?>
</div><!-- #item-body -->