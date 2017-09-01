=== WooCommerce BuddyPress Integration ===

Contributors: themekraft, svenl77, gfirem, garrett-eclipse, shabushabu, kishores,
Tags: WooCommerce, BuddyPress, Shop, eCommerce, social networking, social shopping, customer, customer relation, achievements, support, product, vendor, marketplace, groups, support groups, profile, my account, my-account
Requires at least: WP 4.0
Tested up to: WP 4.8.1
Stable tag: 3.0.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Shop solution for your BuddyPress community. Integrates a WooCommerce installation with a BuddyPress social network.

== Description ==

Create a seamless customer experience and get more engagement on your site by integrating your WooCommerce store with your BuddyPress community.

---

Sync customer data: Show purchase history, product downloads and more in your BuddyPress member profiles. Make all customer relevant data accessible from one place. Add pages from other WooCommerce extensions right into your BuddyPress member profiles.
---

That allows you a quick great overview about the engagement level of your customers & users and let’s you manage your site more efficiently.

---

> #### Push your online shop and products to a new level
> * Empower your customers to become more involved and interact with other users, your products and brand, or even contribute in product development.
> * Perfect for marketplaces, membership sites, digital products, auction sites or any kind of subscription site!
> * Secure your data


---

> #### All Plugin Features in Detail
> * Easy to use. No configuration needed. Just activate it. That’s it!
> * Member data: Edit your shipping and billing address directly in your member profile and find all personal information in one place.
> * Checkout: Find your cart in your member profile.
> * Purchase history: Find your payment history and access your downloads from your member profile.
> * View of a single purchase in member profile
> * Track your order in your profile, with your order ID.
> * Activity Stream Integration
> * Always stay informed with the activity stream! All activities like reviews written by customers or purchases made by your customers are posted to the activity stream.
> * Manage notifications: Members can choose if they want their reviews and purchased activities to be shown in the activity stream or not.
> * Synchronization: It synchronizes all WooCommerce data with your BuddyPress data. If you register a new account via the checkout or add data like your billing address etc. the plugin will handle all the communication between WooCommerce and BuddyPress and keep the data synchronized.
> * Fallback save! If you turn off BuddyPress for some reason, or the user don’t want to become a member the plugin falls back to the regular WooCommerce pages.

---

<h4>Videos</h4>

