<?php
function scanAndFilterContent($dirPath) {
    // Đảm bảo thư mục tồn tại
    if (!is_dir($dirPath)) return ['folders' => [], 'files' => []];

    $items = scandir($dirPath);
    $folders = [];
    $files = [];

    foreach ($items as $item) {
        // 1. Lọc bỏ file rác macOS và thư mục dùng chung 'images'
        if ($item === '.' || $item === '..' || $item === '.DS_Store' || strpos($item, '._') === 0 || $item === 'images') {
            continue;
        }

        $fullPath = $dirPath . '/' . $item;

        if (is_dir($fullPath)) {
            // Nếu là thư mục con, đẩy vào mảng folders
            $folders[] = $item;
        } else {
            // Nếu là file, kiểm tra định dạng YYYY-MM-DD-title.txt/html
            if (preg_match('/^(\d{4}-\d{2}-\d{2})-(.*)\.(txt|html)$/i', $item, $matches)) {
                $dateStr = $matches[1];
                
                // Lọc bỏ bài viết có ngày trong tương lai
                if (strtotime($dateStr) > time()) {
                    continue; 
                }

                $files[] = [
                    'filename' => $item,
                    'date' => $dateStr,
                    'path' => $fullPath
                ];
            }
        }
    }

    // Sắp xếp thư mục theo A-Z
    usort($folders, 'strcasecmp');

    // Sắp xếp file theo Z-A (Mới nhất lên đầu)
    usort($files, function($a, $b) {
        return strcmp($b['filename'], $a['filename']); 
    });

    return [
        'folders' => $folders,
        'files' => $files
    ];
}
?>