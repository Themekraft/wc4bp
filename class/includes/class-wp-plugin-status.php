<?php
if ( ! class_exists( 'WpPluginStatus100', false ) ) {
	/**
	 * Class WpPluginStatus10
	 *
	 * @version 1.0.0
	 */
	class WpPluginStatus100 {
		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;
		/**
		 * @var String the plugins slug where all be deployed
		 */
		private $plugin_slug;
		/**
		 * @var String the parent slug
		 */
		private $parent_slug;

		/**
		 * WpPluginStatus100 constructor.
		 *
		 * @param $args
		 */
		public function __construct( $args ) {
			//Check for required parameters
			if ( ! empty( $args ) ) {
				if ( isset( $args['slug'] ) ) {
					$this->plugin_slug = $args['slug'] . '_status';
					$this->parent_slug = $args['slug'];
				} else {
					throw new InvalidArgumentException( 'slug is a required parameter' );
				}
			} else {
				throw new InvalidArgumentException( 'You need to set the required parameters to make this work' );
			}
			add_action( 'admin_menu', array( $this, 'add_status_menu' ) );
			add_filter( 'wp_plugin_status_view_values', array( $this, 'render_items' ), 10, 1 );
		}

		/**
		 * Return an instance of this class.
		 *
		 * @param $args
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance( $args ) {
			// If the single instance hasn't been set, set it now.
			if ( empty( self::$instance ) ) {
				self::$instance = new self( $args );
			}

			return self::$instance;
		}

		public function render_items( $render_array ) {
			switch ( $render_array['key'] ) {
				case 'wp_memory_limit':
					$render_array['value'] = size_format( $render_array['value'] );
					break;
				case 'wp_debug_mode':
				case 'wp_cron':
					$render_array['value'] = ( $render_array['value'] ) ? 'true' : 'false';
					break;
			}

			return $render_array;
		}

		public function get_basic_status() {
			global $wpdb;
			// WP memory limit
			$wp_memory_limit = $this->normalize_to_num( WP_MEMORY_LIMIT );
			if ( function_exists( 'memory_get_usage' ) ) {
				$wp_memory_limit = max( $wp_memory_limit, $this->normalize_to_num( @ini_get( 'memory_limit' ) ) );
			}

			// Return all environment info. Described by JSON Schema.
			return apply_filters( 'wp_plugin_status_data', array(
				'WordPress environment' => array(
					'home_url'               => get_option( 'home' ),
					'site_url'               => get_option( 'siteurl' ),
					'wp_version'             => get_bloginfo( 'version' ),
					'wp_multisite'           => is_multisite(),
					'wp_memory_limit'        => $wp_memory_limit,
					'wp_debug_mode'          => ( defined( 'WP_DEBUG' ) && WP_DEBUG ),
					'wp_cron'                => ! ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ),
					'language'               => get_locale(),
					'server_info'            => $_SERVER['SERVER_SOFTWARE'],
					'php_version'            => phpversion(),
					'php_max_execution_time' => ini_get( 'max_execution_time' ),
					'php_max_input_vars'     => ini_get( 'max_input_vars' ),
					'mysql_version'          => ( ! empty( $wpdb->is_mysql ) ? $wpdb->db_version() : '' ),
					'default_timezone'       => date_default_timezone_get(),
				),
			) );
		}

		public function add_status_menu() {
			add_submenu_page( $this->parent_slug, 'Status', 'Status', 'manage_options', $this->plugin_slug, array(
				$this,
				'status_view',
			) );
		}

		public function get_section_id( $title ) {
			return strtolower( esc_attr( sanitize_title( $title ) ) );
		}

		public function status_view() {
			$active_tab = 'generic';
			$result     = Request_Helper::simple_get( 'tab' );
			if ( ! empty( $result ) ) {
				$get_tabs = $result;
				if ( ! empty( $get_tabs ) && ( 'generic' === $get_tabs || 'status' === $get_tabs || 'tools' === $get_tabs ) ) {
					$active_tab = $get_tabs;
				}
			}
			?>
            <h2 class="nav-tab-wrapper status">
                <a href="?page=<?php echo esc_attr( $this->plugin_slug ); ?>&tab=status"
                   class="nav-tab <?php echo 'generic' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Status' ); ?></a>
            </h2>
			<?php
			switch ( $active_tab ) {
				case 'generic';
					$data = $this->get_basic_status();
					?>
                    <p><?php echo esc_attr( apply_filters( 'wp_plugin_status_view_description', 'This is the basic information from your system' ) ); ?></p>
                    <style>
                        table.wc_status_table {
                            margin-bottom: 1em;
                        }

                        table.wc_status_table h2 {
                            font-size: 14px;
                            margin: 0;
                        }

                        table.wc_status_table tr:nth-child(2n) th,
                        table.wc_status_table tr:nth-child(2n) td {
                            background: #fcfcfc;
                        }

                        table.wc_status_table th {
                            font-weight: 700;
                            padding: 9px;
                        }

                        table.wc_status_table td:first-child {
                            width: 33%;
                        }

                        table.wc_status_table td.help {
                            width: 1em;
                        }

                        table.wc_status_table td {
                            padding: 9px;
                            font-size: 1.1em;
                        }

                        table.wc_status_table td mark {
                            background: transparent none;
                        }

                        table.wc_status_table td mark.yes {
                            color: green;
                        }

                        table.wc_status_table td mark.no {
                            color: #999;
                        }

                        table.wc_status_table td mark.error {
                            color: red;
                        }

                        table.wc_status_table td ul {
                            margin: 0;
                        }

                        table.wc_status_table .help_tip {
                            cursor: help;
                        }

                        .woocommerce-help-tip::after {
                            font-family: Dashicons;
                            speak: none;
                            font-weight: 400;
                            text-transform: none;
                            line-height: 1;
                            -webkit-font-smoothing: antialiased;
                            text-indent: 0px;
                            position: absolute;
                            top: 0px;
                            left: 0px;
                            width: 100%;
                            height: 100%;
                            text-align: center;
                            content: "";
                            cursor: help;
                            font-variant: normal normal;
                            margin: 0px;
                        }

                        .woocommerce-help-tip {
                            color: #666;
                            display: inline-block;
                            font-size: 1.1em;
                            font-style: normal;
                            height: 16px;
                            line-height: 16px;
                            position: relative;
                            vertical-align: middle;
                            width: 16px;
                        }
                    </style>
                    <script>
                        function IsJsonString(str) {
                            try {
                                JSON.parse(str);
                            } catch (e) {
                                return false;
                            }
                            return true;
                        }

                        function export_status(element) {
                            var final_result = [];
                            var btn_export = jQuery(element);
                            jQuery('tr.' + btn_export.attr('value')).each(function (position, item) {
                                var result = {};
                                var value = jQuery(item).find('.status_value').text();
                                result['title'] = jQuery(item).find('.status_title').text();
                                result['value'] = IsJsonString(value) ? JSON.parse(value) : value;
                                final_result.push(result);
                            });
                            var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(final_result));
                            btn_export.attr("href", dataStr);
                            btn_export.attr("download", "status.json");
                        }
						<?php echo apply_filters( 'wp_plugin_status_append_js', '' ); ?>
                    </script>
					<?php foreach ( $data as $section_key => $section_values ): ?>
                    <table class="wc_status_table widefat" cellspacing="0" id="status_<?php echo $this->get_section_id( $section_key ); ?>">
                        <thead>
                        <tr>
                            <th colspan="2"><h2 style="float:left; display: inline"><?php echo esc_attr( $section_key ); ?></h2>
								<?php echo apply_filters( 'wp_plugin_status_header_append_html', $this->export_html( $section_key ), $section_key ); ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ( $section_values as $key => $value ):
							$to_render = apply_filters( 'wp_plugin_status_view_values', array(
								'key'   => $key,
								'value' => $value,
							) );
							?>
                            <tr class="status_values_<?php echo $this->get_section_id( $section_key ); ?>">
                                <td class="status_title"><?php echo esc_attr( $to_render['key'] ); ?>:</td>
                                <td class="status_value"><?php echo esc_attr( $to_render['value'] ); ?></td>
                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
				<?php endforeach; ?>
					<?php
					break;
			}
		}

		private function export_html( $section_key ) {
			ob_start(); ?>
            <div style="float:right; display: inline; margin-right: 20px;">
                <a class="button-primary" onclick="export_status(this);" value="status_values_<?php echo esc_attr( strtolower( sanitize_title( $section_key ) ) ); ?>" id="export_status_<?php echo esc_attr( strtolower( sanitize_title( $section_key ) ) ); ?>">Export</a>
            </div>
			<?php
			return ob_get_clean();
		}

		function normalize_to_num( $size ) {
			$l   = substr( $size, - 1 );
			$ret = substr( $size, 0, - 1 );
			switch ( strtoupper( $l ) ) {
				case 'P':
					$ret *= 1024;
				case 'T':
					$ret *= 1024;
				case 'G':
					$ret *= 1024;
				case 'M':
					$ret *= 1024;
				case 'K':
					$ret *= 1024;
			}

			return $ret;
		}
	}
}
if ( ! class_exists( 'WpPluginStatusFactory', false ) ) {
	class WpPluginStatusFactory {
		protected static $class_versions = array();
		protected static $sorted = false;

		/**
		 * Create a new instance of WpPluginStatus.
		 *
		 * @see WpPluginStatus10::__construct()
		 *
		 * @param $args       array Settings
		 *
		 * @return \WpPluginStatus100
		 */
		public static function build_manager( $args ) {
			$class = self::get_latest_class_version( 'WpPluginStatus' );

			return new $class( $args );
		}

		/**
		 * Get the specific class name for the latest available version of a class.
		 *
		 * @param string $class
		 *
		 * @return string|null
		 */
		public static function get_latest_class_version( $class ) {
			if ( ! self::$sorted ) {
				self::sort_versions();
			}
			if ( isset( self::$class_versions[ $class ] ) ) {
				return reset( self::$class_versions[ $class ] );
			} else {
				return null;
			}
		}

		/**
		 * Sort available class versions in descending order (i.e. newest first).
		 */
		protected static function sort_versions() {
			foreach ( self::$class_versions as $class => $versions ) {
				uksort( $versions, array( __CLASS__, 'compare_versions' ) );
				self::$class_versions[ $class ] = $versions;
			}
			self::$sorted = true;
		}

		protected static function compare_versions( $a, $b ) {
			return - version_compare( $a, $b );
		}

		/**
		 * Register a version of a class.
		 *
		 * @access private This method is only for internal use by the library.
		 *
		 * @param string $general_class Class name without version numbers, e.g. 'WpPluginStatus'.
		 * @param string $versioned_class Actual class name, e.g. 'WpPluginStatus100'.
		 * @param string $version Version number, e.g. '1.0.0'.
		 */
		public static function add_version( $general_class, $versioned_class, $version ) {
			if ( ! isset( self::$class_versions[ $general_class ] ) ) {
				self::$class_versions[ $general_class ] = array();
			}
			self::$class_versions[ $general_class ][ $version ] = $versioned_class;
			self::$sorted                                       = false;
		}
	}
}
WpPluginStatusFactory::add_version( 'WpPluginStatus', 'WpPluginStatus100', '1.0.0' );
