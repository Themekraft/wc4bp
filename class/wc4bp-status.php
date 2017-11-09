<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC4BP_Status {
	private $status_handler;

	public function __construct() {
		require_once WC4BP_ABSPATH_CLASS_PATH . 'includes/class-wp-plugin-status.php';
		$this->status_handler = WpPluginStatusFactory::build_manager( array(
			'slug' => 'wc4bp-options-page',
		) );
		add_action( 'init', array( $this, 'set_status_options' ), 1, 1 );
		add_filter( 'wp_plugin_status_data', array( $this, 'status_data' ) );
		add_filter( 'wp_plugin_status_append_js', array( $this, 'append_js' ) );
		add_filter( 'wp_plugin_status_header_append_html', array( $this, 'append_header_button' ), 10, 2 );
		add_action( 'wp_ajax_clean_errors_status', array( $this, 'clean_errors_status' ) );
		add_action( 'wp_loaded', array( $this, 'notice_errors' ) );
	}

	public function notice_errors() {
		$errors = WC4BP_Exception_Handler::get_instance()->get_exception_list();
		if ( ! empty( $errors ) ) {
			$message = sprintf( "<a href='%s'>Some issues need your attention, check our Error section.</a>", admin_url( 'admin.php?page=wc4bp-options-page_status#status_error_bookmark' ) );
			$this->admin_notice( $message );
		}
	}

	/**
	 * Add admin notices to single site or multisite
	 *
	 * @param        $message
	 * @param string $type
	 */
	public function admin_notice( $message, $type = 'error' ) {
		if ( is_multisite() ) {
			add_action( 'network_admin_notices', function () use ( $message, $type ) {
				echo '<div class="' . esc_attr( $type ) . '"><b>WC4BP -> WooCommerce BuddyPress Integration</b>: ' . $message . '</div>';
			} );
		} else {
			add_action( 'admin_notices', function () use ( $message, $type ) {
				echo '<div class="' . esc_attr( $type ) . '"><b>WC4BP -> WooCommerce BuddyPress Integration</b>: ' . $message . '</div>';
			} );
		}
	}

	public function clean_errors_status() {
		try {
			if ( ! defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return;
			}
			check_ajax_referer( 'clean_status', 'nonce' );
			$result = WC4BP_Exception_Handler::clean_exceptions();
			wp_send_json( intval( $result ) );
		} catch ( Exception $exception ) {
			WC4BP_Loader::get_exception_handler()->save_exception( $exception->getTrace() );
		}
	}

	public function append_js() {
		$nonce = wp_create_nonce( 'clean_status' );
		$js    = <<<EOD
function clear_error_status(element) {
    jQuery(element).text('Cleaning...');
    var error_table = jQuery('#status_errors');
    jQuery.ajax({
        type: 'POST', url: ajaxurl,
        data: {
            action: 'clean_errors_status',
            nonce: '{$nonce}'
        },
        success: function (response) {
            if (response) {
                response = JSON.parse(response);
                if(response > 0){
                    error_table.hide();
                }
            }
        }
    });
}
EOD;

		return $js;
	}

	public function append_header_button( $section_key, $string_html ) {
		ob_start(); ?>
        <div style="float:right; display: inline; margin-right: 20px;">
            <a></a>
			<?php if ( 'Errors' === $section_key ) : ?>
                <a class="button-primary" onclick="clear_error_status(this);">Clean</a>
			<?php endif; ?>
            <a class="button-primary" onclick="export_status(this);" value="status_values_<?php echo esc_attr( strtolower( sanitize_title( $section_key ) ) ); ?>" id="export_status_<?php echo esc_attr( strtolower( sanitize_title( $section_key ) ) ); ?>">Export</a>
        </div>
		<?php
		$string_buffer = ob_get_clean();

		return $string_buffer;
	}

	public function set_status_options() {
		// Only Check for requirements in the admin
		if ( ! is_admin() ) {
			return;
		}
	}

	public function status_data( $data ) {
		/** @var WC4BP_Exception_Handler $error_handler */
		$error_handler = WC4BP_Exception_Handler::get_instance();
		$errors_list   = $error_handler->get_exception_list();
		$errors        = array();
		$i             = 0;
		foreach ( $errors_list as $time => $item ) {
			$i ++;
			$date                       = date( 'm/d/Y h:m:s', $time );
			$errors[ $date . '-' . $i ] = wp_json_encode( $item );
		}
		if ( ! empty( $errors ) ) {
			$data['Errors'] = $errors;
		}
		$data['WC4BP']              = array(
			'version' => $GLOBALS['wc4bp_loader']->get_version(),
		);
		$wc4bp_options              = get_option( 'wc4bp_options' );
		$all_endpoints              = WC4BP_MyAccount::get_available_endpoints();
		$endpoint_my_account_status = array();
		foreach ( $all_endpoints as $endpoint_key => $endpoint_name ) {
			$endpoint_my_account_status[ $endpoint_key ]                 = $endpoint_name;
			$endpoint_my_account_status[ $endpoint_key . '_off' ]        = ( isset( $wc4bp_options[ 'wc4bp_endpoint_' . $endpoint_key ] ) ) ? 'true' : 'false';
			$post                                                        = WC4BP_MyAccount::get_page_by_name( wc4bp_Manager::get_prefix() . $endpoint_key );
			$endpoint_my_account_status[ $endpoint_key . '_page_exist' ] = ( empty( $post ) ) ? 'false' : 'true';
		}
		$data['Shop Settings']                                    = array();
		$data['My Account Tabs']                                  = $endpoint_my_account_status;
		$data['Shop Tabs']                                        = array();
		$data['Turn off the profile sync']                        = array();
		$data['Overwrite the Content of your Shop Home/Main Tab'] = array();

		return $data;
	}
}