Overview over the plugin options [youtube https://www.youtube.com/watch?v=DtPk-WKo8ww&feature=youtu.be]

---

### Blog Posts
[Why You Should Build Your Online Shop With WordPress, BuddyPress and WooCommerce](https://themekraft.com/why-you-should-build-your-online-shop-with-wordpress-buddypress-and-woocommerce/)



> #### AddOns
> * WC4BP -> Checkout Manager: Add your BuddyPress Profile Fields into the WooCommerce Checkout. Customize your WooCommerce Checkout field and remove unwanted fields for example "phone number" from the checkout form.


---

> #### Docs & Support
> * Find our Getting Started, How-to and Developer Docs on [docs.themekraft.com](http://docs.themekraft.com/)

---

> #### Submit Issues - Contribute
> * Pull request are welcome. WC4BP is community driven and developed on [Github](https://github.com/Themekraft/wc4bp)

---

> #### Demo Site
> * Feel free to test WC4BP on our Demo Site: [WC4BP Demo](https://addendio.com/try-plugin/?slug=wc4bp)

---

> #### Follow Us
> [Blog](https://themekraft.com/blog/) | [Twitter](https://twitter.com/themekraft) | [Facebook](https://www.facebook.com/themekraft/)

---

> **Powered with ❤ by [ThemeKraft](https://themekraft.com)**

---

#### Tags
Tags: WooCommerce, BuddyPress, Shop, eCommerce, social networking, social shopping, customer, customer relation, achievements, support, product, vendor, marketplace, groups, support groups, profile, my account, my-account


== Installation ==
Upload the entire WC4BP folder to the /wp-content/plugins/ directory or install the plugin through the WordPress plugins screen directly.
Activate the plugin through the 'Plugins' menu in WordPress.
Head to the 'WC4BP Settings' menu item in your admin sidebar to adjust the settings

== Screenshots ==

1. Admin Settings General
2. Profile Field Synchronization Settings
3. Add Pages to Member Profiles
4. Profile Cart
5. Checkout
6. Track my order
7. Profile Settings
8. Profile Address Details

== Changelog ==

= 3.0.13 31 August 2017 =
* Fixed add-payment-method page
* Fixed an issue related to the hook the_title form wp. In some instance the post_id come empty and trow a warning.
* Fixed free version problem add WC4BP_Loader::getFreemius()->is__premium_only() to avoid premium code insert into free version
* Limit the woocommerce endpoint to only the basic implemented for core.
* Implemented a cache for the function where the core get the endpoints
* Implemented a cache for the functions referent to the endpoints
* Fixing a waring, reported from h-20409
* Integrated with circleCi add new CircleCI settings
* Adding the git ignore file.
* Integrating wp unit test

= 3.0.12 12 August 2017 =
* Adjust the default tab logic. If the cart tab is disabled, the next active tab is set as default tab. If no other tab was previously set as default.
* Added missing string translation
* Removing the edd migration
* when working with the free version, put the option 'Select the tab you want to use as your Shop Home' disabled
* add the shops tab to the 'Select the tab you want to use as your Shop Home.' dropdown
* now when all the tabs are disabled, if there are custom page, it shows as default tab. when all the tabs are disbled and no custom page were added, the shop turn of until a tab or custom page is enabled
* customizing the messages for the requirement library
* save all the settings in a single action
* maintain the BuddyPress pages with their bbPress links

= 3.0.11 1 August 2017 =
* Added the groups creation verification, to avoid creating duplicates when upgrading plugin versions
* Added the code of the group in the field description
* Freemius update to allow free pro add ons

= 3.0.10 27 July 2017 =
* Adding a security measure to avoid show internal pages to logout users
* Updating all field coming from WC classes
* fix call to undefined function in the free version of the plugin
* changed the min wc version to 3.1

= 3.0.9 18 July 2017 =
* Fixed Spelling and Capitalizations props @garrett-eclipse
* Add new filter wc4bp_add_endpoint to add custom endpoints from other WooCommerce Extensions
* Fixing the turn off shop options from the settings. The sub tabs got broken if shop settings got deactivated.
* Fixed a redirect issue
* Multiples fixing
* Updating the readme to hold new work conventions. props @gfirem for improving the workflow with gitflow
* Changing the initialization hook. Checking if the shop if enabled to process the links.
* Checking if xprofile is active in buddypress

= 3.0.8 Jun 27.2017 =
* Updated freemius
* Fixed a issue with the submit button in the settings page
* Fixed a issue in the checkout in some situations there was a 404 after payment

= 3.0.7 15.06.2017 =
* Added an extra check to avoid notice in the page loop in the WordPress backend.
* Fixed an issue with WooCommerce 3.0. The BP fields on the checkout disappeared.
* Fixed an issue when try to "Sync Profile Fields" returned value of 0.
* Fixed Redundant code to sync xprofile
* Fixed order id for compatibility with woo 3.0
* Fixed the check for post_type in the post list when add the mark #73
* Resolving the issue to delete integrated page with encoding characters and fix the component to show the content #74
* Fix sync BuddyPress sync fields #75
* Moving the template page option to the integrate page tab #66
* Refactoring the code. Improve the activation/deactivation hooks. #78

= 3.0.6 25.04.2017 =
* Adding the remove function for the integrate pages

= 3.0.5 21.04.2017 =
* Updated Freemius
* Added new do action wc4bp_core_fs_loaded
* Readme and author url update
* Multiple smaller fixes

= 3.0.4 01.04.2017 =
* Fixing incompatibility for the requirement library
* Fixing the name of the plugin when checking the PHP version and WordPress
* Refactoring to support old PHP version

= 3.0.3 29.03.2017 =
* Cleaning the code, checking the PHP 5.3 compatibility
* Adding PHP and WordPress check minimum version. Cleaning the code and refactoring.
* Refactoring the code. Integrating EDD with freemius.

= 3.0.2 24.03.2017 =
* Fixing the disable my account tabs in the free version.
* Now the my account tabs is showing to starter plan, and only is possible to disabled it in professional.

= 3.0.1 08.03.2017 =
* Fixing the go pro tab
* Fixing the redirect from the original my-account page

= 3.0 01.03.2017 =
* Version 3.0 is a complete rewrite of the old 2.x version and the first public version.
* WC endpoint modifications
* Refactoring and cleaning the code
* Added sanitize check to the page settings - Sanitizing all $_POST
* Add a filter to change the wc4bp_ slug in the url. Fixed the generation of the pages. Adding the filter to the page mark.
* Add settings options to show my account pages
* Adding options to enabled the wc endpoints
* Changing the way of handle the my account page. Adding the pages into the wp with content and remove it from wc4bp settings. Refactoring the code.
* Separation code views, in the settings section
* Validating if the shop tab are enabled into the process to enabled/disabled the woo my account tabs
* Adding validation for requirements.
* Make texts of the setting translatable
* Update views settings, Update, add, views html Separation of views
* Change from on to off, no logical structure
* Update - Added BuddyPress tabs to the administration Profile
* Update - Apply filters to tabs in WooCommerce
* Adding a method to check if woo exist and refactoring
* Renaming file and adding a general header file to the setting page
* Fixed the duplicate cart and duplicate default page content
* Fixed the checkout page not found.
* Fixed the order details view, and the history tab.
* Adding the content to download tab
* remove old helpscout integration.
* Integrate Pages order is broken. Fixed the delete page from the profile.
* Fixed the redirect error of shop tabs.
* Adding inside 404 error when the page not exist. Moving the implementation of the prefix.
* Adding the css class to handle then pro content. Migrating all sub menu pages into one page with tabs.
* Fixed the redirection problem to return to the shop.
* Fixed the redirection when turn off 'Checkout' and 'Cart' tab.
* Fixed the url for the shopping cart in the admin bar is wrong.
* Fixed the sync between wc myaccount, BuddyPress profile and wp profile.
* Removing the Power By Freemius.
* Adding the tab go pro and moving the admin page slug to be accessible for other places
* Fixed the redirect of the return to the shop from the cart
* Fixed the redirect problem when history are disabled. Fixed the redirect default when the cart tabs are disabled
* Integrating the visual part of the freemius.
* Improve the debug
* Changing the structure of the admin view
* Applying freemius to the bp component.
* Fixed the default tab to exclude the my account tab if is free or starter
* Clean up the code
