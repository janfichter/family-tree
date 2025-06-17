/family-tree/
│
├── admin/    
│   ├── css/
│   └── metabox.css                // Стили для админ-панели
│
├── family-tree.php         ← Главный файл плагина + шорткод
├── templates/
│   └── tree-template.php  ← Формирование JSON
├── includes/
│   ├── meta-boxes.php      ← Метабоксы в админке
│   └── relationships.php  ← Обновление обратных связей
├── public/
│   ├── assets/
│   │   ├── js/
│   │   │   ├── d3.v4.min.js (если нужно)
│   │   │   ├── family-chart.min.js ← Исправленная библиотека
│   │   │   └── family-tree.js    ← Инициализация графа
│   │   └── css/
│   │       └── family-tree.css   ← Стили дерева
│   └── images/
│       ├── male.png
│       └── female.png
├── README.txt              ← Краткая инструкция по использованию
└── languages/                     // Локализация (по желанию, не обязателен)