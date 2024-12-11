<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class ImageManagement {

    public function __construct() {
        add_action('restrict_manage_posts', [$this, 'add_image_management_buttons']);
        add_action('load-upload.php', [$this, 'restrict_buttons_to_media_library']);
        add_action('wp_ajax_mark_unused_images', [$this, 'mark_unused_images_ajax_handler']);
        add_action('wp_ajax_remove_delete_prefix', [$this, 'remove_delete_prefix_ajax_handler']);
    }

    public function add_image_management_buttons() {
        if (!current_user_can('manage_options')) {
            return;
        }

        echo '<div class="alignright actions">';
        echo '<button class="button button-primary" id="mark-unused-images-button">Mark Unused Images</button>';
        echo '<button class="button button-secondary" id="remove-delete-prefix-button">Remove Delete_ Prefix</button>';
        echo '</div>';
    }

    public function restrict_buttons_to_media_library() {
        if (get_current_screen()->post_type !== 'attachment') {
            remove_action('restrict_manage_posts', [$this, 'add_image_management_buttons']);
        }
    }

    public function mark_unused_images_ajax_handler() {
        check_ajax_referer('mark_unused_images_action', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have sufficient permissions.']);
        }

        global $wpdb;

        $used_images = [];
        $unused_count = 0;
        $used_count = 0;

        // Get site logo and favicon
        $site_logo_id = get_theme_mod('custom_logo');
        $site_icon_id = get_option('site_icon');
        $ids_to_include = array_filter([$site_logo_id, $site_icon_id]);

        foreach ($ids_to_include as $id) {
            $url = untrailingslashit(esc_url_raw(wp_get_attachment_url($id)));
            if ($url) $used_images[] = $url;
        }

        // WooCommerce images
        if (class_exists('WooCommerce')) {
            $products = wc_get_products(['limit' => -1]);
            foreach ($products as $product) {
                $used_images[] = untrailingslashit(esc_url_raw(wp_get_attachment_url(get_post_thumbnail_id($product->get_id()))));
                $gallery_image_ids = $product->get_gallery_image_ids();
                foreach ($gallery_image_ids as $id) {
                    $used_images[] = untrailingslashit(esc_url_raw(wp_get_attachment_url($id)));
                }
            }
        }

        // Posts and pages including drafts and private posts
        $posts = $wpdb->get_results("SELECT ID, post_content FROM {$wpdb->posts} WHERE post_status IN ('publish', 'draft', 'private')", ARRAY_A);
        foreach ($posts as $post) {
            preg_match_all('/https?:\/\/[^"]+\.(jpg|jpeg|png|gif|webp)/i', $post['post_content'], $matches);
            if (!empty($matches[0])) $used_images = array_merge($used_images, $matches[0]);

            $thumbnail_id = get_post_thumbnail_id($post['ID']);
            if ($thumbnail_id) {
                $used_images[] = untrailingslashit(esc_url_raw(wp_get_attachment_url($thumbnail_id)));
            }
        }

        // Get all attachments
        $media_query = new WP_Query([
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
        ]);

        if ($media_query->have_posts()) {
            while ($media_query->have_posts()) {
                $media_query->the_post();
                $media_id = get_the_ID();
                $media_url = untrailingslashit(esc_url_raw(wp_get_attachment_url($media_id)));
                $current_title = get_the_title($media_id);

                if (!in_array($media_url, $used_images)) {
                    if (strpos($current_title, 'Delete_') !== 0) {
                        wp_update_post(['ID' => $media_id, 'post_title' => "Delete_" . $current_title]);
                        $unused_count++;
                    }
                } else {
                    $used_count++;
                }
            }
        }

        wp_reset_postdata();

        wp_send_json_success([
            'message' => 'Image analysis complete.',
            'used_count' => $used_count,
            'unused_count' => $unused_count,
        ]);
    }

    public function remove_delete_prefix_ajax_handler() {

        check_ajax_referer('remove_delete_prefix_action', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have sufficient permissions.']);
        }

        $updated_count = 0;

        $media_query = new WP_Query([
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
        ]);

        if ($media_query->have_posts()) {
            while ($media_query->have_posts()) {
                $media_query->the_post();
                $media_id = get_the_ID();
                $current_title = get_the_title($media_id);

                if (strpos($current_title, 'Delete_') === 0) {
                    $new_title = substr($current_title, 7); // Remove "Delete_" prefix
                    wp_update_post(['ID' => $media_id, 'post_title' => $new_title]);
                    $updated_count++;
                }
            }
        }

        wp_reset_postdata();

        wp_send_json_success([
            'message' => 'Delete_ prefix removal complete.',
            'updated_count' => $updated_count,
        ]);
    }
}

// Initialize image management functionality
new ImageManagement();
