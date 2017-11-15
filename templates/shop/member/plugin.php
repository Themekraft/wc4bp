<div id="item-body" role="main">
	<?php
	/**
	 * @package        WordPress
	 * @subpackage    BuddyPress, Woocommerce
	 * @author        Sven Lehnert
	 * @link        https://github.com/Themekraft/BP-Shop-Integration
	 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
	 */
	global $bp, $wp, $post;
	$action              = bp_current_action();
	$bp_action_variables = $bp->action_variables;
	$wc4bp_options       = get_option( 'wc4bp_options' );
	$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
	if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
		$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
	}
	$my_account_page = 0;
	if ( WC4BP_Loader::getFreemius()->is_plan__premium_only( wc4bp_base::$starter_plan_id ) ) {
		$available_endpoint = WC4BP_MyAccount::get_active_endpoints__premium_only();
		if ( ! empty( $available_endpoint ) ) {
			foreach ( $available_endpoint as $available_endpoint_key => $available_endpoint_name ) {
				$current_page = $available_endpoint_key;
				if ( $action == $current_page ) {
					$my_account_page = 1;
					$order_page      = 'orders';
					if ( $action == $order_page && ! empty( $bp_action_variables ) ) {
						foreach ( $bp_action_variables as $var ) {
							if ( $var == 'view-order' ) {
								$my_account_page = 2;
								break;
							}
						}
					}
					break;
				}
			}
		}
	}
	$args = array();
	switch ( $my_account_page ) {
		case 1:
			$args = array( 'pagename' => $action );
			break;
		case 2:
			woocommerce_account_view_order( get_query_var( 'view-order' ) );
			break;
		default:
			$page    = get_page_by_path( $bp->current_action );
			if ( ! empty( $page ) ) {
				if ( isset( $bp_action_variables[0] ) ) {
					$args = array(
						'name'      => $bp_action_variables[0],
						'post_type' => 'page'
					);
				} else if ( isset( $wc4bp_pages_options['selected_pages'][ $page->ID ]['page_id'] ) ) {
					$args = array(
						'p'         => $wc4bp_pages_options['selected_pages'][ $page->ID ]['page_id'],
						'post_type' => 'page'
					);
				}
			}
			$args = apply_filters( 'wc4bp_members_plugin_template_query', $args );
	}
	
	if ( $my_account_page <= 1 ) {
		$wp_query2 = new wp_query( $args );
		if ( ! empty( $wp_query2->posts ) ) {
			$custom_page_template = apply_filters('wc4bp_custom_page_template', '');
			if ( empty( $custom_page_template ) ) {
				$old_post = $post;
				$post     = $wp_query2->posts[0];
				setup_postdata( $post ); ?>

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
				get_template_part( $custom_page_template );
			}
		} else {
			$wp_query2->set_404();
			status_header( 404 );
			nocache_headers();
			include( get_query_template( '404' ) );
		}
	}
	?>
</div><!-- #item-body -->
