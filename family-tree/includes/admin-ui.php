<?php
function family_tree_admin_styles() {
    wp_enqueue_style('family-tree-admin-css', plugins_url('/admin/css/metabox.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'family_tree_admin_styles');

function register_person_post_type() {
    $args = [
        'labels' => [
            'name' => __('Персоны'),
            'singular_name' => __('Персона')
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'thumbnail']
    ];
    register_post_type('person', $args);
}
add_action('init', 'register_person_post_type');