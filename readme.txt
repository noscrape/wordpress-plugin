=== Noscrape ===
Contributors: bpr0
Tags: privacy, security, bot protection, scraping, woocommerce
Requires at least: 6.8
Tested up to: 7.0
Requires PHP: 8.2
Stable tag: 0.1.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Protect email addresses, phone numbers, prices and other sensitive content from scraping bots using server-side obfuscation.

== Description ==

Noscrape protects sensitive website content without exposing the original text in the page source.

Unlike JavaScript-based approaches, Noscrape performs the obfuscation on the server before the HTML is delivered to the visitor.

Current features include:

* Server-side obfuscation
* Original content never appears in the page source
* Automatic font embedding
* One API request per page
* Graceful fallback if the API is unavailable
* WooCommerce integration
* Shortcode support
* No JavaScript required

A Noscrape account and API key are required.

https://noscrape.eu

== Installation ==

1. Upload the plugin to `/wp-content/plugins/` or install it through the WordPress plugin installer.
2. Activate the plugin.
3. Open **Settings → Noscrape**.
4. Enter your Noscrape API key.
5. Save the settings.

== Frequently Asked Questions ==

= Do I need a Noscrape account? =

Yes. You need an API key which you can obtain from https://noscrape.eu.

= Does the original content appear in the page source? =

No. The original content is replaced on the server before the page is sent to the browser.

= Does it require JavaScript? =

No.

= Does it work with WooCommerce? =

Yes. Product prices, cart prices and other WooCommerce price output can be protected automatically.

= What happens if the API is unavailable? =

The plugin automatically falls back to the original content so your website always remains functional.

== Screenshots ==

1. Plugin settings
2. WooCommerce price protection
3. Shortcode example

== Changelog ==

= 0.1.0 =

* Initial release
* Server-side obfuscation
* WooCommerce integration
* Shortcode support
* Automatic font embedding
* Graceful fallback
