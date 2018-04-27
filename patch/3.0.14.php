<?php

/**
 * @package        WordPress
 * @subpackage     BuddyPress, WooCommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC4BP_3013' ) ) {
	/**
	 * Class WC4BP_3013
	 *
	 * This class is a patch to fix the typo 'wc4pb' with the correct post name. More details in the https://github.com/Themekraft/wc4bp/issues/135
	 */
	class WC4BP_3013 {
		private $wc4bp_upgrade_patch_options;

		public function __construct( $wc4bp_upgrade_patch_options ) {
			$this->wc4bp_upgrade_patch_options = $wc4bp_upgrade_patch_options;
			$this->init();
		}

		public function init() {
			try {
				if ( $this->continue_process() ) {
					$posts = $this->get_pages();
					if ( ! empty( $posts ) ) {
						foreach ( $posts as $post ) {
							if ( isset( $post->ID ) ) {
								$post = get_post( intval( $post->ID ) );
								wp_delete_post( $post->ID );
								wp_cache_delete( 'wc4bp_get_page_by_name_' . $post->post_name, 'wc4bp' );
							}
						}
						$wc4bp_options = get_option( 'wc4bp_options' );
						if ( isset( $wc4bp_options['my_account_prefix'] ) ) {
							unset( $wc4bp_options['my_account_prefix'] );
							update_option( 'wc4bp_options', $wc4bp_options );
						}
						WC4BP_MyAccount::clean_my_account_cached();

						return $this->finish_process();
					}
				}
			} catch ( Exception $exception ) {
				WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
			}

			return false;
		}

		/**
		 * Get a list of pages with post_name start with 'wc4pb'
		 *
		 * @return array
		 */
		private function get_pages() {
			try {
				global $wpdb;
				$posts = $wpdb->get_results( "SELECT ID FROM kraft_posts WHERE post_name LIKE 'wc4pb_%' AND post_type='page'" );
				if ( ! empty( $posts ) ) {
					return $posts;
				}
			} catch ( Exception $exception ) {
				WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
			}

			return array();
		}

		/**
		 * Determine if the process can continue
		 *
		 * @return bool
		 */
		public function continue_process() {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX && defined( 'WP_ADMIN' ) && WP_ADMIN ) {
				return false;
			} else {
				return ( empty( $this->wc4bp_upgrade_patch_options ) && empty( $this->wc4bp_upgrade_patch_options[ WC4BP_Loader::VERSION ] ) );
			}
		}

		/**
		 * Mark this process as finished
		 *
		 * @return bool
		 */
		public function finish_process() {
			$wc4bp_upgrade_patch[ WC4BP_Loader::VERSION ] = 1;

			return update_option( 'wc4bp_upgrade', $wc4bp_upgrade_patch );
		}

	}
}