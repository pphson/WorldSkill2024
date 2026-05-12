document.addEventListener("DOMContentLoaded", function() {

    // 1. TÍNH NĂNG COVER IMAGE SPOTLIGHT [cite: 129]
    const coverWrapper = document.getElementById('coverWrapper');
    const coverImage = document.getElementById('coverImage');

    if (coverWrapper && coverImage) {
        coverWrapper.addEventListener('mousemove', function(e) {
            // Lấy kích thước và vị trí của wrapper
            const rect = coverWrapper.getBoundingClientRect();
            // Tính toán tọa độ chuột tương đối bên trong wrapper
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            // Cập nhật biến CSS --x và --y liên tục
            coverImage.style.setProperty('--x', `${x}px`);
            coverImage.style.setProperty('--y', `${y}px`);
        });

        // Tùy chọn: Reset lại vị trí ra giữa khi chuột rời đi
        coverWrapper.addEventListener('mouseleave', function() {
            coverImage.style.setProperty('--x', `50%`);
            coverImage.style.setProperty('--y', `50%`);
        });
    }

    // 2. TÍNH NĂNG ZOOM ẢNH (Phóng to / Thu nhỏ) [cite: 159-161]
    const contentImages = document.querySelectorAll('.post-body img');
    let currentlyEnlarged = null;

    // Hàm đóng ảnh
    const closeImage = () => {
        if (currentlyEnlarged) {
            currentlyEnlarged.classList.remove('img-enlarged');
            currentlyEnlarged = null;
        }
    };

    contentImages.forEach(img => {
        img.addEventListener('click', function(e) {
            e.stopPropagation(); // Ngăn sự kiện bong bóng
            
            if (currentlyEnlarged === this) {
                // Nếu click lại chính ảnh đang mở thì đóng [cite: 160]
                closeImage();
            } else {
                // Nếu có ảnh khác đang mở thì đóng nó đi trước
                closeImage();
                // Bật ảnh hiện tại lên
                this.classList.add('img-enlarged');
                currentlyEnlarged = this;
            }
        });
    });

    // Cuộn chuột (scroll) thì đóng ảnh [cite: 161]
    window.addEventListener('scroll', closeImage);
});