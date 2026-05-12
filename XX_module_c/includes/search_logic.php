<?php
// Hàm quét đệ quy lấy TOÀN BỘ file tĩnh trong hệ thống
function getAllFilesRecursive($dir) {
    $results = [];
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === '.DS_Store' || strpos($item, '._') === 0 || $item === 'images') continue;
        
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            $results = array_merge($results, getAllFilesRecursive($path));
        } elseif (preg_match('/^(\d{4}-\d{2}-\d{2})-(.*)\.(txt|html)$/i', $item)) {
            $results[] = $path;
        }
    }
    return $results;
}

// Hàm Tìm kiếm đa từ khóa (Logic OR)
function searchArticles($queryString) {
    $allFiles = getAllFilesRecursive(CONTENT_PATH);
    
    // Tách từ khóa bằng dấu '/' và loại bỏ khoảng trắng dư thừa
    $keywords = array_map('trim', explode('/', $queryString));
    $keywords = array_filter($keywords); // Loại bỏ phần tử rỗng
    
    $matchedArticles = [];

    foreach ($allFiles as $filePath) {
        $article = parseArticle($filePath); // Tái sử dụng hàm parse ở bước trước
        
        // Bỏ qua file Draft và file Tương lai
        if ($article['is_draft'] || strtotime($article['date']) > time()) continue;

        $isMatch = false;
        foreach ($keywords as $kw) {
            // Kiểm tra: Có nằm trong Title hoặc Content không (không phân biệt hoa thường)
            if (stripos($article['title'], $kw) !== false || stripos($article['html_content'], $kw) !== false) {
                $isMatch = true;
                break; // Thỏa mãn điều kiện OR nên dừng vòng lặp từ khóa
            }
        }

        if ($isMatch) {
            $matchedArticles[] = $article;
        }
    }

    // Sắp xếp bài viết mới nhất lên đầu (Z-A theo ngày)
    usort($matchedArticles, function($a, $b) {
        return strcmp($b['date'], $a['date']);
    });

    return $matchedArticles;
}

// Hàm Lọc theo Tag
function getArticlesByTag($targetTag) {
    $allFiles = getAllFilesRecursive(CONTENT_PATH);
    $matchedArticles = [];

    foreach ($allFiles as $filePath) {
        $article = parseArticle($filePath);
        if ($article['is_draft'] || strtotime($article['date']) > time()) continue;

        // Chuyển tất cả tags về chữ thường để so sánh chính xác
        $lowerTags = array_map('strtolower', $article['tags']);
        if (in_array(strtolower($targetTag), $lowerTags)) {
            $matchedArticles[] = $article;
        }
    }

    usort($matchedArticles, function($a, $b) {
        return strcmp($b['date'], $a['date']);
    });

    return $matchedArticles;
}
?>