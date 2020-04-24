<?php

namespace Page;

class AdminPage {
	// include url of current page
	public static $setting_url = '/admin.php?page=wc4bp-options-page';

	public static $dropdown_tab_sync_disabled = "input[name*='tab_sync_disabled'][name^='wc4bp_options']" ;

	/**
	 * @param string $tab
	 *
	 * @return string
	 */
	public static function tab( $tab = 'generic' ) {
		return sprintf( "%s&tab=%s", static::$setting_url, $tab );
	}

}
