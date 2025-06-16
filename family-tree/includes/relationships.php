<?php
function sync_relationships($person_id) {
    $meta = get_post_meta($person_id);

    // Отец
    if (!empty($meta['_father'][0])) {
        $father_id = $meta['_father'][0];

        // Добавляем себя как ребенка к отцу
        $father_children = get_post_meta($father_id, '_children', true) ?: [];
        if (!in_array($person_id, $father_children)) {
            $father_children[] = $person_id;
            update_post_meta($father_id, '_children', $father_children);
        }
    }

    // Мать
    if (!empty($meta['_mother'][0])) {
        $mother_id = $meta['_mother'][0];

        $mother_children = get_post_meta($mother_id, '_children', true) ?: [];
        if (!in_array($person_id, $mother_children)) {
            $mother_children[] = $person_id;
            update_post_meta($mother_id, '_children', $mother_children);
        }
    }

    // Супруг(а)
    $spouse_ids = maybe_unserialize(get_post_meta($person_id, '_spouses', true));
    foreach ((array)$spouse_ids as $spouse_id) {
        // Делаем себя родителем детям супруга
        $spouse_children = get_post_meta($spouse_id, '_children', true) ?: [];
        if (!in_array($person_id, $spouse_children)) {
            $spouse_children[] = $person_id;
            update_post_meta($spouse_id, '_children', $spouse_children);
        }

        // Делаем супруга супругом себе (обратная связь)
        $my_spouses = get_post_meta($person_id, '_spouses', true) ?: [];
        if (!in_array($spouse_id, $my_spouses)) {
            $my_spouses[] = $spouse_id;
            update_post_meta($person_id, '_spouses', $my_spouses);
        }
    }

    // Дети
    $children_ids = maybe_unserialize(get_post_meta($person_id, '_children', true));
    foreach ((array)$children_ids as $child_id) {
        // Добавляем себя как отца или мать
        $child_rels = get_post_meta($child_id, '_parents', true) ?: [];

        // Проверяем пол текущей персоны
        $my_gender = get_post_meta($person_id, '_gender', true);

        if ($my_gender === 'male') {
            if (!in_array($person_id, $child_rels)) {
                $child_rels[] = $person_id;
                update_post_meta($child_id, '_father', $person_id);
            }
        } else {
            if (!in_array($person_id, $child_rels)) {
                $child_rels[] = $person_id;
                update_post_meta($child_id, '_mother', $person_id);
            }
        }
    }
}
add_action('save_post_person', 'sync_relationships');