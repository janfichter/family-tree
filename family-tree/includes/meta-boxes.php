<?php
function family_tree_add_meta_boxes() {
    add_meta_box('person_details', 'Детали персоны', 'render_person_details_metabox', 'person', 'normal', 'high');
}
add_action('add_meta_boxes', 'family_tree_add_meta_boxes');

function render_person_details_metabox($post) {
    wp_nonce_field('save_person_data', 'person_nonce');

    $first_name = get_post_meta($post->ID, '_first_name', true);
    $middle_name = get_post_meta($post->ID, '_middle_name', true);
    $last_name = get_post_meta($post->ID, '_last_name', true);
    $maiden_name = get_post_meta($post->ID, '_maiden_name', true);
    $gender = get_post_meta($post->ID, '_gender', true);
    $birth_year = get_post_meta($post->ID, '_birth_year', true);
    $death_year = get_post_meta($post->ID, '_death_year', true);
    $died_unknown = get_post_meta($post->ID, '_died_unknown', true);

    $father_id = get_post_meta($post->ID, '_father', true);
    $mother_id = get_post_meta($post->ID, '_mother', true);
    $spouses_ids = get_post_meta($post->ID, '_spouses', true) ?: [];
    $children_ids = get_post_meta($post->ID, '_children', true) ?: [];

    $people = get_posts([
        'post_type' => 'person',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'exclude' => [$post->ID]
    ]);

    include plugin_dir_path(__FILE__) . '../templates/person-metabox-fields.php';
}
function family_tree_admin_scripts($hook) {
    if ($hook !== 'post.php' && $hook !== 'post-new.php') return;

    // Подключаем jQuery и Select2
    wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',  ['jquery'], null, true);
    wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'); 

    // Инициализация Select2
    wp_add_inline_script('select2', '
        jQuery(document).ready(function($) {
            $("#spouses, #children").select2({
                placeholder: "Выберите...",
                allowClear: true,
                width: "100%"
            });
        });
    ');
}
add_action('admin_enqueue_scripts', 'family_tree_admin_scripts');