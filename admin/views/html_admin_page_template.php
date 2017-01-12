<?php
/**
 * Admin View: Template pages
 *
 */

?>

<p>Please keep in mind that you need to use a template part.<br> Without the header and footer added. Just the loop item!</p>
<p>For example 'content', 'page' would check for a template content-page.php and if content-page.php not exists it would look for content.php.</p>
<input name='wc4bp_options[page_template]' type='text' value="<?php echo $page_template ?>"/>