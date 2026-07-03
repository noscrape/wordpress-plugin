<?php

declare(strict_types=1);

/**
 * Plugin Name:       Noscrape
 * Plugin URI:        https://noscrape.eu
 * Description:       Protect email addresses, phone numbers, prices and other sensitive content from scraping bots using server-side obfuscation.
 * Version:           0.1.0
 * Requires at least: 6.8
 * Requires PHP:      8.2
 * Author:            Bernhard Schönberger
 * Author URI:        https://noscrape.eu
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       noscrape
 */

defined('ABSPATH') || exit;

require __DIR__.'/vendor/autoload.php';

new Noscrape\WordPress\Plugin()->boot();
