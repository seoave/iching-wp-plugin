<?php

/*
 * Plugin Name: IChing WP Plugin
 * Plugin URI: https://github.com/iching/iching-wp-plugin
 * Description: This is a plugin for IChing
 * Version: 1.0.1
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author: Oleksandr Burkhan.
 * Author URI: https://github.com/iching
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://github.com/iching/iching-wp-plugin
 * Requires Plugins: No
 */

use WP_Iching\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

// Constants
define('ICHING_ASSETS_URL', plugins_url('/assets', __FILE__));

require_once __DIR__ . '/vendor/autoload.php';

(new Plugin())->init();
