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

class WC4BP_Required_PHP extends wc4bp_requirements {
	
	public function __construct( $text_domain = 'wc4bp_requirements' ) {
		parent::__construct( $text_domain );
	}
	
	/**
	 * Set the plugins requirements
	 *
	 * @return array
	 */
	function getRequirements() {
		$requirements                = array();
		$requirement                 = new WP_PHP_Requirement();
		$requirement->minimumVersion = '5.3.0';
		array_push( $requirements, $requirement );
		$requirement                 = new WP_WordPress_Requirement();
		$requirement->minimumVersion = '4.6.2';
		array_push( $requirements, $requirement );
		return $requirements;
	}
}