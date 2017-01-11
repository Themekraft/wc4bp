<?php
/**
 * @package		WordPress
 * @subpackage	BuddyPress, Woocommerce
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @link		https://github.com/Themekraft/BP-Shop-Integration
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
?>

<div id="item-body" role="main">
	<?php do_action( 'woocommerce_before_my_account' ); ?>

	<?php do_action( 'wc4bp_before_history_body' ); ?>

	<?php do_shortcode('[wc4bp_my_recent_orders]') ?>

    <?php do_action( 'woocommerce_after_my_account' ); ?>

	<?php do_action( 'wc4bp_after_history_body' ); ?>

</div><!-- #item-body -->
