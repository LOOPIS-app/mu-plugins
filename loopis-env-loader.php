<?php
/**
 * Plugin Name: LOOPIS Env Loader
 * Description: Configuration of environment variables not used in production
 * Version:     0.02
 * Author:      The Develoopers
 * Author URI:  https://loopis.org
 * License:     GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Skip on live environment (env vars are set at server level)
if (defined('LOOPIS_LIVE') && LOOPIS_LIVE) {
    return;
}

// Load .env from WordPress root (same folder as wp-config.php)
$loopis_env_path = ABSPATH . '.env';

if (is_readable($loopis_env_path)) {
    $loopis_env_lines = file($loopis_env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($loopis_env_lines as $loopis_env_line) {
        $loopis_env_line = trim($loopis_env_line);
        if ($loopis_env_line === '' || str_starts_with($loopis_env_line, '#')) {
            continue;
        }
        if (!str_contains($loopis_env_line, '=')) {
            continue;
        }
        [$loopis_env_key, $loopis_env_value] = explode('=', $loopis_env_line, 2);
        $loopis_env_key   = trim($loopis_env_key);
        $loopis_env_value = trim($loopis_env_value);
        if ($loopis_env_key !== '' && getenv($loopis_env_key) === false) {
            putenv("{$loopis_env_key}={$loopis_env_value}");
            $_ENV[$loopis_env_key] = $loopis_env_value;
        }
    }
}

// Define constants from env — add new keys here as the project grows
$loopis_env_constants = [
    'LOOPIS_STRIPE_SECRET_KEY'              => '',
    'LOOPIS_STRIPE_WEBHOOK_SECRET_MEMBERSHIP' => '',
    'LOOPIS_STRIPE_WEBHOOK_SECRET_COINS'    => '',
    'ADMIN_PASS'    => '',
];

foreach ($loopis_env_constants as $loopis_const_name => $loopis_const_fallback) {
    if (!defined($loopis_const_name)) {
        define($loopis_const_name, getenv($loopis_const_name) ?: $loopis_const_fallback);
    }
}
