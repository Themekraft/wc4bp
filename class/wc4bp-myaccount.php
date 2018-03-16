<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC4BP_MyAccount {
	
	public static $prefix;
	protected $current_title;
	private $wc4bp_options;
	
	public function __construct() {
		try {
			$this->wc4bp_options         = get_option( 'wc4bp_options' );
			$is_shop_disable             = ! isset( $this->wc4bp_options['tab_activity_disabled'] );
			$is_woo_redirection_disabled = ! isset( $this->wc4bp_options['tab_my_account_disabled'] );
			if ( ! $is_shop_disable || ( $is_shop_disable || ! $is_woo_redirection_disabled ) ) {
				add_filter( 'woocommerce_get_view_order_url', array( $this, 'get_view_order_url' ), 10, 2 );
			}
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	public function get_base_url( $endpoint = '' ) {
		try {
			if ( ! empty( $endpoint ) ) {
				$endpoint = '/' . $endpoint;
			}
			
			return bp_core_get_user_domain( bp_loggedin_user_id() ) . wc4bp_Manager::get_shop_slug() . $endpoint;
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
			
			return $endpoint;
		}
	}
	
	/**
	 * Change url for view order endpoint.
	 *
	 * @param          $view_order_url
	 * @param WC_Order $order
	 *
	 * @return string
	 */
	public function get_view_order_url( $view_order_url, $order ) {
		try {
			$is_bp_component = bp_is_current_component( wc4bp_Manager::get_shop_slug() );
			if ( $is_bp_component && ! isset( $this->wc4bp_options['wc4bp_endpoint_orders'] ) ) {
				$view_order_url = wc_get_endpoint_url( 'view-order', $order->get_id(), $this->get_base_url( 'orders' ) );
			}
			
			return $view_order_url;
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
			
			return $view_order_url;
		}
	}
	
	/**
	 * Redirect WC my Account to BP member profile page
	 *
	 * @param $permalink
	 *
	 * @return string
	 */
	public function my_account_page_permalink__premium_only( $permalink ) {
		$result = $permalink;
		try {
			global $bp;
			
			$wc4bp_endpoint = WC4BP_MyAccount::get_active_endpoints__premium_only();
			
			if ( ! empty( $wc4bp_endpoint ) ) {
				foreach ( $wc4bp_endpoint as $active_page_key => $active_page_name ) {
					if ( $bp->current_action === $active_page_key ) {
						$result = wc4bp_redirect::get_base_url() . $bp->current_action;
						break;
					}
				}
			}
			
			return $result;
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
			
			return $permalink;
		}
	}
	
	public static function add_all_endpoints__premium_only() {
		try {
			$available_endpoints = self::get_available_endpoints();
			if ( ! empty( $available_endpoints ) ) {
				foreach ( $available_endpoints as $end_point_key => $end_point_value ) {
					$page_name = wc4bp_Manager::get_prefix() . $end_point_key;
					$post      = self::get_page_by_name( $page_name );
					if ( empty( $post ) ) {
						$r = wp_insert_post(
							array(
								'comment_status' => 'closed',
								'ping_status'    => 'closed',
								'post_title'     => $end_point_value,
								'post_name'      => $page_name,
								'post_content'   => self::get_page_content__premium_only( $end_point_key ),
								'post_status'    => 'publish',
								'post_type'      => 'page',
								'meta_input'     => array(
									'wc4bp-my-account-template' => 1,
								),
							)
						);
					}
				}
			}
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	public static function remove_all_endpoints__premium_only() {
		try {
			$available_endpoints = self::get_available_endpoints();
			if ( ! empty( $available_endpoints ) ) {
				foreach ( $available_endpoints as $end_point_key => $end_point_value ) {
					$post = self::get_page_by_name( wc4bp_Manager::get_prefix() . $end_point_key );
					if ( ! empty( $post ) ) {
						wp_delete_post( $post->ID, true );
						wp_cache_delete( 'wc4bp_get_page_by_name_' . $post->post_name, 'wc4bp' );
					}
				}
				self::clean_my_account_cached();
			}
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	public static function clean_my_account_cached() {
		wp_cache_delete( 'wc4bp_get_active_endpoints', 'wc4bp' );
		wp_cache_delete( 'wc4bp_get_available_endpoints', 'wc4bp' );
		wp_cache_delete( 'wc4bp_my_account_prefix', 'wc4bp' );
	}
	
	/**
	 * Get my account pages content
	 *
	 * @param string $end_point_key
	 *
	 * @return array|String
	 */
	public static function get_page_content__premium_only( $end_point_key = '' ) {
		try {
			$result    = array();
			$available = self::get_available_endpoints();
			if ( ! empty( $available ) ) {
				if ( empty( $end_point_key ) ) {
					foreach ( $available as $available_key => $available_value ) {
						$result = array_merge( $result, array(
							$available_key => apply_filters( 'wc4bp_woocommerce_endpoint_content_' . $available_key, '[' . $available_key . ']' ),
						) );
					}
				} else {
					if ( ! empty( $available[ $end_point_key ] ) ) {
						$result = apply_filters( 'wc4bp_woocommerce_endpoint_content_' . $end_point_key, '[' . $end_point_key . ']' );
					}
				}
			}
			
			return $result;
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
			
			return array();
		}
	}
	
	public static function get_page_by_name( $post_name, $output = OBJECT ) {
		try {
			global $wpdb;
			$result = wp_cache_get( 'wc4bp_get_page_by_name_' . $post_name, 'wc4bp' );
			if ( false === $result ) {
				$post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_name=%s AND post_type=%s", array( $post_name, 'page' ) ) );
				if ( $post ) {
					$post_result = get_post( $post, $output );
					wp_cache_add( 'wc4bp_get_page_by_name_' . $post_name, $post_result, 'wc4bp' );
					
					return $post_result;
				}
			}
			
			return $result;
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
		
		return null;
	}
	
	public static function get_active_endpoints__premium_only() {
		try {
			$result    = array();
			$available = self::get_available_endpoints();
			if ( ! empty( $available ) ) {
				$result = wp_cache_get( 'wc4bp_get_active_endpoints', 'wc4bp' );
				if ( false === $result ) {
					$wc4bp_options = get_option( 'wc4bp_options' );
					foreach ( $available as $end_point_key => $end_point_value ) {
						if ( empty( $wc4bp_options[ 'wc4bp_endpoint_' . $end_point_key ] ) ) {
							$result[ $end_point_key ] = $end_point_value;
						}
					}
					wp_cache_add( 'wc4bp_get_active_endpoints', $result, 'wc4bp' );
				}
			}
			
			return $result;
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
			
			return array();
		}
	}
	
	/**
	 * @return array
	 */
	public static function get_available_endpoints() {
		try {
			$end_points = wp_cache_get( 'wc4bp_get_available_endpoints', 'wc4bp' );
			if ( false === $end_points ) {
				$woo_endpoints = array(
					'orders'          => get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' ),
					'downloads'       => get_option( 'woocommerce_myaccount_downloads_endpoint', 'downloads' ),
					'edit-address'    => get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ),
					'payment-methods' => get_option( 'woocommerce_myaccount_payment_methods_endpoint', 'payment-methods' ),
					'edit-account'    => get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' ),
				);
				
				$end_points = array(
					'orders'          => __( 'Orders', 'woocommerce' ),
					'downloads'       => __( 'Downloads', 'woocommerce' ),
					'edit-address'    => __( 'Addresses', 'woocommerce' ),
					'payment-methods' => __( 'Payment methods', 'woocommerce' ),
					'edit-account'    => __( 'Account details', 'woocommerce' ),
				);
				
				// Remove missing endpoints.
				foreach ( $woo_endpoints as $endpoint_id => $endpoint ) {
					if ( empty( $endpoint ) ) {
						unset( $end_points[ $endpoint_id ] );
					}
				}
				wp_cache_add( 'wc4bp_get_available_endpoints', $end_points, 'wc4bp' );
			}
			
			return apply_filters( 'wc4bp_add_endpoint', $end_points );
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
			
			return array();
		}
	}
}
