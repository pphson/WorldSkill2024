document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".attraction-card");
    const spots = document.querySelectorAll(".map-spot");

    // 1. Hover từ Thẻ Card -> Bật sáng Chấm Spot trên bản đồ
    cards.forEach(card => {
        const id = card.getAttribute("data-spot");
        const targetSpot = document.querySelector(`.map-spot[data-card="${id}"]`);

        card.addEventListener("mouseenter", () => {
            card.classList.add("active-focus");
            if (targetSpot) targetSpot.classList.add("active-focus");
        });

        card.addEventListener("mouseleave", () => {
            card.classList.remove("active-focus");
            if (targetSpot) targetSpot.classList.remove("active-focus");
        });
    });

    // 2. Hover từ Chấm Spot -> Bật sáng Thẻ Card tương ứng bên góc trái
    spots.forEach(spot => {
        const id = spot.getAttribute("data-card");
        const targetCard = document.querySelector(`.attraction-card[data-spot="${id}"]`);

        spot.addEventListener("mouseenter", () => {
            spot.classList.add("active-focus");
            if (targetCard) targetCard.classList.add("active-focus");
        });

        spot.addEventListener("mouseleave", () => {
            spot.classList.remove("active-focus");
            if (targetCard) targetCard.classList.remove("active-focus");
        });
    });
});