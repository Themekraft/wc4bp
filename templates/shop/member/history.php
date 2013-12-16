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
	<?php do_action( 'woocommerce_before_my_account' ); ?>
	
	<?php do_action( 'wc4bp_before_history_body' ); ?>

	<?php
	if(  wc4bp_is_subpage( 'view' ) ) :
		 wc4bp_load_template( 'shop/member/history/view' );
	else :
		 wc4bp_load_template( 'shop/member/history/home' );
	endif;
	?>

	<?php do_action( 'wc4bp_after_history_body' ); ?>

</div><!-- #item-body -->