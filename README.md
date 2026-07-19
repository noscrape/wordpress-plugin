# Noscrape for WordPress

Official WordPress plugin for **Noscrape**.

Protect email addresses, phone numbers, prices and other sensitive content from scraping bots using server-side obfuscation. Unlike JavaScript-based solutions, the original content is **never sent to the browser**.

## Features

- Server-side obfuscation
- Original content never appears in the page source
- One API request per page
- Automatic font embedding
- Graceful fallback if the API is unavailable
- WooCommerce integration
- Shortcode support
- No JavaScript required
- Lightweight
- Easy to configure

---

## Requirements

- WordPress 6.8+
- PHP 8.2+
- A Noscrape account

Create an account at:

https://noscrape.eu

---

## Installation

### Manual

1. Download the latest release.
2. Upload the plugin to `wp-content/plugins/`.
3. Activate the plugin.
4. Open **Settings → Noscrape**.
5. Enter your API key.
6. Save.

---

## Configuration

The plugin supports the following settings:

| Setting | Description |
|----------|-------------|
| API Key | Your Noscrape API key |
| Host | Optional custom API endpoint (On-Premise installations) |
| Shortcodes | Enable shortcode integration |
| WooCommerce | Automatically obfuscate WooCommerce prices |
| Screen reader price text | Optionally obfuscate WooCommerce price text intended for screen readers; reduces accessibility |

---

## Shortcodes

Protect arbitrary content using the built-in shortcode.

```text
[noscrape]
hello@example.com
[/noscrape]
```

Output is automatically replaced during page rendering using the Noscrape API.

---

## WooCommerce

When enabled, the plugin automatically protects WooCommerce prices.

Supported locations include:

- Product pages
- Shop pages
- Category pages
- Cart
- Checkout

No template modifications are required.

WooCommerce publishes separate price text for screen readers. By default, this remains readable to
preserve accessibility. Enable **Screen reader price text** only when protecting that text is more
important than screen-reader price announcements.

---

## How it works

1. WordPress renders the page.
2. Sensitive content is replaced with temporary placeholders.
3. The plugin sends a single request to the Noscrape API.
4. Placeholders are replaced with obfuscated content.
5. The required font is embedded into the page.
6. The final HTML is sent to the browser.

The original content never reaches the client.

---

## Error handling

If the API is temporarily unavailable:

- The website continues to work normally.
- Original content is displayed instead of obfuscated text.
- Administrators receive a notification in the WordPress dashboard.

This ensures that Noscrape never breaks your website.

---

## Documentation

https://noscrape.eu/docs

---

## Support

Website

https://noscrape.eu

Documentation

https://noscrape.eu/docs

Issues

https://github.com/noscrape/wordpress-plugin/issues

---

## License

GPL-2.0-or-later
