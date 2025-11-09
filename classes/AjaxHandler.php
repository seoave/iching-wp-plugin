<?php
// TODO experimental handler
namespace WP_Iching;

class AjaxHandler
{
    const ACTION = 'iching';
    const NONCE = 'iching-ajax';

    public static function register(): void
    {
        $handler = new self();

        add_action('wp_ajax_' . self::ACTION, array($handler, 'handle'));
        add_action('wp_ajax_nopriv_' . self::ACTION, array($handler, 'handle'));
        add_action('wp_loaded', [$handler, 'register_script']);
    }

    public function register_script(): void
    {
        wp_register_script('iching-ajax', ICHING_ASSETS_URL . '/js/main.js');
        wp_localize_script('iching-ajax', 'iching_ajax_data', $this->get_ajax_data());
        wp_enqueue_script('iching-ajax');
    }

    private function get_ajax_data(): array
    {
        return [
            'action' => self::ACTION,
            'nonce'  => wp_create_nonce(AjaxHandler::NONCE),
            'ajax_url' => admin_url('admin-ajax.php'),
        ];
    }

    public function handle()
    {
        check_ajax_referer(self::NONCE);

        echo '!!!';

        die();
    }
}
