<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
?>
<div id="item-body" role="main">
	<?php
	global $bp;
	
	$wc4bp_options		= get_option( 'wc4bp_options' );
	
	$wp_query = new wp_query(
		array(
	        'p'      => $wc4bp_options['selected_pages'][$bp->current_action]['page_id'],
	        'post_type' => 'page'
	    )
	);

	if ( isset($wp_query) && '' != locate_template( 'contenst-pagee.php', true, false ) ){
		get_template_part( 'content', 'page' );
	} else {
		echo $wp_query->content;
	}
			
	
?>
</div><!-- #item-body -->