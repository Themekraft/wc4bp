<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress,WooCommerce
 * @author		svenl77
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

class BPSHOP_Downloads
{
	/**
	 * Initiate the downloads
	 * 
	 * @since 	1.0
	 * @access 	public
	 */
	public function init()
	{
		// remove woocommerce function
		//remove_action( 'init', 'woocommerce_download_product' );
		// then add it back in
		add_action( 'init', 										array( __CLASS__, 'download_product' )	  );

		add_action( 'woocommerce_product_options_downloads', array( __CLASS__, 'add_time_option'  ) 	  );
		add_action( 'save_post', 									array( __CLASS__, 'save_time_option' ), 1 );
	}
	
	/**
	 * Add the time-based option to product pages
	 * 
	 * @since 	1.0
	 * @access 	public
	 */
	public function add_time_option()
	{
		global $post;
		
		$limit = (array) get_post_meta( $post->ID, 'time_limit', true );
		?>
		<p class="form-field">
			<label for="time-limit"><?php _e( 'Time Restriction', 'bpshop' ) ?></label>
			<input type="text" class="short" name="time_limit[length]" id="time-length" value="<?php echo esc_attr( $limit['length'] ) ?>" />

			<select id="time-duration" name="time_limit[duration]">
				<option value="">----</option>
				<option<?php if( $limit['duration'] == 'days'   ) echo ' selected="selected"'; ?> value="days"><?php _e( 'Day(s)', 'bpshop' 	) ?></option>
				<option<?php if( $limit['duration'] == 'weeks'  ) echo ' selected="selected"'; ?> value="weeks"><?php _e( 'Week(s)', 'bpshop' 	) ?></option>
				<option<?php if( $limit['duration'] == 'months' ) echo ' selected="selected"'; ?> value="months"><?php _e( 'Month(s)', 'bpshop' ) ?></option>
				<option<?php if( $limit['duration'] == 'years'  ) echo ' selected="selected"'; ?> value="years"><?php _e( 'Year(s)', 'bpshop' 	) ?></option>
			</select>

			<span class="description"><?php _e( 'Leave blank to disable.', 'bpshop' ) ?></span>
		</p>
		<?php
	}
	
	/**
	 * Save the time-based option
	 * 
	 * @since 	1.0
	 * @access 	public
	 */
	public function save_time_option( $post_id )
	{	
		if( isset( $_POST['time_limit']['length'] ) && absint( $_POST['time_limit']['length'] ) > 0 && isset( $_POST['time_limit']['duration'] ) && in_array( $_POST['time_limit']['duration'], array( 'days', 'weeks', 'months', 'years' ) ) )
			update_post_meta( $post_id, 'time_limit', $_POST['time_limit'] );
	}
	
	/**
	 * Get the proper duration word
	 * 
	 * Probably not needed, but applies to a geeks sense of neatness
	 * 
	 * @since 	1.0
	 * @access 	public
	 */
	public function get_duration( $duration = false, $length = false )
	{
		if( ! $duration || ! $length )
			return false;
		
		switch( $duration )
		{
			case 'days':
				$duration = _n( 'day', 'days', $length );
				break;

			case 'weeks':
				$duration = _n( 'week', 'weeks', $length );
				break;
				
			case 'months':
				$duration = _n( 'month', 'months', $length );
				break;
				
			case 'years':
				$duration = _n( 'year', 'years', $length );
				break;
		}
		
		return $duration;
	}
	
