<?php
function family_tree_register_metabox() {
    add_meta_box(
        'family_person_relations',
        'Родственные связи',
        'family_tree_person_metabox',
        'person',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'family_tree_register_metabox');

function family_tree_person_metabox($post) {
    wp_nonce_field('save_family_tree_rels', 'family_tree_rels_nonce');

    $all_people = get_posts([
        'post_type' => 'person',
        'post_status' => 'publish',
        'numberposts' => -1
    ]);

    $meta = get_post_meta($post->ID);

    $father_id = maybe_unserialize($meta['_father'][0] ?? '');
    $mother_id = maybe_unserialize($meta['_mother'][0] ?? '');
    $spouse_ids = maybe_unserialize($meta['_spouses'][0] ?? []);
    $child_ids = maybe_unserialize($meta['_children'][0] ?? '');

    ?>
    <table class="form-table">
        <tr>
            <th><label for="_first_name">Имя</label></th>
            <td><input type="text" name="_first_name" value="<?= esc_attr($meta['_first_name'][0] ?? '') ?>" class="widefat"></td>
        </tr>
        <tr>
            <th><label for="_middle_name">Отчество</label></th>
            <td><input type="text" name="_middle_name" value="<?= esc_attr($meta['_middle_name'][0] ?? '') ?>" class="widefat"></td>
        </tr>
        <tr>
            <th><label for="_last_name">Фамилия</label></th>
            <td><input type="text" name="_last_name" value="<?= esc_attr($meta['_last_name'][0] ?? '') ?>" class="widefat"></td>
        </tr>
        <tr>
            <th><label for="_maiden_name">Девичья фамилия</label></th>
            <td><input type="text" name="_maiden_name" value="<?= esc_attr($meta['_maiden_name'][0] ?? '') ?>" class="widefat"></td>
        </tr>
        <tr>
            <th><label for="_gender">Пол</label></th>
            <td>
                <select name="_gender" id="_gender" class="widefat">
                    <option value="male" <?= selected($meta['_gender'][0] ?? '', 'male') ?>>Мужской</option>
                    <option value="female" <?= selected($meta['_gender'][0] ?? '', 'female') ?>>Женский</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_father">Отец</label></th>
            <td>
                <select name="_father" id="_father" class="widefat">
                    <option value="">Нет</option>
                    <?php foreach ($all_people as $person): ?>
                        <option value="<?= $person->ID ?>" <?= selected($person->ID, $father_id) ?>>
                            <?= get_the_title($person->ID) ?> (<?= $person->ID ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_mother">Мать</label></th>
            <td>
                <select name="_mother" id="_mother" class="widefat">
                    <option value="">Нет</option>
                    <?php foreach ($all_people as $person): ?>
                        <option value="<?= $person->ID ?>" <?= selected($person->ID, $mother_id) ?>>
                            <?= get_the_title($person->ID) ?> (<?= $person->ID ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_spouses">Супруг(а)</label></th>
            <td>
                <select name="_spouses[]" id="_spouses" multiple class="widefat">
                    <?php foreach ($all_people as $person): ?>
                        <option value="<?= $person->ID ?>" <?= in_array($person->ID, (array)$spouse_ids) ? 'selected' : '' ?>>
                            <?= get_the_title($person->ID) ?> (<?= $person->ID ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_children">Дети</label></th>
            <td>
                <select name="_children[]" id="_children" multiple class="widefat">
                    <?php foreach ($all_people as $person): ?>
                        <option value="<?= $person->ID ?>" <?= in_array($person->ID, (array)$child_ids) ? 'selected' : '' ?>>
                            <?= get_the_title($person->ID) ?> (<?= $person->ID ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
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