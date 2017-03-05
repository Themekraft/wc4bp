=== WooCommerce for Buddypress ===

Contributors: themekraft, svenl77, gfirem, slava, shabushabu, kishores
Tags: buddypress, social networking, woocommerce, e-commerce
Requires at least: WP 4.0
Tested up to: WP 4.7.2
Stable tag: 3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce for BuddyPress - Shop solution for your BuddyPress community. Integrates a WooCommerce installation with a BuddyPress social network.

== Description ==

Seamless combine ‘WooCommerce for Buddypress’ with your BuddyPress community to your store which delivers an amazing customer experience by letting your customers engage more with their users on your site.

---

Sync customer data: Show purchase history, product downloads and more in your BuddyPress member profiles. Make all customer relevant data accessible from one place. Add pages from other WooCommerce extensions right into your BuddyPress member profiles.

---

> #### Push your online shop and products to a new level.
> * Empower your customers to become more involved and interact with other users, your products and brand, or even contribute in product development.
> * Perfect for marketplaces, membership sites, digital products, auction sites or any kind of subscription site!

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

> #### AddOns
Install Plugin WC4BP - a checkout manager
What the plugin does: Add your BuddyPress Member Profile Fields into the WooCommerce Checkout. Customize your WooCommerce Checkout field and remove unwanted fields for example "phone number" from the checkout form.

---

== Installation ==
1. Download the plugin
2. Upload to wp-content/plugins/
3. Activate in the backend
4. Done... No options available!

== Frequently Asked Questions ==

= Why no options? =

Because we don't need any. Woocommerce takes care of that. But maybe later...

== Languages ==
* English
* German

== Screenshots ==

1. Admin

== Changelog ==

= 3.0 = - 01.03.2017
* Version 3.0 is a complete rewrite of the old 2.x version and the first public version.
* WC endpoint modifications
* Refactoring and cleaning the code
* Added sanitise check to the page settings - Sanitizing all $_POST
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

= v2.5 - 11.08.2016=
* Switch the update script to the new system
* Clean up the code

= v2.4 - 26.04.2016=
* Fixed a issue where the js does not get loaded in the checkout. Lucky WC add some filter so we can rewrite this functionality to become more update save in the future
* woocommerce_is_checkout and woocommerce_is_account_page should work just fine now if BuddyPress is enabled
* Fixed an issue with the order received page url
* add a missing global $bp to the wc4bp_get_checkout_order_received_url and wc4bp_get_checkout_payment_url
* clean up the code

= v2.3.5 - 03.30.2016 =
* Change BP_MEMBERS_SLUG to $bp->pages->members->slug to avoid strange issue if people overwrite the member slug
* fixed css props to tristanpenman for the pul request ;)

= v2.3.4 =
* fixed some smaller bugs and clean up the code

= v2.3.3 =
* Fixed a issue from the last update. If the checkout was disabled the redirect was broken.

= v2.3.2 =
* Fixed a redirect issue if cart is empty and a checkout link is clicked.

= v2.3.1 =
* Check if object to prevent crashing if crone job is running.

= v2.3 =
* add the /history/ slug to wc4bp-redirect. Was deleted by mistake in the latest version and curses a issue if the history order details is displayed. Thanks to Bharat for pointing me on this.
* New checkout tab added to BP profile to resolve conflict between cart and checkout section
* added settings for checkout tab
* adde the checkout top navigationb
* fixed the sync option when wc4bp checkout manager install
* added admin message foer all depandancies
* small code refactor
* added filter for tabs
* added filter for profile nav links
* added fix for checkout order recieve issues
* fixed xprofile delete issues
* fixed xprofile update and insert issues

= v2.2.3 =
* add the /history/ slug to wc4bp-redirect.
Was deleted by mistake in the latest version and curses a issue if the history order details is displayed.
Thanks to Bharat for pointing me on this.

= v2.2.2 =
* changed the member link from user_login to user_nicename
* changed bp_loggedin_user_domain to get_bloginfo('url') . '/'.BP_MEMBERS_SLUG.'/'. $current_user->user_login

= v2.2.1 =
* moved the wc4bp_intercept_wp_profile_sync to the xprofile extension.
* comment out woocommerce_get_checkout_payment_url filter. Not needed anymore.

