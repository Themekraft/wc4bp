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

		$old_post = $post;
		$post = '';

		setup_postdata($wp_query->posts[0]); ?>
		
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
		<?php edit_post_link( __( 'Edit', '_tk' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
		</article><!-- #post-## -->
		
		<?php
		$post = $old_post;
	} else {
		get_template_part( $wc4bp_options['page_template'] );
	} 
?>
</div><!-- #item-body -->