<?php
require_once 'db.php';

// 1. Truy vấn thông tin sản phẩm và công ty [cite: 772]
$sql = "SELECT p.*, c.name as company_name 
        FROM products p 
        JOIN companies c ON p.company_id = c.id 
        WHERE p.gtin = :gtin AND p.is_hidden = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute([':gtin' => $gtin]);
$product = $stmt->fetch();

// Nếu không tìm thấy sản phẩm hoặc sản phẩm bị ẩn, trả về 404 [cite: 664]
if (!$product) {
    // Gửi header 404 đến trình duyệt 
    header("HTTP/1.1 404 Not Found");
    // Hiển thị giao diện thông báo lỗi
    echo "<div style='text-align:center; padding:50px;'>
            <h1>404 Not Found</h1>
            <p>Sản phẩm không tồn tại hoặc đã bị ẩn.</p>
            <a href='/XX_module_b/login'>Quay lại trang chủ</a>
          </div>";
    exit;
}

// 2. Lấy thông tin đa ngôn ngữ [cite: 645]
$stmtTrans = $pdo->prepare("SELECT language_code, name, description FROM product_translations WHERE product_id = ?");
$stmtTrans->execute([$product['id']]);
$raw_translations = $stmtTrans->fetchAll(PDO::FETCH_ASSOC);

// Chuyển mảng phẳng thành mảng có key là language_code để dễ truy xuất
$translations = [];
foreach ($raw_translations as $tr) {
    $translations[$tr['language_code']] = $tr;
}
?>

<!DOCTYPE html>
<html lang="en" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail - <?php echo $gtin; ?></title>
    <style>
        /* Mobile-friendly CSS  */
        body { font-family: Arial, sans-serif; margin: 0; padding: 15px; background: #f9f9f9; display: flex; justify-content: center; }
        .product-card { background: #fff; width: 100%; max-width: 400px; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .lang-switch { text-align: right; margin-bottom: 15px; }
        .lang-btn { cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; background: #eee; }
        .lang-btn.active { background: #007bff; color: white; border-color: #007bff; }
        .company-name { font-weight: bold; color: #555; margin-bottom: 10px; }
        .product-image { width: 100%; height: 200px; background: #eee; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 4px; overflow: hidden; }
        .product-image img { max-width: 100%; max-height: 100%; }
        .gtin { font-size: 0.9em; color: #888; margin-bottom: 10px; }
        .description { line-height: 1.5; color: #333; margin-bottom: 20px; }
        .weight-info { font-size: 0.9em; border-top: 1px solid #eee; padding-top: 10px; }
        
        /* Ẩn hiện nội dung theo ngôn ngữ */
        [lang="fr"] .lang-en, [lang="en"] .lang-fr { display: none; }
    </style>
</head>
<body>

<div class="product-card">
    <div class="lang-switch">
        <button class="lang-btn active" onclick="switchLang('en')" id="btn-en">EN</button>
        <button class="lang-btn" onclick="switchLang('fr')" id="btn-fr">FR</button>
    </div>

    <div class="company-name"><?php echo htmlspecialchars($product['company_name']); ?></div>

    <div class="product-image">
        <?php if ($product['image_path']): ?>
            <img src="<?php echo $base_path; ?>/uploads/<?php echo $product['image_path']; ?>" alt="Product">
        <?php else: ?>
            <img src="https://via.placeholder.com/400x200?text=No+Image" alt="Placeholder">
        <?php endif; ?>
    </div>

    <h2 class="lang-en"><?php echo htmlspecialchars($translations['en']['name'] ?? ''); ?></h2>
    <h2 class="lang-fr"><?php echo htmlspecialchars($translations['fr']['name'] ?? ''); ?></h2>

    <div class="gtin">GTIN: <?php echo htmlspecialchars($product['gtin']); ?></div>

    <div class="description lang-en"><?php echo nl2br(htmlspecialchars($translations['en']['description'] ?? '')); ?></div>
    <div class="description lang-fr"><?php echo nl2br(htmlspecialchars($translations['fr']['description'] ?? '')); ?></div>

    <div class="weight-info">
        <p>Weight: <?php echo $product['gross_weight'] . $product['weight_unit']; ?></p>
        <p>Net Content Weight: <?php echo $product['net_weight'] . $product['weight_unit']; ?></p>
    </div>
</div>

<script>
    [cite_start]// Hàm chuyển đổi ngôn ngữ bằng cách đổi thuộc tính lang của thẻ HTML [cite: 773]
    function switchLang(lang) {
        document.getElementById('html-tag').setAttribute('lang', lang);
        
        // Cập nhật trạng thái nút bấm
        document.querySelectorAll('.lang-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('btn-' + lang).classList.add('active');
    }
</script>

</body>
</html>