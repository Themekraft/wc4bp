<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Class Request_Helper
 *
 * Generic helper for requests
 */
class Request_Helper {
	/**
	 * Get a POST parameter and sanitize
	 *
	 * @param        $param
	 * @param string $default
	 * @param string $sanitize
	 *
	 * @return mixed
	 */
	public static function get_post_param( $param, $default = '', $sanitize = '' ) {
		return self::get_simple_request( array(
			'type'     => 'post',
			'param'    => $param,
			'default'  => $default,
			'sanitize' => $sanitize,
		) );
	}
	/**
	 * Get a GET parameters and sanitize
	 *
	 * @param        $param
	 * @param string $sanitize
	 * @param string $default
	 *
	 * @return mixed
	 */
	public static function simple_get( $param, $sanitize = 'sanitize_text_field', $default = '' ) {
		return self::get_simple_request( array(
			'type'     => 'get',
			'param'    => $param,
			'default'  => $default,
			'sanitize' => $sanitize,
		) );
	}
	/**
	 * Process a Request
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public static function get_simple_request( $args ) {
		$defaults = array(
			'param'    => '',
			'default'  => '',
			'type'     => 'get',
			'sanitize' => 'sanitize_text_field',
		);
		$args     = wp_parse_args( $args, $defaults );
		$value    = $args['default'];
		if ( 'get' === $args['type'] ) {
			if ( $_GET && isset( $_GET[ $args['param'] ] ) ) {
				$value = $_GET[ $args['param'] ];
			}
		} elseif ( 'post' === $args['type'] ) {
			if ( isset( $_POST[ $args['param'] ] ) ) {
				$value = stripslashes_deep( maybe_unserialize( $_POST[ $args['param'] ] ) );
			}
		} else {
			if ( isset( $_REQUEST[ $args['param'] ] ) ) {
				$value = $_REQUEST[ $args['param'] ];
			}
		}
		self::sanitize_value( $args['sanitize'], $value );
		return $value;
	}
	/**
	 * @param $value
	 *
	 * @return string
	 */
	public static function preserve_backslashes( $value ) {
		// If backslashes have already been added, don't add them again
		if ( strpos( $value, '\\\\' ) === false ) {
			$value = addslashes( $value );
		}
		return $value;
	}
	/**
	 * Sanitize values
	 *
	 * @param $sanitize
	 * @param $value
	 */
	public static function sanitize_value( $sanitize, &$value ) {
		if ( ! empty( $sanitize ) ) {
			if ( is_array( $value ) ) {
				$temp_values = $value;
				foreach ( $temp_values as $k => $v ) {
					Request_Helper::sanitize_value( $sanitize, $value[ $k ] );
				}
			} else {
				$value = call_user_func( $sanitize, $value );
			}
		}
	}
	/**
	 * Sanitize the request
	 *
	 * @param $sanitize_method
	 * @param $values
	 */
	public static function sanitize_request( $sanitize_method, &$values ) {
		$temp_values = $values;
		foreach ( $temp_values as $k => $val ) {
			if ( isset( $sanitize_method[ $k ] ) ) {
				$values[ $k ] = call_user_func( $sanitize_method[ $k ], $val );
			}
		}
	}
	/**
	 * Sanitize array
	 *
	 * @param $values
	 */
	public static function sanitize_array( &$values ) {
		$temp_values = $values;
		foreach ( $temp_values as $k => $val ) {
			$values[ $k ] = wp_kses_post( $val );
		}
	}
}