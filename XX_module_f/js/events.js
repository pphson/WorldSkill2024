// Dữ liệu thô trích xuất từ file văn bản hệ thống
const rawEventsData = `Lyon accueille la finale mondiale des Worldskills 2024
worldskills-2024-p.jpg

Forum des associations 2024
fda-p.jpg

Lyon Kayak
lyon-kayak-p-0.jpg

La semaine bleue 2024
semaine-bleue-2024-p.jpg

Le Village des Métiers
village-des-metiers-p.jpg

Les Journées Portes Ouvertes des Entreprises
journees_portes_ouvertes_entreprises_2023_p.jpg`;

const slider = document.getElementById('eventsSlider');

if (slider) {
    const blocks = rawEventsData.trim().split('\n\n');
    
    blocks.forEach(block => {
        const lines = block.split('\n');
        if (lines.length >= 2) {
            const title = lines[0].trim();
            const imgName = lines[1].trim(); // Ví dụ: fda-p.jpg hoặc worldskills-2024-p.jpg

            // Tách tên file và định dạng mở rộng để ghép chuỗi chính xác cho file low-res
            const dotIndex = imgName.lastIndexOf('.');
            const baseName = imgName.substring(0, dotIndex); // VD: fda-p
            
            // Lưu ý đặc biệt: File worldskills-2024-p-low-res dùng đuôi .png theo cấu trúc thư mục của bạn
            const lowResExt = (baseName === 'worldskills-2024-p') ? 'png' : imgName.split('.').pop();
            const lowResName = `${baseName}-low-res.${lowResExt}`;

            const card = document.createElement('div');
            card.className = 'event-card';
            
            // Sử dụng phần tử <picture> để trình duyệt tự động tính toán và tải ảnh tối ưu theo độ rộng màn hình
            card.innerHTML = `
                <div class="card-image-wrapper">
                    <picture>
                        <source media="(max-width: 759px)" srcset="latest-events-images/${lowResName}">
                        <source media="(min-width: 760px)" srcset="latest-events-images/${imgName}">
                        <img src="latest-events-images/${imgName}" alt="${title}" loading="lazy">
                    </picture>
                </div>
                <h3>${title}</h3>
            `;
            
            slider.appendChild(card);
        }
    });
}