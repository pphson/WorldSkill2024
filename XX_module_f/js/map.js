// Liên kết tương tác Hover đồng bộ giữa Thẻ và Điểm mốc trên bản đồ
const cards = document.querySelectorAll('.attraction-card');
const spots = document.querySelectorAll('.map-spot');

cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.classList.add('active-focus');
        const spotNum = card.getAttribute('data-spot');
        const targetSpot = document.querySelector(`.map-spot[data-card="${spotNum}"]`);
        if (targetSpot) targetSpot.classList.add('active-focus');
    });
    card.addEventListener('mouseleave', () => {
        card.classList.remove('active-focus');
        const spotNum = card.getAttribute('data-spot');
        const targetSpot = document.querySelector(`.map-spot[data-card="${spotNum}"]`);
        if (targetSpot) targetSpot.classList.remove('active-focus');
    });
});

spots.forEach(spot => {
    spot.addEventListener('mouseenter', () => {
        spot.classList.add('active-focus');
        const cardId = spot.getAttribute('data-card');
        const targetCard = document.querySelector(`.attraction-card[data-spot="${cardId}"]`);
        if (targetCard) targetCard.classList.add('active-focus');
    });
    spot.addEventListener('mouseleave', () => {
        spot.classList.remove('active-focus');
        const cardId = spot.getAttribute('data-card');
        const targetCard = document.querySelector(`.attraction-card[data-spot="${cardId}"]`);
        if (targetCard) targetCard.classList.remove('active-focus');
    });
});