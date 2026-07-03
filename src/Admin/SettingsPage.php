<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Admin;

use Noscrape\WordPress\Config\Config;

final readonly class SettingsPage
{
    public function __construct(
        private Config $config,
    )
    {
    }

    public function boot(): void
    {
        add_action('admin_menu', [$this, 'registerPage']);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_notices', [$this, 'adminNotices']);
    }

    public function registerPage(): void
    {
        add_options_page(
            __('Noscrape', 'noscrape'),
            __('Noscrape', 'noscrape'),
            'manage_options',
            'noscrape',
            [$this, 'render'],
        );
    }

    public function registerSettings(): void
    {
        register_setting('noscrape', 'noscrape_api_key');
        register_setting('noscrape', 'noscrape_host');
        register_setting('noscrape', 'noscrape_cache');
        register_setting('noscrape', 'noscrape_shortcodes');
        register_setting('noscrape', 'noscrape_woocommerce');

        add_settings_section(
            'noscrape_general',
            __('General', 'noscrape'),
            '__return_false',
            'noscrape',
        );

        add_settings_field(
            'noscrape_api_key',
            __('API Key', 'noscrape'),
            [$this, 'renderApiKeyField'],
            'noscrape',
            'noscrape_general',
        );

        add_settings_field(
            'noscrape_host',
            __('Host', 'noscrape'),
            [$this, 'renderHostField'],
            'noscrape',
            'noscrape_general',
        );

        add_settings_section(
            'noscrape_integrations',
            __('Integrations', 'noscrape'),
            '__return_false',
            'noscrape',
        );

        add_settings_field(
            'noscrape_shortcodes',
            __('Shortcodes', 'noscrape'),
            [$this, 'renderShortcodesField'],
            'noscrape',
            'noscrape_integrations',
        );

        if (class_exists('WooCommerce')) {
            add_settings_field(
                'noscrape_woocommerce',
                __('WooCommerce', 'noscrape'),
                [$this, 'renderWooCommerceField'],
                'noscrape',
                'noscrape_integrations',
            );
        }
    }

    public function render(): void
    {
        ?>
        <div class="wrap">
            <h1>Noscrape</h1>

            <form method="post" action="options.php">
                <?php
                settings_fields('noscrape');
                do_settings_sections('noscrape');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function renderApiKeyField(): void
    {
        printf(
            '<input class="regular-text" type="password" name="noscrape_api_key" value="%s">',
            esc_attr($this->config->apiKey() ?? ''),
        );

        echo '<p class="description">';
        esc_html_e('Enter your Noscrape API key.', 'noscrape');
        echo '<br><br>';
        echo '<a href="https://noscrape.eu" target="_blank" rel="noopener noreferrer">';
        esc_html_e('Get your API key', 'noscrape');
        echo '</a>';
        echo ' &middot; ';
        echo '<a href="https://noscrape.eu/docs" target="_blank" rel="noopener noreferrer">';
        esc_html_e('Documentation', 'noscrape');
        echo '</a>';
        echo '</p>';
    }


    public function renderHostField(): void
    {
        printf(
            '<input class="regular-text" type="url" name="noscrape_host" value="%s" placeholder="https://api.noscrape.eu">',
            esc_attr($this->config->host() ?? ''),
        );

        echo '<p class="description">';
        esc_html_e('Leave empty to use the official Noscrape API.', 'noscrape');
        echo '</p>';
    }

    public function renderShortcodesField(): void
    {
        printf(
            '<label><input type="checkbox" name="noscrape_shortcodes" value="1" %s> %s</label>',
            checked((bool)get_option('noscrape_shortcodes', true), true, false),
            esc_html__('Enable shortcode integration.', 'noscrape'),
        );
    }

    public function renderWooCommerceField(): void
    {
        printf(
            '<label><input type="checkbox" name="noscrape_woocommerce" value="1" %s> %s</label>',
            checked((bool)get_option('noscrape_woocommerce', true), true, false),
            esc_html__('Automatically obfuscate WooCommerce prices.', 'noscrape'),
        );
    }

    public function adminNotices(): void
    {
        $notice = get_transient('noscrape_admin_notice');

        if (!is_array($notice)) {
            return;
        }

        delete_transient('noscrape_admin_notice');

        $type = in_array($notice['type'] ?? '', ['error', 'warning', 'success', 'info'], true)
            ? $notice['type']
            : 'error';

        ?>
        <div class="notice notice-<?php echo esc_attr($type); ?> is-dismissible">
            <p>
                <strong>Noscrape:</strong>
                <?php echo esc_html($notice['message']); ?>
            </p>
        </div>
        <?php
    }
}
