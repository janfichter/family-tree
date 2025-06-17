document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("FamilyChart");

    if (!window.familyData || !container) return;

    console.log("window.familyData:", window.familyData);

    const store = window.f3.createStore({
        data: window.familyData.data,
        main_id: window.familyData.data[0]?.id || null,
        node_separation: window.familyData.node_separation || 250,
        level_separation: window.familyData.level_separation || 150
    });

    console.log("store.state после создания:", store.state);

    const view = window.f3.d3AnimationView({
        store: store,
        cont: container,

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
        ]
    });

    store.setOnUpdate((props) => {
        if (typeof view.update === 'function') {
            view.update(props || {});
        } else {
            console.error("view.update — не функция");
        }
    });

    setTimeout(() => {
        store.update.tree({ initial: true });
        console.log("store.getTree() после update.tree:", store.getTree());
    }, 100);
});