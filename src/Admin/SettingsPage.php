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

        add_action(
            'admin_notices',
            [$this, 'adminNotices'],
        );
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
    }

    public function renderHostField(): void
    {
        printf(
            '<input class="regular-text" type="url" name="noscrape_host" value="%s" placeholder="https://api.noscrape.eu">',
            esc_attr($this->config->host() ?? ''),
        );
    }


    public function adminNotices(): void
    {
        $notice = get_transient('noscrape_admin_notice');

        if (!is_array($notice)) {
            return;
        }

        delete_transient('noscrape_admin_notice');

        ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <strong>Noscrape:</strong>
                <?= esc_html($notice['message']) ?>
            </p>
        </div>
        <?php
    }
}
