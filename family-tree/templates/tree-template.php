<?php

$people = get_posts([
    'post_type' => 'person',
    'post_status' => 'publish',
    'numberposts' => -1
]);

$data = [];

foreach ($people as $p) {
    $meta = get_post_meta($p->ID);

    // Получаем связи
    $father_id = maybe_unserialize($meta['_father'][0] ?? '');
    $mother_id = maybe_unserialize($meta['_mother'][0] ?? '');
    $spouse_ids = maybe_unserialize($meta['_spouses'][0] ?? []);
    $child_ids = maybe_unserialize($meta['_children'][0] ?? []);

    // Генерируем аватар
    $image = has_post_thumbnail($p->ID)
        ? get_the_post_thumbnail_url($p->ID, 'thumbnail')
        : (
            $meta['_gender'][0] === 'female'
                ? plugins_url('/images/female.png', __FILE__)
                : plugins_url('/images/male.png', __FILE__)
        );

    // Гендер M/F
    $gender = strtoupper(substr($meta['_gender'][0] ?? '', 0, 1));
    if (!in_array($gender, ['M', 'F'])) $gender = 'M';

    // Формируем запись
    $person = [
        'id' => (string)$p->ID,
        'rels' => [],
        'data' => [
            'first name' => $meta['_first_name'][0] ?? '',
            'middle name' => $meta['_middle_name'][0] ?? '',
            'last name' => $meta['_last_name'][0] ?? '',
            'avatar' => $image,
            'gender' => $gender
        ]
    ];

    // Добавляем девичью фамилию
    if (!empty($meta['_maiden_name'][0])) {
        $person['data']['maiden name'] = $meta['_maiden_name'][0];
    }

    // Дата рождения и смерти
    if (!empty($meta['_birth_year'][0])) {
        $person['data']['birthday'] = $meta['_birth_year'][0];
    }

    if (!empty($meta['_death_year'][0])) {
        $person['data']['death'] = $meta['_death_year'][0];
    } elseif (!empty($meta['_died_unknown'][0])) {
        $person['data']['death'] = ($gender === 'F') ? 'Умерла' : 'Умер';
    }

    // Родственные связи
    if (!empty($father_id)) $person['rels']['father'] = (string)$father_id;
    if (!empty($mother_id)) $person['rels']['mother'] = (string)$mother_id;

    if (!empty($spouse_ids)) {
        $person['rels']['spouses'] = array_map('strval', array_filter((array)$spouse_ids, 'is_numeric'));
    }

    if (!empty($child_ids)) {
        $person['rels']['children'] = array_map('strval', array_filter((array)$child_ids, 'is_numeric'));
    }

    $data[] = $person;
}

// Передача данных в JS
echo '<div id="FamilyChart"></div>';
echo '<script>window.familyData = ' . json_encode([
    'data' => $data,
    'node_separation' => 250,
    'level_separation' => 150
], JSON_UNESCAPED_UNICODE) . ';</script>';