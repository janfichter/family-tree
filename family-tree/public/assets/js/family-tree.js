document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("FamilyChart");

    if (!window.familyData || !container) {
        console.error("Ошибка: window.familyData не загружен или контейнер отсутствует");
        return;
    }

    // 1. Создаём хранилище
    const store = window.f3.createStore({
        data: window.familyData.data,
        main_id: window.familyData.data?.[0]?.id || null,
        node_separation: window.familyData.node_separation || 250,
        level_separation: window.familyData.level_separation || 150
    });

    // 2. Инициализируем граф
    const view = window.f3.d3AnimationView({
        store: store,
        cont: container
    });

    // 3. Подписываемся на обновления
    store.setOnUpdate((props) => {
        if (typeof view.update === 'function') {
            view.update(props || {});
        } else {
            console.error("view.update — не функция");
        }
    });

    // 4. Обновляем дерево после полной инициализации
    setTimeout(() => {
        if (store.update && typeof store.update.tree === 'function') {
            store.update.tree({ initial: true });
        } else {
            console.error("store.update.tree — не функция");
        }
    }, 100);
});