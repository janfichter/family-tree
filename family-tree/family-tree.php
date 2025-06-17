<?php
/*
Plugin Name: Family Tree
Description: Плагин для создания семейного древа в WordPress.
Version: 1.0
Author: Your Name
*/

defined('ABSPATH') or die('No script kiddies please!');

// Подключение основных файлов
require_once plugin_dir_path(__FILE__) . 'includes/admin-ui.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/save-person.php';
require_once plugin_dir_path(__FILE__) . 'includes/relationships.php';
require_once plugin_dir_path(__FILE__) . 'public/shortcodes.php';

function family_tree_enqueue_scripts() {
    wp_enqueue_script('d3', plugins_url('/public/assets/js/d3.min.js', __FILE__), [], null, true);
	wp_enqueue_script('family-chart', plugins_url('/public/assets/js/family-chart.min.js', __FILE__), ['d3'], null, true);
    wp_enqueue_script('family-tree-js', plugins_url('/public/assets/js/family-tree.js', __FILE__), ['family-chart'], null, true);
    wp_enqueue_style('family-tree-css', plugins_url('/public/assets/css/family-tree.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'family_tree_enqueue_scripts');