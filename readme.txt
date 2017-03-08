=== WooCommerce BuddyPress Integration ===

Contributors: themekraft, svenl77, gfirem, shabushabu, kishores
Tags: WooCommerce, BuddyPress, Shop, eCommerce, social networking, social shopping, customer, customer relation, achievements, support, product, vendor, marketplace, groups, support groups, profile, my account, my-account
Requires at least: WP 4.0
Tested up to: WP 4.7.2
Stable tag: 3.0.1
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

> #### AddOns
> * WC4BP -> Checkout Manager: Add your BuddyPress Profile Fields into the WooCommerce Checkout. Customize your WooCommerce Checkout field and remove unwanted fields for example "phone number" from the checkout form.


---

> #### Docs & Support
> * Find our Getting Started, How-to and Developer Docs on [docs.themekraft.com](http://docs.themekraft.com/)

---

> #### Submit Issues - Contribute
> * Pull request are welcome. BuddyForms is community driven and developed on [Github](https://github.com/Themekraft/wc4bp)

---

> #### Demo Site
> * Feel free to test BuddyForms on our Demo Site: [WC4BP Demo](https://addendio.com/try-plugin/?slug=wc4bp)

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
2. Profile Field Synchronisation Settings
3. Add Pages to Member Profiles
4. Profile Cart
5. Checkout
6. Track my order
7. Profile Settings
8. Profile Address Details

== Changelog ==

= 3.0.1 08.03.2017 =
* Fixing the go pro tab
* Fixing the redirect from the original my-account page

= 3.0 01.03.2017 =
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