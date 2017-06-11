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

class wc4bp_admin_pages extends wc4bp_base {
	
	/**
	 * The Admin Page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
	public function wc4bp_screen_pages($active_tab) {
	    $this->wc4bp_register_admin_pages_settings();
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_pages_screen_pages.php' );
	}
	
	/**
	 * Register the admin settings
	 *
	 * @author Sven Lehnert
	 * @package TK Loop Designer
	 * @since 1.0
	 */
	public function wc4bp_register_admin_pages_settings() {
		add_settings_section( 'section_general1', '', '', 'wc4bp_options_pages' );
		add_settings_field( 'template', __( '<b>Change the page template to be used for the attached pages.</b>', 'wc4bp' ), array( $this, 'wc4bp_page_template' ), 'wc4bp_options_pages', 'section_general1' );
		
		// Settings fields and sections
		add_settings_section( 'section_general', '', array( $this, 'wc4bp_shop_pages_add' ), 'wc4bp_options_pages' );
	}
	
	/**
	 * View to select the page view template by default
	 */
	public function wc4bp_page_template() {
		$wc4bp_options = get_option( 'wc4bp_options_pages' );
		
		$page_template = '';
		if ( ! empty( $wc4bp_options['page_template'] ) ) {
			$page_template = $wc4bp_options['page_template'];
		}
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'main/html_admin_page_template.php' );
		
		submit_button();
	}
	
	public function wc4bp_shop_pages_add() {
		$this->wc4bp_get_forms_table();
		
	}
	
	public function wc4bp_shop_pages_rename() {
		$options = get_option( 'wc4bp_options' );
		
		$shop_main_nav = '';
		if ( isset( $options['shop_main_nav'] ) ) {
			$shop_main_nav = $options['shop_main_nav'];
		}
		
		$cart_sub_nav = '';
		if ( isset( $options['cart_sub_nav'] ) ) {
			$cart_sub_nav = $options['cart_sub_nav'];
		}
		
		$history_sub_nav = '';
		if ( isset( $options['history_sub_nav'] ) ) {
			$history_sub_nav = $options['history_sub_nav'];
		}
		
		$track_sub_nav = '';
		if ( isset( $options['track_sub_nav'] ) ) {
			$track_sub_nav = $options['track_sub_nav'];
		}
		
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'pages/html_admin_pages_shop_pages_rename.php' );
	}
	
	
	public function wc4bp_get_forms_table() {
		//6$wc4bp_options			= get_option( 'wc4bp_options' );
		$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
		if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
			$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
		}
		
		// echo '<pre>';
		// print_r($wc4bp_pages_options);
		// echo '</pre>';
		?>
        <style type="text/css">
            .wc4bp_editinline {
                color: #bc0b0b;
                cursor: pointer;
            }

            table #the-list tr .wc4bp-row-actions {
                opacity: 0
            }

            table #the-list tr:hover .wc4bp-row-actions {
                opacity: 1
            }

            table.wp-list-table th.manage-column {
                width: auto;
                padding: 20px 0px 20px 10px;
            }

        </style>
		<?php
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'pages/html_admin_pages_forms_table.php' );
		
	}
	
	public function wc4bp_thickbox_page_form() {
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'pages/html_admin_pages_thickbox.php' );
		//$options = get_option( 'wc4bp_options' );
	}
	
	public static function wc4bp_add_edit_entry_form_call( $edit = '' ) {
		$wc4bp_page_id = '';
		$tab_name      = '';
		$position      = '';
		$main_nav      = '';
		
		if ( isset( $_POST['wc4bp_tab_slug'] ) ) {
			$wc4bp_tab_slug = sanitize_text_field( $_POST['wc4bp_tab_slug'] );
		}
		
		$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
		if ( ! empty( $wc4bp_pages_options ) && is_string( $wc4bp_pages_options ) ) {
			$wc4bp_pages_options = json_decode( $wc4bp_pages_options, true );
		}
		
		$children = 0;
		$page_id  = '';
		if ( isset( $wc4bp_tab_slug ) ) {
			if ( isset( $wc4bp_pages_options['selected_pages'][ $wc4bp_tab_slug ]['tab_name'] ) ) {
				$tab_name = $wc4bp_pages_options['selected_pages'][ $wc4bp_tab_slug ]['tab_name'];
			}
			
			if ( isset( $wc4bp_pages_options['selected_pages'][ $wc4bp_tab_slug ]['children'] ) ) {
				$children = $wc4bp_pages_options['selected_pages'][ $wc4bp_tab_slug ]['children'];
			}
			
			if ( isset( $wc4bp_pages_options['selected_pages'][ $wc4bp_tab_slug ]['position'] ) ) {
				$position = $wc4bp_pages_options['selected_pages'][ $wc4bp_tab_slug ]['position'];
			}
			
			if ( isset( $wc4bp_pages_options['selected_pages'][ $wc4bp_tab_slug ]['page_id'] ) ) {
				$page_id = $wc4bp_pages_options['selected_pages'][ $wc4bp_tab_slug ]['page_id'];
			}
		}
//        echo $wc4bp_page_id;
		$args = array(
			'echo'             => true,
			'sort_column'      => 'post_title',
			'show_option_none' => __( 'none', 'wc4bp' ),
			'name'             => "wc4bp_page_id",
			'class'            => 'postform',
			'selected'         => $page_id
		);
		include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'pages/html_admin_pages_edit_entry.php' );
	}
	
	public function wc4bp_add_edit_entry_form( $edit = '' ) {
		self::wc4bp_add_edit_entry_form_call( $edit );
	}
}