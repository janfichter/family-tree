/family-tree/
│
├── admin/    
│   ├── css/
│   └── metabox.css                // Стили для админ-панели
│
├── family-tree.php                // Основной файл плагина (точка входа)
│
├── includes/
│   ├── admin-ui.php               // Регистрация кастомного типа "person"
│   ├── meta-boxes.php             // Добавление метабоксов в админке
│   ├── save-person.php            // Сохранение данных персоны
│   └── relationships.php          // Обработка родственных связей (обратные связи)
│
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── family-tree.css    // Базовые стили для контейнера графа
│   │   └── js/
│   │       └── family-tree.js     // Инициализация D3-графа
│   └── shortcodes.php             // Шорткод [family_tree] для вывода древа на странице
│
├── templates/
│   ├── person-metabox-fields.php  // HTML-форма полей метабокса (админка)
│   └── tree-template.php          // Шаблон генерации JSON и разметки графа
│
└── languages/                     // Локализация (по желанию, не обязателен)