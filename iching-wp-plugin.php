<?php

/*
 * Plugin Name: IChing WP Plugin
 * Plugin URI: https://github.com/iching/iching-wp-plugin
 * Description: This is a plugin for IChing
 * Version: 1.0.0
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author: Oleksandr Burkhan.
 * Author URI: https://github.com/iching
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: The gettext text domain of the plugin. More information can be found in the Text Domain section of the How to Internationalize your Plugin page.
 * Domain Path: The domain path lets WordPress know where to find the translations.
 * Update URI: https://github.com/iching/iching-wp-plugin
 * Requires Plugins: No
 */

if (!defined('ABSPATH')) {
    exit;
}

add_shortcode(
    'iching_divination',
    'iching_output'
);

//  Call Divination
use App\Divination;

require_once __DIR__ . '/classes/core/vendor/autoload.php';

function iching_output() {

$text = (new Divination)->index();

    return $text;
}