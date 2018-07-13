<?php

/**
 * @package        WordPress
 * @subpackage     BuddyPress, WooCommerce
 * @author         Boris Glumpler
 * @copyright      2011, Themekraft
 * @link           https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class handle all core redirection and url replace
 *
 * Class wc4bp_redirect
 */
class wc4bp_redirect {
	
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'wc4bp_redirect_to_profile' ) );
		add_filter( 'page_link', array( $this, 'wc4bp_page_link_router' ), 9999, 2 );//High priority to take precedent over other plugins
	}
	
	/**
	 * Get base url for all redirection
	 *
	 * @return string
	 */
	public static function get_base_url() {
		$base_url = bp_core_get_user_domain( bp_loggedin_user_id() ) . wc4bp_Manager::get_shop_slug() . '/';
		
		return $base_url;
	}
	
	/**
	 * Process the url for given post id
	 *
	 * @param bool $post_id
	 *
	 * @return bool|string
	 */
	public function redirect_link( $post_id = false ) {
		try {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return false;
			}
			if ( empty( $post_id ) ) {
				return false;
			}
			/**
			 * Add more endpoint to avoid the rewrite of the url for the plugin
			 *
			 * @param array String values of the endpoint to by pass the url transform
			 */
			$avoid_woo_endpoints = apply_filters( 'wc4bp_avoid_woo_endpoints', array( 'order-received', 'order-pay' ) );
			global $bp, $wp;
			if ( ( isset( $wp->query_vars['name'] ) && in_array( $wp->query_vars['name'], $avoid_woo_endpoints ) ) ) {
				return false;
			}
			foreach ( $avoid_woo_endpoints as $avoid_woo_endpoint ) {
				if ( isset( $wp->query_vars[ $avoid_woo_endpoint ] ) ) {
					return false;
				}
			}
			if ( ! empty( $bp->pages ) ) {
				//Search in all the actives BPress pages for the current id
				foreach ( $bp->pages as $page_key => $page_data ) {
					//if the current id is in the BP pages, do not redirect the link, maintain the BP link
					if ( intval( $page_data->id ) === intval( $post_id ) ) {
						return false;
					}
				}
				$wc4bp_options = get_option( 'wc4bp_options' );
				if ( ! empty( $wc4bp_options['tab_activity_disabled'] ) ) {
					return false;
				}
				$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
				if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
					$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
				}
				$cart_page_id              = wc_get_page_id( 'cart' );
				$checkout_page_id          = wc_get_page_id( 'checkout' );
				$account_page_id           = wc_get_page_id( 'myaccount' );
				$granted_selected_pages_id = array();
				$granted_wc_pages_id       = array( intval( $account_page_id ) );
				if ( ! empty( $wc4bp_pages_options['selected_pages'] ) ) {
					foreach ( $wc4bp_pages_options['selected_pages'] as $selected_page ) {
						if ( $selected_page['children'] > 0 ) {
							$parent_id                               = intval( $this->get_top_parent_page_id( $selected_page['page_id'] ) );
							$granted_wc_pages_id[]                   = $parent_id;
							$granted_selected_pages_id[ $parent_id ] = $selected_page;
						} else {
							$granted_wc_pages_id[]                                            = intval( $selected_page['page_id'] );
							$granted_selected_pages_id[ intval( $selected_page['page_id'] ) ] = $selected_page;
						}
					}
				}
				if ( ! isset( $wc4bp_options['tab_checkout_disabled'] ) ) {
					$granted_wc_pages_id[] = $checkout_page_id;
				}
				if ( ! isset( $wc4bp_options['tab_cart_disabled'] ) ) {
					$granted_wc_pages_id[] = $cart_page_id;
				}
				if ( in_array( $post_id, $granted_wc_pages_id, true ) ) {
					switch ( $post_id ) {
						case $cart_page_id:
							if ( ! isset( $wc4bp_options['tab_cart_disabled'] ) ) {
								return $this->convert_url( 'cart' );
							}
							break;
						case $checkout_page_id:
							$checkout_url = '';
							if ( ! isset( $wc4bp_options['tab_checkout_disabled'] ) && is_object( WC()->cart ) && ! WC()->cart->is_empty() ) {
								$checkout_url = 'checkout';
							} elseif ( ! isset( $wc4bp_options['tab_checkout_disabled'] ) && ! is_object( WC()->cart ) ) {
								$checkout_url = 'home';
							}
							$order_pay     = isset( $wp->query_vars['order-pay'] ) ? $wp->query_vars['order-pay'] : '';
							$checkout_page = get_post( $checkout_page_id );
							$url           = get_bloginfo( 'url' ) . '/' . $checkout_page->post_name;

							$payment_created_account = isset( $bp->unfiltered_uri[0] ) ? $bp->unfiltered_uri[0] : '';
							if ( isset( $wp->query_vars['order-pay'] ) ) {
								return $url;
							} else {
								/**
								 * Change the checkout url
								 *
								 * @param string The checkout url
								 */
								$checkout_url = apply_filters( 'wc4bp_checkout_page_link', $checkout_url );
							}


							return $this->convert_url( $checkout_url );
							break;
						case $account_page_id:
							if ( ! isset( $wc4bp_options['tab_my_account_disabled'] ) ) {
								return $this->convert_url();
							}
							break;
					}
					if ( ! empty( $granted_selected_pages_id ) ) {
						$parent_post_id = $this->get_top_parent_page_id( $post_id );
						foreach ( $granted_selected_pages_id as $select_page_id => $select_page ) {
							if ( $select_page_id === $parent_post_id ) {
								$post_data  = get_post( $post_id );
								$final_slug = ( $select_page['tab_slug'] !== $post_data->post_name ) ? $select_page['tab_slug'] . '/' . $post_data->post_name : $select_page['tab_slug'];
								
								return $this->convert_url( $final_slug );
							}
						}
					}
					
					return false;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
			
			return false;
		}
	}
	
	private function convert_url( $add_url = '' ) {
		$suffix = '';
		if ( ! empty( $add_url ) ) {
			$suffix = $add_url . '/';
		}
		$link = self::get_base_url() . $suffix;
		if ( 'yes' === get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
			$link = str_replace( 'http:', 'https:', $link );
		}

		/**
		 * Change the redirection link
		 *
		 * @param string The url
		 */
		return apply_filters( 'wc4bp_get_redirect_link', $link );
	}
	
	/**
	 * Change core related urls
	 *
	 * @param $link
	 * @param $id
	 *
	 * @return mixed
	 */
	function wc4bp_page_link_router( $link, $id ) {
		//if user is not logged or is in the backend in exit
		if ( ! is_user_logged_in() || is_admin() ) {
			return $link;
		}
		
		$new_link = $this->redirect_link( $id );
		if ( ! empty( $new_link ) ) {
			$link = $new_link;
		}

		/**
		 * Change the route of the link
		 *
		 * @param string The url
		 */
		return apply_filters( 'wc4bp_router_link', $link );
	}
	
	/**
	 * Redirect core related urls
	 *
	 * @return bool
	 */
	function wc4bp_redirect_to_profile() {
		global $post;
		//if user is not logged or is in the backend in exit
		if ( ! is_user_logged_in() || is_admin() ) {
			return false;
		}
		//if post is empty exit
		if ( empty( $post ) ) {
			return false;
		}
		$link = $this->redirect_link( $post->ID );
		
		if ( ! empty( $link ) ) {
			wp_safe_redirect( $link );
			exit;
		} else {
			return false;
		}
	}
	
	/**
	 * Get the top parent of a post id
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	function get_top_parent_page_id( $post_id ) {
		$ancestors = get_post_ancestors( $post_id );
		// Check if page is a child page (any level)
		if ( $ancestors ) {
			// Grab the ID of top-level page from the tree
			return end( $ancestors );
		} else {
			// Page is the top level, so use  it's own id
			return $post_id;
		}
	}
}
