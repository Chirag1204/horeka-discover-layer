=== Horeka Discover & Quick ===
Contributors: horeka
Tags: marketplace, resellers, ecommerce, discovery, reservations
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Complete homepage and discovery layer for Horeka marketplace - connecting local resellers and customers.

== Description ==

Horeka Discover & Quick is a production-grade WordPress plugin that enables a configurable homepage and discovery layer for the Horeka app ecosystem. Horeka is a home goods and small appliances marketplace connecting local resellers and customers.

= Core Features =

* **Admin Configuration** - Comprehensive settings page with visual customization options
* **Custom Post Types** - Resellers and Reservations with full meta field support
* **REST API** - Complete JSON API for resellers, products, and reservations
* **Frontend Shortcodes** - Ready-to-use shortcodes for welcome screens and listings
* **Security First** - Nonce verification, rate limiting, input sanitization
* **Performance Optimized** - Transient caching, lazy loading, pagination

= Available Shortcodes =

* `[horeka_welcome]` - Welcome screen with Discover and Quick CTAs
* `[horeka_discover_home]` - Complete discovery homepage
* `[horeka_reseller_list per_page="10" featured="1"]` - Reseller listings

= REST API Endpoints =

* `GET /wp-json/horeka/v1/resellers` - List resellers
* `GET /wp-json/horeka/v1/products` - List products
* `POST /wp-json/horeka/v1/reservations` - Create reservation

== Installation ==

1. Upload the `horeka-discover-quick` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Horeka > Settings to configure your welcome screen
4. Add shortcodes to your pages

== Frequently Asked Questions ==

= Does this require WooCommerce? =

No, but WooCommerce integration is available for product listings.

= How do I add resellers? =

Go to Resellers > Add New in your WordPress admin panel.

= Are reservations stored in WordPress? =

Yes, all reservations are stored as a custom post type with full meta data.

= Is this secure? =

Yes, the plugin follows WordPress security best practices with nonce verification, capability checks, input sanitization, and rate limiting.

== Changelog ==

= 1.0.0 =
* Initial release
* Admin dashboard and settings
* Custom post types for resellers and reservations
* REST API endpoints
* Frontend shortcodes
* Security and performance features

== Upgrade Notice ==

= 1.0.0 =
Initial release of Horeka Discover & Quick plugin.