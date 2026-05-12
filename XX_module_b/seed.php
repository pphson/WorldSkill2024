<?php
// seed.php
require 'db.php';

// Hàm xóa ký tự ẩn (BOM) thường xuất hiện trong file CSV từ Excel
function clean($text)
{
    return trim(preg_replace('/^[\xef\xbb\xbf]+/', '', $text));
}

$companies_path = __DIR__ . '/data/companies.csv';
$products_path = __DIR__ . '/data/products.csv';

try {
    // 1. Làm sạch dữ liệu cũ để tránh trùng lặp khi chạy lại script
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE product_translations;");
    $pdo->exec("TRUNCATE TABLE products;");
    $pdo->exec("TRUNCATE TABLE companies;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    $pdo->beginTransaction();

    // 2. IMPORT COMPANIES
    $stmtComp = $pdo->prepare("INSERT INTO companies (name, address, telephone, email, owner_name, owner_mobile, owner_email, contact_name, contact_mobile, contact_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (($handle = fopen($companies_path, "r")) !== FALSE) {
        $headers = fgetcsv($handle); // Bỏ qua dòng header
        while (($data = fgetcsv($handle)) !== FALSE) {
            $stmtComp->execute($data);
        }
        fclose($handle);
        echo "✔ Đã nạp xong danh sách công ty.\n";
    }

    // Lấy ID của công ty đầu tiên để làm tham chiếu cho sản phẩm
    $firstCompanyId = $pdo->query("SELECT id FROM companies LIMIT 1")->fetchColumn();
    if (!$firstCompanyId) throw new Exception("Không tìm thấy công ty nào để liên kết sản phẩm!");

    // 3. IMPORT PRODUCTS & TRANSLATIONS
    $stmtProd = $pdo->prepare("INSERT INTO products (company_id, gtin, brand, country_of_origin, gross_weight, net_weight, weight_unit) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtTrans = $pdo->prepare("INSERT INTO product_translations (product_id, language_code, name, description) VALUES (?, ?, ?, ?)");

    $inserted_gtins = [];

    if (($handle = fopen($products_path, "r")) !== FALSE) {
        $headers = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== FALSE) {
            // Ánh xạ dữ liệu dựa trên Header bạn đã cung cấp:
            // 0: GTIN, 1: Name, 2: Description, 3: Desc FR, 4: Name FR, 5: Brand, 6: Country, 7: Gross, 8: Net, 9: Unit

            $gtin = trim($row[0]);

            if (in_array($gtin, $inserted_gtins)) {
                continue;
            }

            $inserted_gtins[] = $gtin;
            
            // Insert vào bảng products
            $stmtProd->execute([
                $firstCompanyId,
                $row[0], // GTIN [cite: 577, 624]
                $row[5], // Brand Name
                $row[6], // Country of Origin
                $row[7], // Gross Weight
                $row[8], // Net Weight
                $row[9]  // Weight Unit
            ]);

            $productId = $pdo->lastInsertId();

            // Insert bản dịch Tiếng Anh (EN)
            $stmtTrans->execute([$productId, 'en', $row[1], $row[2]]);

            // Insert bản dịch Tiếng Pháp (FR) 
            $stmtTrans->execute([$productId, 'fr', $row[4], $row[3]]);
        }
        fclose($handle);
        echo "✔ Đã nạp xong sản phẩm và thông tin đa ngôn ngữ.\n";
    }

    $pdo->commit();
    echo "\n--- HOÀN TẤT SEEDING DATA ---";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Lỗi: " . $e->getMessage();
}
