<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

$sql = "SELECT p.*, c.name as c_name, c.address as c_addr, c.telephone as c_tel, c.email as c_email,
               c.owner_name, c.owner_mobile, c.owner_email,
               c.contact_name, c.contact_mobile, c.contact_email
        FROM products p 
        JOIN companies c ON p.company_id = c.id 
        WHERE p.gtin = ? AND p.is_hidden = 0";

$stmt = $pdo->prepare($sql);
$stmt->execute([$gtin]);
$p = $stmt->fetch();

if (!$p) {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(["error" => "Product not found or hidden"]);
    exit;
}

// Lấy bản dịch
$stmtT = $pdo->prepare("SELECT language_code, name, description FROM product_translations WHERE product_id = ?");
$stmtT->execute([$p['id']]);
$trans = $stmtT->fetchAll(PDO::FETCH_ASSOC);

$names = []; $descs = [];
foreach($trans as $t) {
    $names[$t['language_code']] = $t['name'];
    $descs[$t['language_code']] = $t['description'];
}

echo json_encode([
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
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);