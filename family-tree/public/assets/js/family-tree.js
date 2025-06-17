document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("FamilyChart");

    if (!window.familyData || !container) return;

    console.log("window.familyData:", window.familyData);
	console.log("window.f3:", window.f3);

    // Создаём хранилище данных
    const store = window.f3.createStore({
        data: window.familyData.data,
        main_id: window.familyData.data[0]?.id || null,
        node_separation: window.familyData.node_separation || 250,
        level_separation: window.familyData.level_separation || 150
    });

    console.log("store.state:", store.state);
    console.log("store.getTree():", store.getTree());

    // Проверяем, что store.update.tree() существует
    if (typeof store.update.tree !== 'function') {
        console.error("store.update.tree — не функция");
        return;
    }

    // Инициализируем представление графа
    const view = window.f3.d3AnimationView({
        store: store,
        cont: container,

        // Добавляем параметры карточки напрямую
        card_dim: {
            w: 220,
            h: 80,
            text_x: 80,
            text_y: 10,
            img_w: 60,
            img_h: 60,
            img_x: 10,
            img_y: 15
        },
        card_display: [
            d => d.data["first name"] || "",
            d => d.data["middle name"] || "",
            d => d.data["last name"] || "",
            d => d.data["maiden name"] || "",
            d => `${d.data["birthday"] || ""} – ${d.data["death"] || ""}`
        ],
        mini_tree: true,
        link_break: false
    });

    // Обновляем дерево
    store.setOnUpdate((props) => view.update(props || {}));
    store.update.tree({ initial: true });

    console.log("store.getTree() после update.tree:", store.getTree());
});