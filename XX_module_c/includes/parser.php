<?php
function parseArticle($filePath) {
    $rawContent = file_get_contents($filePath);
    $filename = basename($filePath);
    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    
    $frontMatter = [];
    $mainContent = $rawContent;

    // 1. Dùng Regex để tách Front-matter (Bắt phần text nằm giữa 2 hàng --- ở đầu file)
    if (preg_match('/^---\s*(.*?)\s*---\s*(.*)/s', $rawContent, $matches)) {
        $metaString = trim($matches[1]);
        $mainContent = trim($matches[2]);
        
        // Phân tích từng dòng của Front-matter
        $metaLines = explode("\n", $metaString);
        foreach ($metaLines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) == 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                // Nếu là tags, chuyển thành mảng
                if ($key === 'tags') {
                    $frontMatter[$key] = array_map('trim', explode(',', $value));
                } else {
                    $frontMatter[$key] = $value;
                }
            }
        }
    }

    // Nếu bài viết này là draft (nháp), ta có thể đánh dấu để ẩn đi ở ngoài danh sách
    if (isset($frontMatter['draft']) && strtolower($frontMatter['draft']) === 'true') {
        $frontMatter['is_draft'] = true;
    } else {
        $frontMatter['is_draft'] = false;
    }

    // 2. Logic trích xuất Tiêu đề (Fallback Title) theo đúng 3 tiêu chí của đề bài
    $title = '';
    if (!empty($frontMatter['title'])) {
        $title = $frontMatter['title']; // Ưu tiên 1: Lấy từ Front-matter
    } elseif (preg_match('/<h1>(.*?)<\/h1>/i', $mainContent, $h1Match)) {
        $title = strip_tags($h1Match[1]); // Ưu tiên 2: Lấy từ thẻ H1 đầu tiên
    } else {
        // Ưu tiên 3: Lấy từ tên file (bỏ 11 ký tự ngày tháng và đuôi file, thay '-' bằng khoảng trắng)
        $slug = pathinfo($filename, PATHINFO_FILENAME); // Vd: 2024-09-01-example-page
        $slug = substr($slug, 11); // Thành: example-page
        $title = ucwords(str_replace('-', ' ', $slug)); // Thành: Example Page
    }

    // 3. Logic xử lý Cover Image mặc định
    $fallbackCover = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
    $coverImage = isset($frontMatter['cover']) ? $frontMatter['cover'] : $fallbackCover;

    // 4. Render nội dung
    if ($ext === 'txt') {
        $mainContent = renderTxtToHtml($mainContent);
    } else {
        // Nếu là HTML, chỉ cần sửa lại đường dẫn ảnh cho đúng với thư mục gốc
        $mainContent = preg_replace('/src=["\']([^"\']+)["\']/i', 'src="/XX_module_c/Module C Media Files/content-pages/images/$1"', $mainContent);
    }

    // 5. Build URL path
    $normalizedContentPath = str_replace('\\', '/', CONTENT_PATH);
    $normalizedFilePath = str_replace('\\', '/', $filePath);
    $relPath = str_replace($normalizedContentPath . '/', '', $normalizedFilePath);
    $urlPath = preg_replace('/\.(txt|html)$/i', '', $relPath);

    return [
        'title' => $title,
        'cover' => '/XX_module_c/Module C Media Files/content-pages/images/' . $coverImage,
        'date' => substr($filename, 0, 10), // Lấy YYYY-MM-DD từ tên file
        'tags' => isset($frontMatter['tags']) ? $frontMatter['tags'] : [],
        'is_draft' => $frontMatter['is_draft'],
        'html_content' => $mainContent,
        'url_path' => $urlPath,
        'summary' => isset($frontMatter['summary']) ? $frontMatter['summary'] : ''
    ];
}

function renderTxtToHtml($txtContent) {
    $lines = explode("\n", $txtContent);
    $html = '';
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue; // Bỏ qua dòng trống
        
        // Kiểm tra xem dòng này có phải là tên file ảnh không (không có khoảng trắng, kết thúc bằng đuôi ảnh)
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $line) && strpos($line, ' ') === false) {
            $imagePath = "/XX_module_c/Module C Media Files/content-pages/images/" . $line;
            // Class 'content-image' để lát nữa viết JS zoom ảnh
            $html .= "<img src='{$imagePath}' alt='Image in content' class='content-image'>\n"; 
        } else {
            // Chuyển dòng text bình thường thành thẻ <p>
            $html .= "<p>{$line}</p>\n";
        }
    }
    return $html;
}
?>