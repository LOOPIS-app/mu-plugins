<?php
/**
 * Plugin Name: LOOPIS Constants
 * Description: Definitions of shared path/URI constants for LOOPIS components.
 * Version:     0.02
 * Author:      The Develoopers
 * Author URI:  https://loopis.org
 * License:     GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
    exit;
}

// Canonical wp-content base path
if (defined('WP_CONTENT_DIR')) {
    $wp_content = untrailingslashit(WP_CONTENT_DIR);
} else {
    $wp_content = untrailingslashit(ABSPATH . 'wp-content');
}

// Canonical wp-content subdirectories
$loopis_themes_root = $wp_content . '/themes';
$loopis_plugins_root = $wp_content . '/plugins';


// Client-side path definitions
define('LOOPIS_THEME_DIR', $loopis_themes_root . '/loopis-theme');
define('LOOPIS_THEME_HQ_DIR', $loopis_themes_root . '/loopis-theme-hq');
define('LOOPIS_USERS_DIR', $loopis_plugins_root . '/loopis-users');

// Server-side path definitions
define('LOOPIS_THEME_URI', content_url('themes/loopis-theme'));         
define('LOOPIS_THEME_HQ_URI', content_url('themes/loopis-theme-hq'));
define('LOOPIS_USERS_URI', content_url('plugins/loopis-users'));