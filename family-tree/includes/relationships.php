<?php
function sync_relationships($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $meta = get_post_meta($post_id);

    // Отец
    $father_id = maybe_unserialize($meta['_father'][0] ?? '');
    if ($father_id && is_numeric($father_id)) {
        $father_children = maybe_unserialize(get_post_meta($father_id, '_children', true)) ?: [];
        if (!in_array($post_id, $father_children)) {
            $father_children[] = $post_id;
            update_post_meta($father_id, '_children', $father_children);
        }
    }

    // Мать
    $mother_id = maybe_unserialize($meta['_mother'][0] ?? '');
    if ($mother_id && is_numeric($mother_id)) {
        $mother_children = maybe_unserialize(get_post_meta($mother_id, '_children', true)) ?: [];
        if (!in_array($post_id, $mother_children)) {
            $mother_children[] = $post_id;
            update_post_meta($mother_id, '_children', $mother_children);
        }
    }

    // Супруг(а)
    $spouse_ids = maybe_unserialize($meta['_spouses'][0] ?? []);
    foreach ((array)$spouse_ids as $spouse_id) {
        if (!is_numeric($spouse_id)) continue;

        $spouse_metas = get_post_meta($spouse_id);

        // Добавляем себя как супруга к супругу
        $spouse_spouses = maybe_unserialize($spouse_metas['_spouses'][0] ?? []);
        if (!in_array($post_id, $spouse_spouses)) {
            $spouse_spouses[] = $post_id;
            update_post_meta($spouse_id, '_spouses', $spouse_spouses);
        }

        // Если супруг мужчина или женщина — добавляем его как spouse
        $my_spouses = maybe_unserialize(get_post_meta($post_id, '_spouses', true)) ?: [];
        if (!in_array($spouse_id, $my_spouses)) {
            $my_spouses[] = $spouse_id;
            update_post_meta($post_id, '_spouses', $my_spouses);
        }
    }

    // Дети
    $child_ids = maybe_unserialize($meta['_children'][0] ?? []);
    foreach ((array)$child_ids as $child_id) {
        $child_metas = get_post_meta($child_id);

        // Добавляем текущего человека как отца или мать
        $child_father = maybe_unserialize($child_metas['_father'][0] ?? '');
        $child_mother = maybe_unserialize($child_metas['_mother'][0] ?? '');

        $my_gender = get_post_meta($post_id, '_gender', true);

        if ($my_gender === 'male' && $child_father !== $post_id) {
            update_post_meta($child_id, '_father', $post_id);
        } elseif ($my_gender === 'female' && $child_mother !== $post_id) {
            update_post_meta($child_id, '_mother', $post_id);
        }
    }
}
add_action('save_post_person', 'sync_relationships');