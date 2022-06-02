<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<?php settings_errors(); ?>
<div id="icon-options-general" class="icon32"><br></div>
<h2> <?php esc_html_e( 'WC4BP', 'wc4bp' ); ?></h2>
<div style="overflow: auto;">
	<span style="font-size: 13px; float:right;"><?php esc_html_e( 'Proudly brought to you by ', 'wc4bp' ); ?><a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>
</div>

<h2 class="nav-tab-wrapper">
	<a href="?page=<?php echo esc_attr( wc4bp_admin::getSlug() ); ?>&tab=generic" class="nav-tab <?php echo $active_tab == 'generic' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'General Settings', 'wc4bp' ); ?></a>
	<a href="?page=<?php echo esc_attr( wc4bp_admin::getSlug() ); ?>&tab=page-sync" class="nav-tab <?php echo $active_tab == 'page-sync' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Profile Fields Sync', 'wc4bp' ); ?></a>
	<a href="?page=<?php echo esc_attr( wc4bp_admin::getSlug() ); ?>&tab=integrate-pages" class="nav-tab <?php echo $active_tab == 'integrate-pages' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Integrate Pages', 'wc4bp' ); ?></a>
	<a href="?page=<?php echo esc_attr( wc4bp_admin::getSlug() ); ?>&tab=notifications" class="nav-tab <?php echo $active_tab == 'notifications' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Notifications', 'wc4bp' ); ?></a>
	<a href="?page=<?php echo esc_attr( wc4bp_admin::getSlug() ); ?>&tab=delete" class="nav-tab <?php echo $active_tab == 'delete' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Delete', 'wc4bp' ); ?></a>

	<?php /** @var wc4bp_admin $this */ if ( $this->needs_upgrade() ) : ?>
		<a href="?page=wc4bp-options-page-pricing" class="nav-tab"><font color="#b22222"><?php esc_html_e( 'Go Professional!!!', 'wc4bp' ); ?></font></a>
	<?php endif; ?>
</h2>
