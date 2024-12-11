<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class UnusedImageMarker {

    private static $instance = null;

    private function __construct() {
        add_action('init', [$this, 'load_dependencies']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function load_dependencies() {
        require_once UIM_PLUGIN_PATH . 'includes/class-image-management.php';
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_script(
            'uim-admin-scripts',
            UIM_PLUGIN_URL . 'assets/js/admin-scripts.js',
            ['jquery'],
            '1.0.0',
            true
        );

        wp_localize_script('uim-admin-scripts', 'UIM_Ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce_mark_unused' => wp_create_nonce('mark_unused_images_action'),
            'nonce_remove_prefix' => wp_create_nonce('remove_delete_prefix_action'),
        ]);
    }
}
