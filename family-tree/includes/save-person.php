<?php
function save_person_data($post_id) {
    if (!isset($_POST['person_nonce']) || !wp_verify_nonce($_POST['person_nonce'], 'save_person_data')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Простые поля
    $fields = ['first_name', 'middle_name', 'last_name', 'gender', 'birth_year', 'death_year'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
    }

    // Девичья фамилия
    if (isset($_POST['maiden_name'])) update_post_meta($post_id, '_maiden_name', sanitize_text_field($_POST['maiden_name']));

    // Умер(ла)
    update_post_meta($post_id, '_died_unknown', isset($_POST['died_unknown']) ? 1 : 0);

    // Связи
    update_post_meta($post_id, '_father', $_POST['father'] ?? '');
    update_post_meta($post_id, '_mother', $_POST['mother'] ?? '');
    update_post_meta($post_id, '_spouses', $_POST['spouses'] ?? []);
    update_post_meta($post_id, '_children', $_POST['children'] ?? []);

    // Обратная совместимость
    require_once plugin_dir_path(__FILE__) . 'relationships.php';
    sync_relationships($post_id);
}
add_action('save_post_person', 'save_person_data');