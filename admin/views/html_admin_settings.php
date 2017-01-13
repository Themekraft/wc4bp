<?php
/**
 * Admin View: Template pages
 *
 */

register_setting( 'wc4bp_options', 'wc4bp_options' );
// Settings fields and sections
add_settings_section( 'section_general', '', '', 'wc4bp_options' );
add_settings_section( 'section_general2', '', '', 'wc4bp_options' );

add_settings_field( 'tabs_shop', '<b>dddShop Settings</b>', array( $this, 'wc4bp_shop_tabs' ), 'wc4bp_options', 'section_general' );
add_settings_field( 'tabs_enable', '<b>Shop Tabs</b>', array( $this, 'wc4bp_shop_tabs_enable'), 'wc4bp_options',  'section_general' );
add_settings_field( 'tabs_disabled', '<b>Remove Shop Tabs</b>', array( $this, 'wc4bp_shop_tabs_disable' ), 'wc4bp_options', 'section_general' );

add_settings_field( 'profile sync', '<b>Turn off the profile sync</b>', array( $this, 'wc4bp_turn_off_profile_sync' ), 'wc4bp_options', 'section_general' );

add_settings_field( 'overwrite', '<b>Overwrite the Content of your Shop Home/Main Tab</b>', array( $this, 'wc4bp_overwrite_default_shop_home_tab' ), 'wc4bp_options', 'section_general' );
add_settings_field( 'template', '<b>Change the page template to be used for the attached pages.</b>', array( $this, 'wc4bp_page_template' ), 'wc4bp_options', 'section_general' );