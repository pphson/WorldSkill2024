// Lấy phần tử video theo đúng ID định nghĩa trong index.html
const video = document.getElementById('touristVideo');

if (video) {
    // Khởi tạo Intersection Observer để theo dõi tỷ lệ hiển thị của video trên màn hình
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            // Tự động phát khi video hiển thị từ 50% trở lên và tab trình duyệt đang mở
            if (entry.isIntersecting && document.visibilityState === 'visible') {
                video.play().catch((err) => {
                    console.log("Chờ tương tác của người dùng để auto-play:", err);
                });
            } else {
                // Tự động tạm dừng khi video bị cuộn khuất khỏi màn hình (dưới 50%)
                video.pause();
            }
        });
    }, { 
        // Cấu hình ngưỡng kích hoạt (threshold) là 0.5 (tương đương 50% diện tích video)
        threshold: 0.5 
    });

    // Bắt đầu theo dõi phần tử video
    observer.observe(video);

    // Xử lý sự kiện thay đổi trạng thái hiển thị của tab trình duyệt (Page Visibility API)
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            // Khi người dùng quay lại tab, kiểm tra xem video có đang nằm trong khung nhìn không
            const rect = video.getBoundingClientRect();
            const inView = (rect.top < window.innerHeight && rect.bottom >= 0);
            if (inView) {
                video.play().catch(() => {});
            }
        } else {
            // Tự động tạm dừng ngay lập tức khi người dùng ẩn tab hoặc thu nhỏ trình duyệt
            video.pause();
        }
    });
}