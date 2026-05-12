<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

// 1. Xử lý tham số Phân trang và Tìm kiếm
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;
$keyword = isset($_GET['query']) ? $_GET['query'] : '';

try {
    // 2. Truy vấn tìm kiếm (Search) có JOIN các bảng [cite: 666]
    $search_query = "";
    $params = [];
    if (!empty($keyword)) {
        $search_query = " AND (t.name LIKE :kw OR t.description LIKE :kw)";
        $params[':kw'] = '%' . $keyword . '%';
    }

    // 3. Lấy tổng số lượng để tính total_pages
    $count_sql = "SELECT COUNT(DISTINCT p.id) FROM products p 
                  JOIN product_translations t ON p.id = t.product_id 
                  WHERE p.is_hidden = 0" . $search_query;
    $stmt_count = $pdo->prepare($count_sql);
    $stmt_count->execute($params);
    $total_items = $stmt_count->fetchColumn();
    $total_pages = ceil($total_items / $per_page);

    // 4. Truy vấn dữ liệu sản phẩm chính
    $sql = "SELECT p.*, c.name as c_name, c.address as c_addr, c.telephone as c_tel, c.email as c_email,
                   c.owner_name, c.owner_mobile, c.owner_email,
                   c.contact_name, c.contact_mobile, c.contact_email
            FROM products p
            JOIN companies c ON p.company_id = c.id
            WHERE p.is_hidden = 0" . $search_query . " 
            LIMIT $per_page OFFSET $offset";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    $output_data = [];

    foreach ($products as $p) {
        // Lấy thông tin đa ngôn ngữ cho từng sản phẩm 
        $stmt_trans = $pdo->prepare("SELECT language_code, name, description FROM product_translations WHERE product_id = ?");
        $stmt_trans->execute([$p['id']]);
        $translations = $stmt_trans->fetchAll();
        
        $names = [];
        $descs = [];
        foreach ($translations as $tr) {
            $names[$tr['language_code']] = $tr['name'];
            $descs[$tr['language_code']] = $tr['description'];
        }

        // Tạo cấu trúc lồng nhau (Nested) theo đúng yêu cầu đề bài [cite: 672, 689, 727]
        $output_data[] = [
            "name" => $names,
            "description" => $descs,
            "gtin" => $p['gtin'],
            "brand" => $p['brand'],
            "countryOfOrigin" => $p['country_of_origin'],
            "weight" => [
                "gross" => (float)$p['gross_weight'],
                "net" => (float)$p['net_weight'],
                "unit" => $p['weight_unit']
            ],
            "company" => [
                "companyName" => $p['c_name'],
                "companyAddress" => $p['c_addr'],
                "companyTelephone" => $p['c_tel'],
                "companyEmail" => $p['c_email'],
                "owner" => [
                    "name" => $p['owner_name'],
                    "mobileNumber" => $p['owner_mobile'],
                    "email" => $p['owner_email']
                ],
                "contact" => [
                    "name" => $p['contact_name'],
                    "mobileNumber" => $p['contact_mobile'],
                    "email" => $p['contact_email']
                ]
            ]
        ];
    }

    // 5. Xuất kết quả cuối cùng kèm Pagination [cite: 727, 735]
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $base_url = $protocol . "://" . $host . $base_path . "/products.json";

    echo json_encode([
        "data" => $output_data,
        "pagination" => [
            "current_page" => $page,
            "total_pages" => (int)$total_pages,
            "per_page" => $per_page,
            "next_page_url" => ($page < $total_pages) ? $base_url . "?page=" . ($page + 1) : null,
            "prev_page_url" => ($page > 1) ? $base_url . "?page=" . ($page - 1) : null
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(["error" => $e->getMessage()]);
}