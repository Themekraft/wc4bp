<?php

/**
 * Adding the Admin Page
 * 
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.0
 */ 

add_action( 'admin_menu', 'wc4bp_admin_menu' );

function wc4bp_admin_menu() {
    add_menu_page( 'WooCommerce for BuddyPress', 'WC4BP Options', 'edit_posts', 'wc4bp_options_page', 'wc4bp_screen' );


}

/**
 * The Admin Page
 * 
 * @author Sven Lehnert
 * @package TK Loop Designer 
 * @since 1.0
 */ 
 
function wc4bp_screen() { ?>

    <div class="wrap">

        <div id="icon-themes" class="icon32"><br></div>
        <h2>Loop Designer Setup</h2>
		
		<p style="margin: 30px 0; font-size: 15px;">
		    Need help? <a class="button secondary" href="http://support.themekraft.com/" target="_blank">Documentation</a> <a class="button secondary" href=/#" target="_blank" title="Submit an email support ticket">Ask Question</a>
		    <span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_blank">Themekraft</a>.</span>
        </p>
		
        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-options' ); ?>
            <?php settings_fields( 'wc4bp_options' ); ?>
            <?php do_settings_sections( 'wc4bp_options' ); ?>
            
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
 
add_action( 'admin_init', 'wc4bp_register_admin_settings' );

function wc4bp_register_admin_settings() {
	wp_enqueue_script(
					'alertify_js',
					plugins_url('/resources/alertify/lib/alertify.min.js', __FILE__),
					array( 'jquery' ),
					'',
					true
				);
				wp_enqueue_style('alertify-css', plugins_url('/resources/alertify/themes/alertify.core.css', __FILE__));
				wp_enqueue_style('alertify-default-css', plugins_url('/resources/alertify/themes/alertify.default.css', __FILE__));
	        
    register_setting( 'wc4bp_options', 'wc4bp_options' );
    
    // Settings fields and sections
    add_settings_section(	'section_general'	, ''							, 'wc4bp_general'	, 'wc4bp_options' );
	
	add_settings_field(		'customizer_disabled'	, '<b>Loop Designer</b>'	, 'wc4bp_customizer'	, 'wc4bp_options' , 'section_general' );

}

/**
 * Important notice on top of the screen
 * 
 * @author Sven Lehnert
 * @package TK Loop Designer 
 * @since 1.0
 */ 
 
function wc4bp_general() {
	
    echo '<p><i>This is The Loop Designer! Get your hands on the Loops, loose your selfe in the customizer ;-) </i></p><br>';
			
}

/**
 * Do you want to use the WordPress Customizer? This is the option to turn on/off the WordPress Customizer Support.   
 * 
 * @author Sven Lehnert 
 * @package TK Loop Designer
 * @since 1.0
 */
 
function wc4bp_customizer(){ ?>
	
	<h3>Loop Designer General Settings</h3>

	<p>Not manny options here: </p>
	
	<p>    just one Button to click on ... </p>
		
	<p><a href="<?php echo get_admin_url(); ?>customize.php"  class="button-primary">Jump into the Customizer :-)</a></p>
	
	<br>
		
	<h3>Turn off Customizer Support</h3>
	<p>This will disable the Loop Designer in the Customizer, but your already created loops will still be available. <br>
	This option can be very useful if you finalise your loops and want to stop edeting them. </p>
	<?php 
	 $options = get_option( 'wc4bp_options' );
	 
	 $customizer_disabled = 0;
	 if(isset( $options['customizer_disabled']))
	 	 $customizer_disabled = $options['customizer_disabled'];
	
	 
    ?><b>Turn off Customizer: </b> <input id='checkbox' name='wc4bp_options[customizer_disabled]' type='checkbox' value='1' <?php checked( $customizer_disabled, 1  ) ; ?> /><?php 
	
	submit_button(); 

}

?>