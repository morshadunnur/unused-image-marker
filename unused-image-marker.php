<?php
/**
 * Plugin Name: Unused Image Marker
 * Description: A plugin to mark unused images in the media library and remove the "Delete_" prefix from image titles.
 * Version: 1.0.0
 * Author: Md Morshadun Nur
 *  Author URI: https://morshadunnur.me
 *  License: GPLv2 or later
 *  License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: unused-image-marker
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define constants
define('UIM_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('UIM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include main class
require_once UIM_PLUGIN_PATH . 'includes/class-unused-image-marker.php';

// Initialize the plugin
UnusedImageMarker::get_instance();
