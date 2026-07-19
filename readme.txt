=== Noscrape ===
Contributors: bpr0
Tags: privacy, security, bot protection, scraping, woocommerce
Requires at least: 6.8
Tested up to: 7.0
Requires PHP: 8.2
Stable tag: 0.1.3
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
* No JavaScript required on the public site

The free tier works without an API key and allows up to 10 requests per minute.
An API key is optional.

https://noscrape.eu

== Installation ==

1. Upload the plugin to `/wp-content/plugins/` or install it through the WordPress plugin installer.
2. Activate the plugin.
3. Open **Settings → Noscrape**.
4. Optionally enter your Noscrape API key.
5. Save the settings.

== External service and privacy ==

Noscrape uses the Noscrape API to create the obfuscated text and font used on your site.
When content is marked for obfuscation, only that content is sent to
`https://api.noscrape.eu/obfuscate`, unless you configure a custom API host. Depending on
the enabled features, this can include shortcode content, email addresses, phone numbers,
and WooCommerce price text. If configured, the API key is sent with the request for
authentication.

No content is sent when there is nothing to obfuscate. The free tier does not require an
API key and is limited to 10 requests per minute.

Service documentation: https://noscrape.eu/docs
Terms of service: https://noscrape.eu/en/terms
Privacy policy: https://noscrape.eu/en/privacy
Legal notice: https://noscrape.eu/en/imprint

== Frequently Asked Questions ==

= Do I need a Noscrape account? =

No. The free tier works without an API key and is limited to 10 requests per minute. You
can optionally use an API key from https://noscrape.eu.

= Does the original content appear in the page source? =

No. The original content is replaced on the server before the page is sent to the browser.

= Does it require JavaScript? =

No.

= Does it work with WooCommerce? =

Yes. Product prices, cart prices and other WooCommerce price output can be protected automatically.

= What happens if the API is unavailable? =

The plugin automatically falls back to the original content so your website always remains functional.

== Screenshots ==

1. WooCommerce sale-price HTML with visible and screen-reader price text obfuscated.
2. Plain text compared with its obfuscated HTML output.

== Changelog ==

= 0.1.0 =

* Initial release
* Server-side obfuscation
* WooCommerce integration
* Shortcode support
* Automatic font embedding
* Graceful fallback
