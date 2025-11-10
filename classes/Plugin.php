<?php

namespace WP_Iching;

use Exception;

/**
 * Main plugin class.
 */
class Plugin
{
    public function init(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
        $this->create_shortcode();
        (new Ajax())->setAjaxAction();
    }

    public function create_shortcode(): void
    {
        add_shortcode(
            'iching_divination',
            [$this, 'iching_output']
        );
    }

    /**
     * @throws Exception
     */
    public static function iching_output(): string
    {
        return Template::render();
    }

    public function load_assets(): void
    {
        wp_register_style('iching', ICHING_ASSETS_URL . '/styles/style.css');

        wp_register_script(
            'iching',
            ICHING_ASSETS_URL . '/js/main.js',
            ['jquery'],
            '1.0.0',
            [
                'in_footer' => true,
            ]
        );

        $data = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('title_example'),
        ];

        wp_localize_script(
            'iching',
            'iching_ajax',
            $data
        );

        // TODO include by condition
        wp_enqueue_style('iching');
        wp_enqueue_script('iching');
    }
}
