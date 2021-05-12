=== WooBuddy -> WooCommerce BuddyPress Integration ===
Contributors: themekraft, svenl77, gfirem, garrett-eclipse, shabushabu, kishores, vmarin
Tags: WooCommerce, BuddyPress, Shop, eCommerce, social networking, social shopping, customer, customer relation, achievements, support, product, vendor, marketplace, groups, support groups, profile, my account, my-account
Requires at least: 4.9
Tested up to: 5.7
Stable tag: 3.3.15
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

### Addons

> * [WooBuddy -> Checkout Manager: Add your BuddyPress Profile Fields into the WooCommerce Checkout. Customize your WooCommerce Checkout field and remove unwanted fields for example "phone number" from the checkout form.](https://wordpress.org/plugins/woocommerce-buddypress-integration-xprofile-checkout-manager/)
> * [WooBuddy -> Groups, integrate BuddyPress Groups with WooCommerce and WooCommerce Subscription. Ideal for subscription and membership sites such as premium support.](https://wordpress.org/plugins/wc4bp-groups/)
> * [WooBuddy -> Subscriptions, integrate BuddyPress with WooCommerce Subscription. Ideal for subscription and membership sites such as premium support.](https://themekraft.com/products/buddypress-woocommerce-subscriptions-integration/)

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

<h4>Videos</h4>

Overview over the plugin options [youtube https://www.youtube.com/watch?v=DtPk-WKo8ww&feature=youtu.be]

---

### Blog Posts
[Why You Should Build Your Online Shop With WordPress, BuddyPress and WooCommerce](https://themekraft.com/why-you-should-build-your-online-shop-with-wordpress-buddypress-and-woocommerce/)


> #### AddOns
> * WooBuddy -> Checkout Manager: Add your BuddyPress Profile Fields into the WooCommerce Checkout. Customize your WooCommerce Checkout field and remove unwanted fields for example "phone number" from the checkout form.


---

> #### Docs & Support
> * Find our Getting Started, How-to and Developer Docs on [docs.themekraft.com](http://docs.themekraft.com/)

---

> #### Submit Issues - Contribute
> * Pull request are welcome. WooBuddy is community driven and developed on [Github](https://github.com/Themekraft/wc4bp)

---

> #### Demo Site
> * Feel free to test WooBuddy on our Demo Site: [WooBuddy Demo](https://addendio.com/try-plugin/?slug=wc4bp)

---

> #### Follow Us
> [Blog](https://themekraft.com/blog/) | [Twitter](https://twitter.com/themekraft) | [Facebook](https://www.facebook.com/themekraft/)

---

> **Powered with ❤ by [ThemeKraft](https://themekraft.com)**

---

#### Tags
Tags: WooCommerce, BuddyPress, Shop, eCommerce, social networking, social shopping, customer, customer relation, achievements, support, product, vendor, marketplace, groups, support groups, profile, my account, my-account


== Installation ==
Upload the entire WooBuddy folder to the /wp-content/plugins/ directory or install the plugin through the WordPress plugins screen directly.
Activate the plugin through the 'Plugins' menu in WordPress.
Head to the 'WooBuddy Settings' menu item in your admin sidebar to adjust the settings

== Screenshots ==

1. Admin Settings General
2. Profile Field Synchronization Settings
3. Add Pages to Member Profiles
4. Profile Cart
5. Checkout
6. Orders
7. Profile Settings
8. Edit Billing Address Details
9. Admin menu options

== Changelog ==
= 3.3.15 - 12 May 2021 =
* Fixed issue on redirections from My Account pages to our BP Profile equivalents.
* Added missing asset on the fremius SDK.
* Removed unused Composer dependencies.
* Removed option "Turn off WooCommerce User Profile override", this option isn't necesary and usable anymore.

= 3.3.14 - 9 Mar 2021 =
* Tested up with WordPress 5.7
* Tested up with WC 5.1.0

= 3.3.13 28 - Jan 2021 =
* Fixed race condition on the method is_buddyboss_theme_active. This issue was affecting BuddyBoss users.

= 3.3.12 - 18 Jan 2021 =
* Update the plugin to fix a release script error causing output php code into the administration.

= 3.3.11 - 7 Jan 2021 =
* Added a hook filter to let 3rd party force the field visibility.
* Changing the title of the plugin on the readme.txt for copyright reasons.
* Improve the requirement validation to avoid crash with the new version of BuddyPress.
* Fixed the integrated page edit functionality to avoid create a new page instead of create a new one.
* Updated Freemius SDK.


= 3.3.10 - 20 Dec 2019 =
* Updated Freemius.
* Fix to force the visibility level on user sing up.

= 3.3.9 11 Oct 2019 =
* Improved the plugin compatibility to work with BuddyBoss theme.

= 3.3.8 7 Jul 2019 =
* Fixed the message posted in the Activity stream when a logged out user bought or comment product. In this case the message will start by `A guest ...`.
* Removed the redefine function `is_order_received_page` now this check is trough a filter from WooCommerce.
* Updated Freemius sdk to version 2.3.0.
* Added the new option to join to the beta channel of the plugin.

= 3.3.7 4 Jun 2019 =
* Removed the noise message to Activate your account.

= 3.3.6 4 Jun 2019 =
* Fix the 404 my account pages for the free version.

= 3.3.5 19 May 2019 =
* Fixing the extra weigh of the plugin zip.

= 3.3.4 11 May 2019 =
* fixed the assets issue caused by the automation script where is deploy the new version.

= 3.3.3 11 May 2019 =
* Fixed the issue related to stripe and the action to add payment from the buddypress profile.

= 3.3.2 11 May 2019 =
* Fix the 404 redirection for the user profile pages.
* Limiting teh review message only to administrator.

= 3.3.1 14 March 2019 =
* Fixed the annoying review message.
* Fixed the redirection for the thankyou page option. The expected behavior for default is to continue the normal WooCommerce process.

= 3.3.0 14 March 2019 =
* Security Fix: Freemius
* Fixed the default thankyou page and default tab dropdown component to show correctly in the free version.
* Fixed the function to hide the admin messages.
* Fixed the ajax call to hide the ask for review admin notice.
* Changed to SEM Version.

= 3.2.6.1 11 March 2019 =
* Improved the loading spinner into the admin.
* Included the argument parameter for the hook `woocommerce_checkout_update_customer` to avoid the error `ArgumentCountError`.
* Added validation to ensure the buddypress xprofile is activated and ready to use in our code.

= 3.2.6 7 March 2019 =
* Freemius updated to 2.2.4.
* Add a new option to disable redirection for the thankyou page.
* Fixed: Clean the thank you page when the selected page is deleted from the integrated pages.
* Fixed: Clean the default tab when the selected page is deleted from the integrated pages.
* Use ajax for add and delete integrated pages, to avoid refresh the entire page.
* Improved the activation of the pro version when the free is installed.
* Added an admin message to remember activate the account when is pro installed but not active.


= 3.2.5 7 December 2018 =
* Freemius updated to 2.2.2.
* Fixed the Stripe Payment integrations.
* Fixed the mixing of urls with https.

= 3.2.4 30 August 2018 =
* Fixed an incompatibility using an internal library with other internal plugins.

= 3.2.3 24 August 2018 =
* Fixed the edit option for the integrated pages.

= 3.2.2 8 August 2018 =
* Fixed the page scripts to add/remove/edit integrated pages.

= 3.2.1 2 August 2018 =
* Adding a global option to disable the activity stream.
* Fixed the integrated page order.
* Avoid including Woo pages and BuddyPress pages in the Integrated page functionality.
* Adding new functionality to sort the tabs.
* Merging all tabs disable option in one section.

= 3.2.0 5 Jul 2018 =
* Fixing the get active endpoint function to work in the free version.
* Remove the action `wc4bp_screen_notification_settings`
* Fixing the loader for minified assets.
* Fixing the base url slug for member.
* Fixing the ability to show the tabs if the Activity Setting in BuddyPress is disabled.
* Updating translation file.
* Updated contributors.
* Grammar Fix thanks to @garrett-eclipse.
* Fixing the deprecated use of create_function, now use an anonymous function.
* Fixing the setting space to be better integrated with BuddyPress. Now if the BuddyPress Activity Setting is not enabled it will not work.
* Fixing the edit address inside BuddyPress profile to work with any language.
* Fixing the activity stream when an Order is complete.
* Fixing the Profile settings url to save the options related to the activity stream.

= 3.1.7 24 Jun 2018 =
* Adding new localization files.
* Adding a helper for the AddOns, insert items in the tabs with certain order.

= 3.1.6 24 Jun 2018 =
* Fixing the option to disable the stream related to the shop purchases.
* Adding a filter to disabled the stream activity as global.

= 3.1.5 24 Jun 2018 =
* Testing with the new version of WooCommerce.
* Adding a new option to avoid WooCommerce override User Profile data.
* Fixing the 404 in pay invoice link.
* Fixing the Payment tab.
* Adding new filter to avoid the redirecting of WooBuddy.
* Improving the code documentation.
* Removing the restore error handler from debug class.

= 3.1.4 14 May 2018 =
* Fixing the filters to change the Shop label

= 3.1.3 6 May 2018 =
* Fix error notice.
* Improving the Spelling/Grammar. Thanks to @garrett-eclipse
* New option to change Shop label.
* New option to change Shop slug.

= 3.1.2 20 April 2018 =
* Adding custom message to check dependency.
* Changing the requirement library to use a custom internal id.
* Adding WooCommerce minimum version compatibility header.
* Removing EDD migration script.
* Enabling trials in the code.

= 3.1.1 9 March 2018 =
* Fixing integration with amazon pay.
* Fixed the 404 error when click the Change payment button inside subscription detail page.
* Fixing the redirection for My Account WooCommerce when is Turn off.
* Updating freemius.

= 3.1.0 9 March 2018 =
* Adding a filter to change the shop slug.
* Refactoring and Optimizing the code.
* Improving compatibility with PayBox IPN.
* Improving compatibility with Amazon Pay.
* Fixing the Sync of existing profield groups. Now if possible to edit the description by hand.
* Fixing the Turn off of Sync Profields.

= 3.0.21 18 January 2018 =
* Updating localization files to include new strings.

= 3.0.20 18 January 2018 =
* Fixing the url redirection for the WooCommerce My Account when the shop option is disable.
* Adding a described text to the shop option in the settings.
* Fixing the order detail 404 when the redirection is of.
* Fixing the User fields to keep in the WooCommerce Session.
* Adding France localization. Thanks to @denisleclerc.

= 3.0.16 21 December 2017 =
* Fixing a 404 error in orders details for the free version.
* Solved HTTP request of WC checkout page leads to INET_E_REDIRECT_FAILED with WooBuddy plugin activated.

= 3.0.15.3 19 December 2017 =
* Fixing 404 in Activity Stream

= 3.0.15.2 18 December 2017 =
* Fixing the revision for each user.
* Adding the revision option to the status section.
* Changing the requirement text for one generic.

= 3.0.15 14 December 2017 =
* fix Error 404 in order-received.
* Solved Fix then pagination for the order page.
* Changing the option to show inside the groups generated in BuddyPress by the plugins.
* Add admin notices in settings.
* Fixing the 404 in checkout when the user register the account in the process.
* Adding space in the admin before the link of ThemKraft.
* Adding a notice to ask for revision.
* Adding WC and BP version to the status menu.
* Fixing the Shop disable when all tabs are disabled.

= 3.0.14.1 3 December 2017 =
* Now the plugins not delete the fields when uninstall, only when the user stop the sync.
* The plugins reuse the groups and the fields if they exist, it use the description of the groups as key for it.
* Ensuring the plugins only modify the fields belong to shipping and billing groups.
* Cleaning the activation and deactivation to not create/delete the old pages.
* Changing the core to be responsible to update the bp/user profield fields, not the x-profile.
* Disabling the error handler. To improve when massive error comes.
* Fixing the header of the status section to improve the filter to add custom attributes to the header of each section.
* Adding extra information to the status section related to the BuddyPress xProfile groups and fields. This time get all groups and fields.
* Optimizing the load of the xProfile field ids from our settings to use a cached version.

= 3.0.14 27 November 2017 =
* Create the status menu option.
* Optimize all related to the my-account.
* Optimize the Redirection.
* Fixing the activation and deactivations for the profile field sync
* Removing pages for WooCommerce My Account integrations.
* Optimize My Account.
* Adding cache.
* Improving the UX of the WooBuddy Setting Tab.
* Fixing the function to load the payment method screen.
* Fixing the edit page to edit the Shipping and Billing group.
* Adding a message to the sync tab when the user disable the Turn off the Profile Sync.
* Enabling short-codes for WooCommerce my account in the free version.
* Removing history. Fixing the default shop in the setting and the front.
* Adding a patch to remove the old pages.
* Fixing the css of all WooCommerce view.

= 3.0.13 19 September 2017 =
* Fixing  Profile Field Visibility for all Users, inside the Profile Syn tab
* Fixed add-payment-method page
* Fixing the redirection to not affect the WooCommerce My Account if the shop is disable
* Fixing the Disable Shop Tab option to work in all plans
* Fixing the option to Overwrite the Content of your Shop Home/Main Tab to keep the value when the user save it in the admin
* Remove the template option from the Integrate Pages tab and move it to a hook, now is possible to do the same function using the filter wc4bp_custom_page_template
* Fixing the option to delete a payment inside tab Payment method
* Fixing the payment methods to show in the checkout process
* Fixing the option Turn off 'Shop' Tab inside "Settings" for the activity stream settings
* Fixing the duplicate Save Button inside the Shop Setting view
* Adding a new option to disable all function related to WooCommerce, with this option checked all tabs will be disable
* Fixing the error Illegal string offset 'tab_activity_disabled' for free version
* Fixed an issue related to the hook the_title form wp. In some instance the post_id come empty and trow a warning.
* Fixed free version problem add WC4BP_Loader::getFreemius()->is__premium_only() to avoid premium code insert into free version
* Limit the WooCommerce endpoint to only the basic implemented for core.
* Implemented a cache for the function where the core get the endpoints
* Implemented a cache for the functions referent to the endpoints
* Fixing a waring, reported from h-20409
* Integrating wp unit test

= 3.0.12 12 August 2017 =
* Adjust the default tab logic. If the cart tab is disabled, the next active tab is set as default tab. If no other tab was previously set as default.
* Added missing string translation
* Removing the EDD migration
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
* Checking if xProfile is active in BuddyPress

= 3.0.8 Jun 27.2017 =
* Updated freemius
* Fixed a issue with the submit button in the settings page
* Fixed a issue in the checkout in some situations there was a 404 after payment

= 3.0.7 15.06.2017 =
* Added an extra check to avoid notice in the page loop in the WordPress backend.
* Fixed an issue with WooCommerce 3.0. The BP fields on the checkout disappeared.
* Fixed an issue when try to "Sync Profile Fields" returned value of 0.
* Fixed Redundant code to sync xProfile
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