	/**
	 * Get a list of downloadable products
	 * 
	 * Based on woocommerce_customer::get_downloadable_products()
	 * Should really be done via a filter and should probably be its own plugin
	 * as it's BuddyPress independant
	 * 
	 * @since 	1.0
	 * @access 	public
	 * @todo	Pull request for woocommerce team
	 */
	public function get_downloadable_products()
	{
		global $wpdb;
		
		$downloads = array();
		
		//$woocommerce_orders = &new woocommerce_orders();
		//$woocommerce_orders->get_customer_orders( get_current_user_id() );
		
//		if( $woocommerce_orders->orders ) :

$args = array(

'numberposts'     => $recent_orders,
'meta_key'        => '_customer_user',
'meta_value'      => get_current_user_id(),
'post_type'       => 'shop_order',
'post_status'     => 'publish' 

);

$customer_orders = get_posts( $args );

if( $customer_orders ) :

			//foreach( $woocommerce_orders->orders as $order ) :
				
				foreach( $customer_orders as $customer_order ) :
						$order = &new woocommerce_order();
						$order->populate( $customer_order );
				
				if( $order->status == 'completed' ) :
				
					$results = $wpdb->get_results( $wpdb->prepare( "
						SELECT *
						FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions
						WHERE order_key = %s
						AND user_id = %d
						", $order->order_key, get_current_user_id() )
					);
					
					$user_info = get_userdata( get_current_user_id() );

					if( $results ) :
						foreach( $results as $result ) :
							$_product = &new woocommerce_product( $result->product_id );
							
							// we check for an existing time limit here and maybe prevent
							// the product from being added to the available products							
							$downloadable_until = false;
							
							if( $limit = get_post_meta( $_product->id, 'time_limit', true ) ) :
								$duration = self::get_duration( $limit['duration'], $limit['length'] );							
								$downloadable_until = strtotime( '+'. $limit['length'] .' '. $duration , strtotime( $order->order_date ) );
								
								if( $downloadable_until < strtotime( 'now' ) )
									continue;
							endif;

							if ($_product->exists) :
								$download_name = $_product->get_title();
							else :
								$download_name = '#'. $result->product_id;
							endif;
							
							$downloads[] = array(
								'download_url' 		  => add_query_arg( 'download_file', $result->product_id, add_query_arg( 'order', $result->order_key, add_query_arg( 'email', $user_info->user_email, home_url() ) ) ),
								'product_id' 		  => $result->product_id,
								'download_name' 	  => $download_name,
								'order_key' 		  => $result->order_key,
								'downloads_remaining' => $result->downloads_remaining,
								'download_duration'	  => ( ( $downloadable_until ) ? gmdate( get_option( 'date_format' ), $downloadable_until ) : false )
							);
						endforeach;
					endif;						
				endif;					
			endforeach;				
		endif;			

		return $downloads;
	}

	/**
	 * Download a product
	 * 
	 * Based on woocommerce_download_product()
	 * Should really be done via a filter
	 * 
	 * @since 	1.0
	 * @access 	public
	 * @todo	Pull request for woocommerce team
	 */
	public function download_product()
	{
		if( isset( $_GET['download_file'] ) && isset( $_GET['order'] ) && isset( $_GET['email'] ) ) :
	
			global $wpdb;
	
			$download_file = (int) urldecode( $_GET['download_file'] );
			$order = urldecode( $_GET['order'] );
			$email = urldecode( $_GET['email'] );
	
			if( ! is_email( $email ) )
				wp_safe_redirect( home_url() );
	
			$downloads_remaining = $wpdb->get_var( $wpdb->prepare( "
				SELECT downloads_remaining
				FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions
				WHERE user_email = %s
				AND order_key = %s
				AND product_id = %d
			", $email, $order, $download_file ) );
			
			// Restrict a download once time has passed
			if( $limit = (array) get_post_meta( $download_file, 'time_limit', true ) ) :
				
				// get order_id from order_key
				$order_id = (int) $wpdb->get_var( $wpdb->prepare( "
					SELECT post_id
					FROM {$wpdb->postmeta}
					WHERE meta_key = 'order_key'
					AND meta_value = %s
				", $order ) );

				$order = new woocommerce_order( $order_id );

				$duration = self::get_duration( $limit['duration'], $limit['length'] );							
				$downloadable_until = strtotime( '+'. $limit['length'] .' '. $duration , strtotime( $order->order_date ) );

				if( $downloadable_until < strtotime( 'now' ) )
		            wp_die( sprintf( __( 'The time limit for this file has been reached and it cannot be downloaded any more. <a href="%s">Go to homepage &rarr;</a>', 'bpshop' ), home_url() ) );
			endif;

			// Resume default woocommerce function
	        if( $downloads_remaining == NULL ) :
	            wp_die( sprintf( __( 'File not found. <a href="%s">Go to homepage &rarr;</a>', 'woocommerce' ), home_url() ) );
			elseif( $downloads_remaining == '0' ) :
	            wp_die( sprintf( __( 'Sorry, you have reached your download limit for this file. <a href="%s">Go to homepage &rarr;</a>', 'woocommerce' ), home_url() ) );
			else :
				if( $downloads_remaining > 0 ) :
					$wpdb->update( $wpdb->prefix .'woocommerce_downloadable_product_permissions', array(
						'downloads_remaining' => $downloads_remaining - 1,
					), array(
						'user_email' 		  => $email,
						'order_key'			  => $order,
						'product_id' 		  => $download_file
					), array( '%d' ), array( '%s', '%s', '%d' ) );
				endif;
	
				// Download the file
				$file_path = ABSPATH . get_post_meta( $download_file, 'file_path', true );
	
	            $file_path = realpath( $file_path );
	            
	            if( ! file_exists( $file_path ) || is_dir( $file_path ) || ! is_readable( $file_path ) )
	                wp_die( sprintf(__( 'File not found. <a href="%s">Go to homepage &rarr;</a>', 'woocommerce' ), home_url() ) );
	
	            $file_extension = strtolower( substr( strrchr( $file_path, "." ), 1 ) );
	
	            switch( $file_extension ) :
	                case "pdf":
	                	$ctype = "application/pdf";
	                	break;
						
	                case "exe":
	                	$ctype = "application/octet-stream";
	                	break;
						
	                case "zip":
	                	$ctype = "application/zip";
	                	break;
						
	                case "doc":
	                	$ctype = "application/msword";
	                	break;
						
	                case "xls":
	                	$ctype = "application/vnd.ms-excel";
	                	break;
						
	                case "ppt":
	                	$ctype = "application/vnd.ms-powerpoint";
	                	break;
						
	                case "gif":
	                	$ctype = "image/gif";
	                	break;
						
	                case "png":
	                	$ctype = "image/png";
	                	break;
						
	                case "jpe":
	                case "jpeg":
	                case "jpg":
	                	$ctype = "image/jpg";
	                	break;
	                	
	                default:
	                	$ctype = "application/force-download";
	            endswitch;
	
				@ini_set( 'zlib.output_compression', 'Off' );
				@set_time_limit( 0 );
				@session_start();
				@session_cache_limiter( 'none' );
				@set_magic_quotes_runtime( 0 );
				@ob_end_clean();
				@session_write_close();
	
				header( "Pragma: no-cache" );
				header( "Expires: 0" );
				header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
				header( "Robots: none" );
				header( "Content-Type: ". $ctype ."" );
				header( "Content-Description: File Transfer" );
	
	          	if( strstr( $_SERVER['HTTP_USER_AGENT'], "MSIE" ) )
	          	{
				    // workaround for IE filename bug with multiple periods / multiple dots in filename
				    $iefilename = preg_replace( '/\./', '%2e', basename( $file_path ), substr_count( basename( $file_path ), '.' ) - 1 );
				    header( "Content-Disposition: attachment; filename=\"". $iefilename ."\";" );
				} else
				    header( "Content-Disposition: attachment; filename=\"". basename( $file_path ) ."\";" );
	
				header( "Content-Transfer-Encoding: binary" );
	
	            header( "Content-Length: ". @filesize( $file_path ) );
	            @readfile( "$file_path" ) or wp_die( sprintf(__( ' File not found. <a href="%s">Go to homepage &rarr;</a>', 'woocommerce' ), home_url() ) );
				exit;
			endif;
		endif;
	}
}
BPSHOP_Downloads::init();
?>