= v2.2 =
* add 'meta'	=> array( 'class' => 'menupop')
* rewrite of the synchronisation
* add support for wc_get_template nd removed woocommerce_get_template
* add ssl to all account pages if ssl is available or selected in the WooCommerce settings
* fixed smaller bugs in the redirects
* add support for xprofile sync during signup and edit xprofile in the wp admin
* cleanup the code

= v2.1.1 =
* fixed a strange redirect issue. On some installs after the checkout the function bp_loggedin_user_domain does not work...

= v2.1 =
* Add new hooks to create a plugin to include BuddyPress xprofile fields into the WooCommerce checkout and hide WooCommerce customer fields from the checkout.
* rework the user sync option to work with very large user bases.
* clean up code and ui ;)
* fixed some smaller bugs reported by users

= v2.0 =
* add new filters for wc4bp_checkout_page_link and wc4bp_account_page_link
* fixed issues with the checkout js
* add delete option to the uninstaller.
* fixed wc 2.1 bug with the profile sync
* add https support to the checkout
* Fixed profile field sync for the address fields
* add new options and option pages
* add new sync options
* redesign the admin settings page
* fixed history view order issue
* add order-pay and add-payment-method url handling
* add endpoint support for order pay and order received url

= v1.3.8 =
* fixed issues with the profile sync

= v1.3.7 =
* fixed issues with the checkout js

= v1.3.6 =
* add new filters for wc4bp_checkout_page_link and wc4bp_account_page_link

= v1.3.5 =
* rewrite for woocommerce 2.1

= v1.3.4 =
* fixed issues with the the api manager

= v1.3.3 =
* fixed a bug reported by a user where the checkout js does not work if the cart integration is disabled.

= v1.3.2 =
* fixed a small bug in the cart url after "add to cart" the "View Cart" link was broken.

= v1.3.1 =
* This is a ute update to the 1.3.1 stable version!!!!
* Renamed functions and prefixed everything with wc4bp
* Add update notification and license keys
* Fixed some more bugs in the new 1.3 features.
* should be stable enough now for life usage ;-)
* add 2 new shortcodes for my_downloads and my_recent_orders

= v1.3 beta 2 =
* fixed several small bugs reported by users

= v1.3 beta 1 =
* Add a new admin interface to turn off Tabs
* Add a new feature to add other WooCommerce pages to the shop in member profiles.
* fixed several small bugs reported by users

= v1.2.1 =
* fixed several small bugs reported by users
* fixed a bug with the en_US translation file
* add a hook woocommerce_before_my_account to the history.

= v1.2 =
* fixed several bugs reported by users
* fixed a problem with the us translation
* Changed textdomain from woocommerce, buddypress to  wc4bp
* Changed from "Network: true" to "Network: false" to avoid that the plugin is installed sitewide on a multi site

= v1.1 =
* Added: buddypress 1.7.x compatibility
* Bugfix: reported by users see github thanks @Marco for the greate testing

= v1.0.8 =
* Added: Only spit out Woocommerce errors on the main site, Thx Ole K Hornnes
* Added: Making sure that the settings page only shows when needed, Thx Ole K Hornnes
* Bugfix: Track your order page wouldn't let you track anything

= v1.0.7 =
* Changed: Bumped testet-up-to versions to latest stable versions
* Bugfix: Fixed dumb redirect issue that the last round of refactoring for 1.0.6 introduced

= v1.0.6 =
* Bugfix: Checkout page for anonymous users

= v1.0.5 =
* NEW: Added shop settings link into wp admin bar settings menu
* NEW: Added nav links to wp admin bar
* Bugfix: Shipping and payment methods were not initially visible on the checkout page
* Bugfix: Profile data did not get synchronized both ways
* Changed: Bumped minimum versions on and upwards (WP => 3.4.1, BP => 1.6.1, WC => 1.6.3)
* Changed: Replaced old WC class names with new ones
* Removed: Minimum PHP version check, as WP now requires PHP 5.2 itself

= v1.0.4 =
* Unknown

= v1.0.3 =
* Rename all jigoshop woocommerce

= v1.0.2 =
* Bugfix: class woocommerce_orders is not used anymore

= v1.0.1 =
* Bugfix: woocommerce_countries::$countries is not a static property anymore

= v1.0 =
* initial release
