<div class="family-person-form">
    <div class="form-section">
        <h3>Основные данные</h3>
        <table class="form-table">
            <tr>
                <th><label for="first_name">Имя</label></th>
                <td><input type="text" id="first_name" name="first_name" value="<?= esc_attr($first_name); ?>" class="widefat"></td>
            </tr>
            <tr>
                <th><label for="middle_name">Отчество</label></th>
                <td><input type="text" id="middle_name" name="middle_name" value="<?= esc_attr($middle_name); ?>" class="widefat"></td>
            </tr>
            <tr>
                <th><label for="last_name">Фамилия</label></th>
                <td><input type="text" id="last_name" name="last_name" value="<?= esc_attr($last_name); ?>" class="widefat"></td>
            </tr>
            <tr>
                <th><label for="gender">Пол</label></th>
                <td>
                    <select id="gender" name="gender" class="widefat">
                        <option value="male" <?= $gender === 'male' ? 'selected' : '' ?>>Мужской</option>
                        <option value="female" <?= $gender === 'female' ? 'selected' : '' ?>>Женский</option>
                    </select>
                </td>
            </tr>
            <tr id="maiden-name-row" style="display:<?= $gender === 'female' ? 'table-row' : 'none' ?>">
                <th><label for="maiden_name">Девичья фамилия</label></th>
                <td><input type="text" id="maiden_name" name="maiden_name" value="<?= esc_attr($maiden_name); ?>" class="widefat"></td>
            </tr>
        </table>
    </div>

    <div class="form-section">
        <h3>Даты жизни</h3>
        <table class="form-table">
            <tr>
                <th><label for="birth_year">Год рождения</label></th>
                <td><input type="number" id="birth_year" name="birth_year" value="<?= esc_attr($birth_year); ?>" class="small-text"></td>
            </tr>
            <tr>
                <th><label for="death_year">Год смерти</label></th>
                <td>
                    <input type="number" id="death_year" name="death_year" value="<?= esc_attr($death_year); ?>" class="small-text">
                    <label>
                        <input type="checkbox" name="died_unknown" id="died_unknown" <?= $died_unknown ? 'checked' : '' ?>>
                        <span id="died_label"><?= ($gender === 'female') ? 'Умерла (дата неизвестна)' : 'Умер (дата неизвестна)' ?></span>
                    </label>
                </td>
            </tr>
        </table>
    </div>

    <div class="form-section">
        <h3>Родственные связи</h3>
        <table class="form-table">
            <tr>
                <th><label for="father">Отец</label></th>
                <td>
                    <select id="father" name="father" class="widefat">
                        <option value="">Нет</option>
                        <?php foreach ($people as $p): if ($p->ID == $post->ID) continue; ?>
                            <option value="<?= $p->ID ?>" <?= $p->ID == $father_id ? 'selected' : '' ?>>
                                <?= esc_html($p->post_title . ' (' . get_post_meta($p->ID, '_first_name', true) . ' ' . get_post_meta($p->ID, '_last_name', true) . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="mother">Мать</label></th>
                <td>
                    <select id="mother" name="mother" class="widefat">
                        <option value="">Нет</option>
                        <?php foreach ($people as $p): if ($p->ID == $post->ID) continue; ?>
                            <option value="<?= $p->ID ?>" <?= $p->ID == $mother_id ? 'selected' : '' ?>>
                                <?= esc_html($p->post_title . ' (' . get_post_meta($p->ID, '_first_name', true) . ' ' . get_post_meta($p->ID, '_last_name', true) . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="spouses">Супруг(а)</label></th>
                <td>
                    <select id="spouses" name="spouses[]" multiple class="widefat">
                        <?php foreach ($people as $p): if ($p->ID == $post->ID) continue; ?>
                            <option value="<?= $p->ID ?>" <?= in_array($p->ID, $spouses_ids) ? 'selected' : '' ?>>
                                <?= esc_html($p->post_title . ' (' . get_post_meta($p->ID, '_first_name', true) . ' ' . get_post_meta($p->ID, '_last_name', true) . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="children">Дети</label></th>
                <td>
                    <select id="children" name="children[]" multiple class="widefat">
                        <?php foreach ($people as $p): if ($p->ID == $post->ID) continue; ?>
                            <option value="<?= $p->ID ?>" <?= in_array($p->ID, $children_ids) ? 'selected' : '' ?>>
                                <?= esc_html($p->post_title . ' (' . get_post_meta($p->ID, '_first_name', true) . ' ' . get_post_meta($p->ID, '_last_name', true) . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- Скрипт для динамического изменения меток -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const genderSelect = document.getElementById("gender");
    const diedLabel = document.getElementById("died_label");
    const diedCheckbox = document.getElementById("died_unknown");
    const maidenRow = document.getElementById("maiden-name-row");

    genderSelect.addEventListener("change", function () {
        const isFemale = this.value === "female";
        // Обновляем текст у чекбокса
        diedLabel.textContent = isFemale ? "Умерла (дата неизвестна)" : "Умер (дата неизвестна)";
        // Показываем/скрываем девичью фамилию
        maidenRow.style.display = isFemale ? "table-row" : "none";
    });

    // Инициализация при загрузке страницы
    if (genderSelect.value === "female") {
        maidenRow.style.display = "table-row";
    }
});
</script>