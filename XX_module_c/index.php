<?php
// Bật hiển thị lỗi để dễ debug trong quá trình code
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Khai báo hằng số đường dẫn gốc để dễ gọi file
define('BASE_PATH', __DIR__);
define('CONTENT_PATH', BASE_PATH . '/Module C Media Files/content-pages');

// Nhúng các file xử lý logic (Thứ tự rất quan trọng)
require_once BASE_PATH . '/includes/file_scanner.php';
require_once BASE_PATH . '/includes/parser.php';
require_once BASE_PATH . '/includes/search_logic.php';

// 1. Phân tích URL (Routing)
$requestUrl = isset($_GET['url']) ? rtrim(urldecode($_GET['url']), '/') : '';
$urlParts = explode('/', $requestUrl);

$route = $urlParts[0];

// XỬ LÝ TÌM KIẾM (SEARCH) - Nếu có tham số ?q=...
if (isset($_GET['q'])) {
    $searchQuery = $_GET['q'];
    $articles = searchArticles($searchQuery);
    $listTitle = "Search Results for: " . htmlspecialchars($searchQuery);
    
    require BASE_PATH . '/templates/list_view.php';
    exit;
}

// 2. ĐIỀU HƯỚNG LUỒNG XỬ LÝ
if ($route === '' || $route === 'heritages') {

    // Xác định thư mục đang đứng
    $currentRelativePath = '';
    if ($route === 'heritages' && count($urlParts) > 1) {
        $folderParts = array_slice($urlParts, 1);
        $currentRelativePath = implode('/', $folderParts);
    }

    $targetPath = CONTENT_PATH . ($currentRelativePath ? '/' . $currentRelativePath : '');

    // Trường hợp 1: Nếu đường dẫn là một file (Xem chi tiết bài viết)
    if (is_file($targetPath . '.txt') || is_file($targetPath . '.html')) {
        $filePath = is_file($targetPath . '.txt') ? $targetPath . '.txt' : $targetPath . '.html';

        $articleData = parseArticle($filePath); // Hàm này nằm trong parser.php

        require BASE_PATH . '/templates/single_view.php';
        exit;
    }
    // Trường hợp 2: Nếu đường dẫn là một thư mục (Xem danh sách)
    elseif (is_dir($targetPath)) {
        // Lúc này hàm scanAndFilterContent đã được định nghĩa ở file_scanner.php
        $contentList = scanAndFilterContent($targetPath);

        require BASE_PATH . '/templates/list_view.php';
        exit;
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Không tìm thấy trang</h1>";
        exit;
    }

} elseif ($route === 'tags') {
    // XỬ LÝ LỌC THEO TAG
    $tagName = isset($urlParts[1]) ? urldecode($urlParts[1]) : '';
    $articles = getArticlesByTag($tagName); // Hàm này nằm trong search_logic.php
    $listTitle = "Articles tagged with: " . htmlspecialchars($tagName);
    
    require BASE_PATH . '/templates/list_view.php';
    exit;
} else {
    // Bắt lỗi 404 cho các route lạ
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - Không tìm thấy trang</h1>";
    exit;
}
?>