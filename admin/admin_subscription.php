<?php

/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 29/06/2017
 * Time: 22:25
 */
class wc4bp_admin_subscription extends wc4bp_base{


    public function wc4bp_screen_subscription($active_tab) {
        //$this->wc4bp_register_admin_settings_delete();
      
        include_once( WC4BP_ABSPATH_ADMIN_VIEWS_PATH . 'html_admin_subscription_screen.php' );
    }

}