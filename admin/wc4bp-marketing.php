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

class WC4BP_Marketing extends wc4bp_base {
	public $is_trial;
	
	public function __construct() {
		parent::__construct();
		add_action( 'admin_notices', array( $this, 'notice' ) );
		add_action( 'network_admin_notices', array( $this, 'notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'marketing_script' ), 10 );
	}
	
	public function notice() {
		$message     = '';
		$links       = array();
		$need_notice = false;
		//the plugins is the pro version and the client need to activate his account
		if ( $this->need_account_activation ) {
			$message     = __( 'Look like you have the Premium version of our plugin and the license is still inactive. Please follow the next link to unlock all the power!', 'wc4bp' );
			$links       = array(
				'activation' => array(
					'name'   => __( 'Activate your account', 'wc4bp' ),
					'target' => WC4BP_Loader::getFreemius()->get_account_url( false, array( 'activate_license' => 'true' ) )
				),
				'dismiss' => array(
					'name'   => __( 'Dismiss', 'wc4bp' ),
					'target' => '#'
				)
				
			);
			$need_notice = true;
		}
		if ( $need_notice ) {
			$this->notice_view( $message, $links );
		}
	}
	
	public function marketing_script( $hook ) {
		try {
			wp_enqueue_style( 'wc4bp_admin_revision_css', wc4bp_Manager::assets_path( 'wc4bp-revision', 'css' ) );
			wp_enqueue_script( 'wc4bp_admin_marketing_js', wc4bp_Manager::assets_path( 'wc4bp-marketing' ), array( 'jquery' ), WC4BP_Loader::VERSION );
			wp_localize_script( 'wc4bp_admin_marketing_js', 'wc4bp_admin_marketing_js', array(
				'nonce'   => wp_create_nonce( 'wc4bp_marketing_nonce' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			) );
		}
		catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}
	
	public function notice_view( $message = '', $links = array() ) {
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_marketing.php' );
	}
}
