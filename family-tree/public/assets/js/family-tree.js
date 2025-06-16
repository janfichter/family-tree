document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("FamilyChart");

    if (!window.familyData || !container) return;

    const store = window.f3.createStore(window.familyData);

    // Устанавливаем главного человека
    if (!store.state.main_id && store.state.data.length > 0) {
        store.update.mainId(store.state.data[0].id);
    }

    const view = window.f3.d3AnimationView({
        store: store,
        cont: container
    });

    const Card = window.f3.elements.Card({
        store: store,
        svg: view.svg,
        card_dim: {
            w: 220,
            h: 100,
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

    view.setCard(Card);
    store.update.tree({ initial: true });

    console.log("store.getData():", store.getData());
    console.log("store.getTree():", store.getTree());
});