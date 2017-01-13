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

class wc4bp_admin_pages {
    
	public function __construct() {
		add_action( 'admin_init', array( $this, 'wc4bp_register_admin_pages_settings' ) );
	}
	
	/**
	 * The Admin Page
	 *
	 * @author Sven Lehnert
	 * @package WC4BP
	 * @since 1.3
	 */
	public function wc4bp_screen_pages() {
		include_once( dirname( __FILE__ ) . '\views\html_admin_pages_screen_pages.php' );
		?>
		<div class="wrap">
        <form method="post" action="options.php">
			<?php wp_nonce_field( 'update-options' ); ?>
			<?php settings_fields( 'wc4bp_options_pages' ); ?>
			<?php do_settings_sections( 'wc4bp_options_pages' ); ?>
        </form>
        </div><?php
	}
	
	/**
	 * Register the admin settings
	 *
	 * @author Sven Lehnert
	 * @package TK Loop Designer
	 * @since 1.0
	 */
	public function wc4bp_register_admin_pages_settings() {
		
		register_setting( 'wc4bp_options_pages', 'wc4bp_options_pages' );
		
		// Settings fields and sections
		add_settings_section( 'section_general', '', array( $this, 'wc4bp_shop_pages_add' ), 'wc4bp_options_pages' );
		
		//add_settings_field(		'pages_add'	, '<b>Add New pages</b>' , 'wc4bp_shop_pages_add'	, 'wc4bp_options_pages' , 'section_general' );
		
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

		include_once( dirname( __FILE__ ) . '\views\html_admin_pages_shop_pages_rename.php' );
		
		submit_button();
	}
	
	
	public function wc4bp_get_forms_table() {
		//6$wc4bp_options			= get_option( 'wc4bp_options' );
		$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
		
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
		include_once( dirname( __FILE__ ) . '\views\html_admin_pages_forms_table.php' );
	}
	
	public function wc4bp_thickbox_page_form() {
		//$options = get_option( 'wc4bp_options' );
		?>

        <div style="margin: 0 0 20px 0;">
            <input alt="#TB_inline?height=300&amp;width=400&amp;inlineId=add_page"
                   title="Add an existing page to your BuddyPress member profiles"
                   class="button button-secondary cptfbp_thickbox cptfbp_thickbox_add " type="button"
                   value="Add a page to your BuddyPress Member Profiles"/>
        </div>
        <div id="add_page" style="display:none"></div>
		
		<?php
	}
	
	public static function wc4bp_add_edit_entry_form_call( $edit = '' ) {
		$wc4bp_page_id = '';
		$tab_name      = '';
		$position      = '';
		$main_nav      = '';
		
		if ( isset( $_POST['wc4bp_tab_slug'] ) ) {
			$wc4bp_tab_slug = $_POST['wc4bp_tab_slug'];
		}
		
		$wc4bp_pages_options = get_option( 'wc4bp_pages_options' );
		
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
		); ?>

        <p><b><?php _e('Choose an existing page', 'wc4bp'); ?></b><br>
			<?php wp_dropdown_pages( $args ); ?>
            <input id='wc4bp_children' name='wc4bp_children' type='checkbox' value='1'/ <?php checked( $children, 1 ); ?>>&nbsp;<b><?php _e('Include
                Children?', 'wc4bp'); ?></b></p>
        <p><b><?php _e('Tab Name', 'wc4bp'); ?></b><i><?php _e('If empty same as Pagename', 'wc4bp'); ?></i><br>

            <input id='wc4bp_tab_name' name='wc4bp_tab_name' type='text' value='<?php echo $tab_name ?>'/></p>
        <p><b><?php _e('Position', 'wc4bp'); ?></b><br>
            <small><i><?php _e('Just enter a number like 1, 2, 3..', 'wc4bp'); ?></i></small>
            <br>
            <input id='wc4bp_position' name='wc4bp_position' type='text' value='<?php echo $position ?>'/></p>
		
		
		<?php if ( isset( $wc4bp_tab_slug ) ) {
			echo '<input type="hidden" id="wc4bp_tab_slug" value="' . $wc4bp_tab_slug . '" />';
		}
		?><input type="button" value="Save" name="add_cpt4bp_page" class="button add_cpt4bp_page btn"><?php
	}
	
	public function wc4bp_add_edit_entry_form( $edit = '' ) {
		self::wc4bp_add_edit_entry_form_call( $edit );
		
	}
}