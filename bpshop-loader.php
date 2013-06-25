<?php
/**
 * Plugin Name: WooCommerce for Buddypress
 * Plugin URI:  https://github.com/Themekraft/WooCommerce-for-Buddypress
 * Description: Integrates a WooCommerce installation with a BuddyPress social network
 * Author:      BP Shop Dev Team
 * Version:     1.0.9
 * Author URI:  https://github.com/Themekraft/WooCommerce-for-Buddypress
 * Network:	true
 *
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

	
/**
 * Loads BuddyForms files only if BuddyPress is present
 *
 * @package BuddyForms
 * @since 0.1-beta
 */

		
if( ! defined( 'BP_VERSION' )){ 
	add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop needs BuddyPress to be installed. <a href="%s">Download it now</a>!\', "bpshop" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
	return;
}
require (dirname(__FILE__) . '/wc4bp-basic-integration.php');

