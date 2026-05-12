<?php
require_once 'db.php';

$isEdit = isset($gtin); // Biến $gtin lấy từ regex trong index.php
$product = null;
$trans = ['en' => [], 'fr' => []];
$errors = [];

// 1. LẤY DỮ LIỆU CŨ (Nếu là chế độ Edit)
if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE gtin = ?");
    $stmt->execute([$gtin]);
    $product = $stmt->fetch();
    
    if ($product) {
        $stmtT = $pdo->prepare("SELECT * FROM product_translations WHERE product_id = ?");
        $stmtT->execute([$product['id']]);
        foreach ($stmtT->fetchAll() as $t) {
            $trans[$t['language_code']] = $t;
        }
    } else {
        die("Sản phẩm không tồn tại!");
    }
}

// 2. LẤY DANH SÁCH CÔNG TY (Cho dropdown)
$companies = $pdo->query("SELECT id, name FROM companies")->fetchAll();

// 3. XỬ LÝ KHI SUBMIT FORM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_gtin = $_POST['gtin'];
    $brand = $_POST['brand'];
    $company_id = $_POST['company_id'];
    $country = $_POST['country_of_origin'];
    $gross = $_POST['gross_weight'];
    $net = $_POST['net_weight'];
    $unit = $_POST['weight_unit'];
    $is_hidden = isset($_POST['is_hidden']) ? 1 : 0;
    $remove_image = isset($_POST['remove_image']) ? true : false;

    // Validation GTIN format
    if (!preg_match('/^[0-9]{13,14}$/', $new_gtin)) {
        $errors[] = "GTIN phải là số và có độ dài 13 hoặc 14 ký tự.";
    }

    // Validation GTIN Unique
    if (empty($errors)) {
        $stmtChk = $pdo->prepare("SELECT id FROM products WHERE gtin = ?");
        $stmtChk->execute([$new_gtin]);
        $existing = $stmtChk->fetch();
        if ($existing && (!$isEdit || $existing['id'] != $product['id'])) {
            $errors[] = "GTIN này đã tồn tại trong hệ thống. Vui lòng nhập GTIN khác.";
        }
    }

    // Xử lý Upload Ảnh / Xóa Ảnh
    $image_name = $product['image_path'] ?? null;
    if ($remove_image) {
        $image_name = null;
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = $new_gtin . "_" . time() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image_name);
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            if ($isEdit) {
                // UPDATE PRODUCT
                $sql = "UPDATE products SET gtin=?, company_id=?, brand=?, country_of_origin=?, gross_weight=?, net_weight=?, weight_unit=?, image_path=?, is_hidden=? WHERE id=?";
                $pdo->prepare($sql)->execute([$new_gtin, $company_id, $brand, $country, $gross, $net, $unit, $image_name, $is_hidden, $product['id']]);
                $productId = $product['id'];
            } else {
                // INSERT PRODUCT
                $sql = "INSERT INTO products (gtin, company_id, brand, country_of_origin, gross_weight, net_weight, weight_unit, image_path, is_hidden) VALUES (?,?,?,?,?,?,?,?,?)";
                $pdo->prepare($sql)->execute([$new_gtin, $company_id, $brand, $country, $gross, $net, $unit, $image_name, $is_hidden]);
                $productId = $pdo->lastInsertId();
            }

            // XỬ LÝ TRANSLATIONS (Xóa cũ thêm mới cho nhanh)
            $pdo->prepare("DELETE FROM product_translations WHERE product_id = ?")->execute([$productId]);
            $stmtT = $pdo->prepare("INSERT INTO product_translations (product_id, language_code, name, description) VALUES (?,?,?,?)");
            
            $stmtT->execute([$productId, 'en', $_POST['name_en'], $_POST['desc_en']]);
            $stmtT->execute([$productId, 'fr', $_POST['name_fr'], $_POST['desc_fr']]);

            $pdo->commit();
            header("Location: " . $base_path . "/products");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $isEdit ? 'Edit Product' : 'New Product'; ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"], textarea, select { width: 100%; padding: 8px; box-sizing: border-box; }
        .row { display: flex; gap: 20px; flex-wrap: wrap; }
        .col { flex: 1; min-width: 300px; border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #fdfdfd; }
        .error-box { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 3px; }
        nav { margin-bottom: 20px; padding: 10px; background: #eee; }
        .btn-save { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; font-size: 16px; margin-top: 20px; }
    </style>
</head>
<body>
    <nav>
        <a href="<?php echo $base_path; ?>/products">Products</a> | <a href="<?php echo $base_path; ?>/companies">Companies</a> | <a href="<?php echo $base_path; ?>/logout">Logout</a>
    </nav>

    <h2><?php echo $isEdit ? 'Edit Product: ' . htmlspecialchars($gtin) : 'Create New Product'; ?></h2>

    <?php if(!empty($errors)): ?>
        <div class="error-box">
            <?php foreach($errors as $err) echo "<div>".htmlspecialchars($err)."</div>"; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col">
                <h3>General Info</h3>
                <div class="form-group">
                    <label>GTIN *</label>
                    <input type="text" name="gtin" value="<?php echo htmlspecialchars($_POST['gtin'] ?? $product['gtin'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Company</label>
                    <select name="company_id">
                        <?php foreach($companies as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo ((isset($_POST['company_id']) && $_POST['company_id'] == $c['id']) || (!isset($_POST['company_id']) && isset($product) && $product['company_id'] == $c['id'])) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Brand Name</label>
                    <input type="text" name="brand" value="<?php echo htmlspecialchars($_POST['brand'] ?? $product['brand'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Country of Origin</label>
                    <input type="text" name="country_of_origin" value="<?php echo htmlspecialchars($_POST['country_of_origin'] ?? $product['country_of_origin'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Product Image</label>
                    <?php if(!empty($product['image_path'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="<?php echo $base_path; ?>/uploads/<?php echo $product['image_path']; ?>" width="100" style="display:block; margin-bottom:5px;">
                            <label style="font-weight: normal;"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*">
                </div>
                <div class="form-group">
                    <label style="font-weight: normal;"><input type="checkbox" name="is_hidden" <?php echo ((isset($_POST['is_hidden'])) || (!isset($_POST['is_hidden']) && isset($product) && $product['is_hidden'])) ? 'checked' : ''; ?>> Hide this product</label>
                </div>
            </div>

            <div class="col">
                <h3>English (EN)</h3>
                <div class="form-group">
                    <label>Product Name (EN)</label>
                    <input type="text" name="name_en" value="<?php echo htmlspecialchars($_POST['name_en'] ?? $trans['en']['name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Description (EN)</label>
                    <textarea name="desc_en" rows="4"><?php echo htmlspecialchars($_POST['desc_en'] ?? $trans['en']['description'] ?? ''); ?></textarea>
                </div>

                <h3>French (FR)</h3>
                <div class="form-group">
                    <label>Product Name (FR)</label>
                    <input type="text" name="name_fr" value="<?php echo htmlspecialchars($_POST['name_fr'] ?? $trans['fr']['name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Description (FR)</label>
                    <textarea name="desc_fr" rows="4"><?php echo htmlspecialchars($_POST['desc_fr'] ?? $trans['fr']['description'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top:20px;">
            <div class="col">
                <label>Gross Weight</label>
                <input type="number" step="0.01" name="gross_weight" value="<?php echo htmlspecialchars($_POST['gross_weight'] ?? $product['gross_weight'] ?? ''); ?>">
            </div>
            <div class="col">
                <label>Net Weight</label>
                <input type="number" step="0.01" name="net_weight" value="<?php echo htmlspecialchars($_POST['net_weight'] ?? $product['net_weight'] ?? ''); ?>">
            </div>
            <div class="col">
                <label>Unit</label>
                <input type="text" name="weight_unit" value="<?php echo htmlspecialchars($_POST['weight_unit'] ?? $product['weight_unit'] ?? 'kg'); ?>">
            </div>
        </div>

        <button type="submit" class="btn-save">SAVE PRODUCT</button>
        <a href="<?php echo $base_path; ?>/products" style="margin-left: 10px;">Cancel</a>
    </form>
</body>
</html>