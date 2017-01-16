<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
?>

<p>
	<?php _e('For example \'content\', \'page\' would check for a template content-page.php and if content-page.php not exists it
	would look for content.php.', 'wc4bp'); ?>
</p>
<input name='wc4bp_options[page_template]' type='text' value="<?php echo $page_template ?>"/>