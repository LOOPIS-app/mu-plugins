<?php
/**
 * Plugin Name: LOOPIS Image Uploads
 * Description: Standardizes uploaded image sizes and compression.
 * Version:     0.02
 * Author:      The Develoopers
 * Author URI:  https://loopis.org
 * License:     GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

// Set compression quality for supported image formats and generated thumbnails.
function loopis_media_image_quality($quality, $mime_type) {
    return in_array($mime_type, array('image/jpeg', 'image/png', 'image/webp'), true) ? 70 : $quality;
}
add_filter('wp_editor_set_quality', 'loopis_media_image_quality', 10, 2);

// Prevent WordPress from generating additional intermediate image sizes.
function loopis_media_upload_sizes($sizes) {
    // Keep only the configured thumbnail; the uploaded full-size file is already capped.
    return isset($sizes['thumbnail']) ? array('thumbnail' => $sizes['thumbnail']) : array();
}
add_filter('intermediate_image_sizes_advanced', 'loopis_media_upload_sizes', 999);

// Resize and re-encode supported images before WordPress creates their attachments.
function loopis_process_media_upload($upload) {
    if (empty($upload['file']) || empty($upload['type'])) {
        return $upload;
    }

    $source_file = $upload['file'];
    $fail = static function($message, $extra_file = '') use ($source_file) {
        foreach (array_unique(array_filter(array($source_file, $extra_file))) as $file) {
            if (is_file($file) && !wp_delete_file($file)) {
                error_log('LOOPIS Image Uploads: could not remove a failed upload file.');
            }
        }

        return array('error' => $message);
    };

    $output_types = array(
        'image/jpeg'          => 'image/jpeg',
        'image/png'           => 'image/png',
        'image/webp'          => 'image/webp',
        'image/heic'          => 'image/jpeg',
        'image/heif'          => 'image/jpeg',
        'image/heic-sequence' => 'image/jpeg',
        'image/heif-sequence' => 'image/jpeg',
    );
    $source_type = $upload['type'];

    // Leave formats outside this policy to WordPress and other upload handlers.
    if (!isset($output_types[$source_type])) {
        return $upload;
    }

    $output_type = $output_types[$source_type];
    $editor = wp_get_image_editor($upload['file']);

    if (is_wp_error($editor)) {
        return $fail('Image processing failed: ' . $editor->get_error_message());
    }

    $result = $editor->maybe_exif_rotate();
    if (is_wp_error($result)) {
        return $fail('Image rotation failed: ' . $result->get_error_message());
    }

    $dimensions = $editor->get_size();
    if ($dimensions['width'] > 1280 || $dimensions['height'] > 1280) {
        $result = $editor->resize(1280, 1280, false);
        if (is_wp_error($result)) {
            return $fail('Image resizing failed: ' . $result->get_error_message());
        }
    }

    $result = $editor->set_quality(70);
    if (is_wp_error($result)) {
        return $fail('Image compression failed: ' . $result->get_error_message());
    }

    $destination = $source_file;

    if ($source_type !== $output_type) {
        // Avoid overwriting a file if a JPEG with this name already exists.
        $filename = pathinfo($source_file, PATHINFO_FILENAME) . '.jpg';
        $destination = trailingslashit(dirname($source_file)) . wp_unique_filename(dirname($source_file), $filename);
    }

    $saved = $editor->save($destination, $output_type);
    if (is_wp_error($saved)) {
        return $fail('Image saving failed: ' . $saved->get_error_message(), $destination);
    }

    if ($source_file !== $saved['path'] && !wp_delete_file($source_file)) {
        // Don't leave both source and converted files when cleanup fails.
        if (!wp_delete_file($saved['path'])) {
            error_log('LOOPIS Image Uploads: could not remove the converted image after cleanup failed.');
        }
        error_log('LOOPIS Image Uploads: could not remove the source image after HEIC/HEIF conversion.');
        return array('error' => 'Could not clean up the source image after conversion.');
    }

    $upload['file'] = $saved['path'];
    $upload['url'] = trailingslashit(dirname($upload['url'])) . wp_basename($saved['path']);
    $upload['type'] = $output_type;

    return $upload;
}
add_filter('wp_handle_upload', 'loopis_process_media_upload');
