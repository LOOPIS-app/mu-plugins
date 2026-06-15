<?php
/**
 * Plugin Name: LOOPIS Constants
 * Description: Definitions of shared path/URI constants for LOOPIS components.
 * Version:     0.01
 * Author:      The Develoopers
 * Author URI:  https://loopis.org
 * License:     GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
    exit;
}

// Canonical wp-content base path for shared theme/plugin constants.
if (defined('WP_CONTENT_DIR')) {
    $wp_content = untrailingslashit(WP_CONTENT_DIR);
} else {
    $wp_content = untrailingslashit(ABSPATH . 'wp-content');
}

$loopis_theme_root = $wp_content . '/themes';

// Define client-side path to https://loopis.app/wp-content/themes/loopis-theme/
if (!defined('LOOPIS_THEME_DIR')) {
    define('LOOPIS_THEME_DIR', $loopis_theme_root . '/loopis-theme');
}

// Define server-side path to /wp-content/themes/loopis-theme/
if (!defined('LOOPIS_THEME_URI')) {
    define('LOOPIS_THEME_URI', content_url('themes/loopis-theme'));
}

// Define client-side path to https://loopis.app/wp-content/themes/loopis-theme-hq/
if (!defined('LOOPIS_THEME_HQ_DIR')) {
    define('LOOPIS_THEME_HQ_DIR', $loopis_theme_root . '/loopis-theme-hq');
}

// Define server-side path to /wp-content/themes/loopis-theme-hq/
if (!defined('LOOPIS_THEME_HQ_URI')) {
    define('LOOPIS_THEME_HQ_URI', content_url('themes/loopis-theme-hq'));
}