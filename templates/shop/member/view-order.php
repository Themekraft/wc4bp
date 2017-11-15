<?php
/**
 * @package         WordPress
 * @subpackage      BuddyPress, Woocommerce
 * @author          GFireM
 * @copyright       2017, Themekraft
 * @link            https://github.com/Themekraft/BP-Shop-Integration
 * @license         http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

$order_id = get_query_var( 'view-order' );
woocommerce_account_view_order( $order_id );
