<?php

// No direct access is allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wc4bp_base {
	private $debug;
	public $is_paying;
	public $is_free;
	public $is_start;
	public $is_professional;
	public $is_premium_only;
	
	static $starter_plan_id = 'starter';
	static $professional_plan_id = 'professional';
	static $business_plan_id = 'business';
	private $plan;
	
	public function __construct( $debug = false ) {
		//Comment the next line to disable the forced debug
//		$debug       = true;
		$this->debug = $debug;
		if ( ! $debug ) {
			$this->is_paying       = WC4BP_Loader::getFreemius()->is_paying();
			$this->is_free         = WC4BP_Loader::getFreemius()->is_free_plan();
			$this->is_start        = WC4BP_Loader::getFreemius()->is_plan( self::$starter_plan_id );
			$this->is_professional = WC4BP_Loader::getFreemius()->is_plan( self::$professional_plan_id );
			$this->is_premium_only = WC4BP_Loader::getFreemius()->is__premium_only();
			
			return;
		} else if ( ! is_array( $debug ) ) {
			$debug = array( 'is_paying' => false, 'is_free_plan' => true, 'starter' => false, 'professional' => false, 'is_premium_only' => false ); //Free
//			$debug = array( 'is_paying' => true, 'is_free_plan' => false, 'starter' => true, 'professional' => false, 'is_premium_only' => true ); //Starter
//			$debug = array( 'is_paying' => true, 'is_free_plan' => false, 'starter' => false, 'professional' => true, 'is_premium_only' => true );//Professional
		}
		$this->is_paying       = $debug['is_paying'];
		$this->is_free         = $debug['is_free_plan'];
		$this->is_start        = $debug['starter'];
		$this->is_professional = $debug['professional'];
		$this->is_premium_only = $debug['is_premium_only'];
		
		//Set the fake plan
		$this->plan = self::$starter_plan_id;
	}
	
	public function disable_class_tag( $tag, $plan = 'professional', $force = false ) {
		if ( $force || ( ! $this->is_paying || $this->is_free || ! $this->is_plan( $plan ) ) ) {
			switch ( $tag ) {
				default:
					$class = 'wc4bp-disabled';
			}
			
			return 'class="' . $class . '"';
		}
		
		return '';
	}
	
	public function disable_input_tag( $type, $plan = 'professional', $force = false ) {
		if ( $force || ( ! $this->is_paying || $this->is_free || ! $this->is_plan( $plan ) ) ) {
			switch ( $type ) {
				case 'button':
					$attr = 'disabled';
					break;
				default:
					$attr = 'disabled="disabled"';
			}
			
			return $attr;
		}
		
		return '';
	}
	
	public function is_plan( $plan_id ) {
		if ( $this->debug ) {
			$result = ( $this->plan == $plan_id );
		} else {
			$result = WC4BP_Loader::getFreemius()->is_plan( $plan_id );
		}
		
		return $result;
	}
	
	public function needs_upgrade() {
		return ( $this->is_free || $this->is_start ) && ! $this->is_professional;
	}
	
	
}