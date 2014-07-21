<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

get_header( 'buddypress' ); ?>

	<div id="content">
		<div class="padder">

			<?php do_action( 'wc4bp_before_member_home_content' ); ?>

			<div id="item-header" role="complementary">

				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>

			</div><!-- #item-header -->

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>

						<?php bp_get_displayed_user_nav(); ?>

						<?php do_action( 'bp_member_options_nav' ); ?>

					</ul>
				</div>
			</div><!-- #item-nav -->

			<div id="item-body">

				<?php do_action( 'wc4bpbefore_member_body' ); ?>
				
				<?php
				if(  wc4bp_is_page( 'history' ) ) :
					 wc4bp_load_template( 'shop/member/history' );
					
				elseif(  wc4bp_is_page( 'track' ) ) :
					 wc4bp_load_template( 'shop/member/track' );

				else :
					bp_core_load_template( 'shop/member/cart' );
				endif;
				?>

				<?php do_action( 'wc4bpfter_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'wc4bp_after_member_home_content' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>