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

	<?php do_action( 'bpshop_before_history_body' ); ?>

	<div class="item-list-tabs no-ajax" id="subnav">
		<ul>
			<?php bp_get_options_nav(); ?>
			<?php do_action( 'bpshop_member_options_nav' ); ?>
		</ul>
	</div><!-- .item-list-tabs -->

	<?php
	if( bpshop_is_subpage( 'view' ) ) :
		bpshop_load_template( 'shop/member/history/view' );
	else :
		bpshop_load_template( 'shop/member/history/home' );
	endif;
	?>

	<?php do_action( 'bpshop_after_history_body' ); ?>

</div><!-- #item-body -->