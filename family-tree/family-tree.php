<?php
/*
Plugin Name: Family Tree
Description: Отображает семейное древо на основе данных из WordPress
Version: 1.0
Author: Я
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';

function family_tree_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/tree-template.php';
    return ob_get_clean();
}
add_shortcode('family_tree', 'family_tree_shortcode');

function family_tree_enqueue_scripts($hook) {
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        wp_enqueue_script('family-tree-admin-js', plugin_dir_url(__FILE__) . 'public/assets/js/family-tree.js', ['jquery', 'd3'], null, true);
    }

    wp_enqueue_style('family-tree-css', plugin_dir_url(__FILE__) . 'public/assets/css/family-tree.css');
    wp_enqueue_script('d3', 'https://d3js.org/d3.v4.min.js',  [], null, true);
    wp_enqueue_script('family-chart-js', plugin_dir_url(__FILE__) . 'public/assets/js/family-chart.min.js', ['d3'], null, true);
    wp_enqueue_script('family-tree-js', plugin_dir_url(__FILE__) . 'public/assets/js/family-tree.js', ['family-chart-js'], null, true);
}
add_action('admin_enqueue_scripts', 'family_tree_enqueue_scripts');
add_action('wp_enqueue_scripts', 'family_tree_enqueue_scripts');