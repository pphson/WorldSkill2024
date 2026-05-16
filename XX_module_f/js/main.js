// Đăng ký Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('sw.js')
    .then(() => console.log("SW Registered"))
    .catch(err => console.log("SW Failed", err));
}

// Hiệu ứng chuột của CTA Button (Border growing effect)
const ctaBtn = document.getElementById('ctaButton');
if(ctaBtn) {
    ctaBtn.addEventListener('mousemove', (e) => {
        const rect = ctaBtn.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        ctaBtn.style.setProperty('--x', `${x}px`);
        ctaBtn.style.setProperty('--y', `${y}px`);
    });
}

// Render dữ liệu Footer giả định từ footer-content.txt
const footerData = `About Us\nGetting Here\nFAQs\nPlaces to Stay\nThings to Do\nEvents Calendar\nRestaurants\nNightlife\nShopping\nPlan Your Trip\nContact Us\nNewsletter Signup`;
const footerEl = document.getElementById('mainFooter');
if(footerEl) {
    const items = footerData.split('\n');
    let html = '<div class="footer-col"><h4>Links</h4><ul>';
    items.forEach((item, idx) => {
        if(idx === 6) html += '</ul></div><div class="footer-col"><h4>More</h4><ul>';
        html += `<li><a href="#" style="color:#ccc; text-decoration:none;">${item}</a></li>`;
    });
    html += '</ul></div>';
    footerEl.innerHTML = html;
}