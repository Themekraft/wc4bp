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
	global $bp, $post;

	$wc4bp_options			= get_option( 'wc4bp_options' );
	$wc4bp_pages_options	= get_option( 'wc4bp_pages_options' );

	if( isset($bp->action_variables[0]) ){
		$args = array(
		        'name'      => $bp->action_variables[0],
		        'post_type' => 'page'
		    );
	} elseif( isset($wc4bp_pages_options['selected_pages'][$bp->current_action]['page_id']) ) {
		$args = array(
		        'p'      => $wc4bp_pages_options['selected_pages'][$bp->current_action]['page_id'],
		        'post_type' => 'page'
		    );
	}
	$args = apply_filters( 'wc4bp_members_plugin_template_query', $args );
	$wp_query2 = new wp_query($args);

	if ( empty($wc4bp_options['page_template']) ){

		$old_post = $post;
		$post = $wp_query2->posts[0];

		setup_postdata($post); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php the_content(); ?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'wc4bp' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->
		<?php edit_post_link( __( 'Edit', 'wc4bp' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
		</article><!-- #post-## -->

		<?php
        wp_reset_postdata();
		$post = $old_post;
	} else {
        $wp_query = $wp_query2;
		get_template_part( $wc4bp_options['page_template'] );
	}
?>
</div><!-- #item-body -->
