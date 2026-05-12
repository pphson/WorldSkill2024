<?php
require_once 'db.php';

$results = [];
$allValid = false;
$inputRaw = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['gtins'])) {
    $inputRaw = $_POST['gtins'];
    // Tách chuỗi theo dòng và loại bỏ khoảng trắng dư thừa
    $gtinList = array_filter(array_map('trim', explode("\n", $inputRaw)));
    
    if (!empty($gtinList)) {
        $validCount = 0;
        foreach ($gtinList as $gtin) {
            // Kiểm tra GTIN tồn tại và không bị ẩn (is_hidden = 0)
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE gtin = ? AND is_hidden = 0");
            $stmt->execute([$gtin]);
            $isValid = $stmt->fetchColumn() > 0;
            
            $results[] = [
                'gtin' => $gtin,
                'status' => $isValid ? 'Valid' : 'Invalid'
            ];
            
            if ($isValid) $validCount++;
        }
        
        // Nếu tất cả các mã nhập vào đều hợp lệ
        if ($validCount === count($gtinList)) {
            $allValid = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GTIN Bulk Verification</title>
    <style>
        body { font-family: sans-serif; padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .container { width: 100%; max-width: 500px; }
        textarea { width: 100%; height: 150px; margin-bottom: 10px; padding: 10px; }
        .result-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .status-valid { color: green; font-weight: bold; }
        .status-invalid { color: red; font-weight: bold; }
        .all-valid-box { text-align: center; margin-bottom: 20px; }
        .all-valid-box img { width: 50px; }
        .all-valid-text { color: green; font-weight: bold; font-size: 1.2em; display: block; }
    </style>
</head>
<body>

<div class="container">
    <h2>Bulk GTIN Verification</h2>
    
    <form method="POST">
        <label for="gtins">Enter GTIN numbers (one per line):</label>
        <textarea name="gtins" id="gtins" placeholder="3000123456789..."><?php echo htmlspecialchars($inputRaw); ?></textarea>
        <button type="submit" id="btn-verify">Verify All</button>
    </form>

    <hr>

    <?php if ($allValid): ?>
        <div class="all-valid-box">
            <img src="<?php echo $base_path; ?>/assets/images/green-tick.png" alt="Success">
            <span class="all-valid-text">All Valid</span>
        </div>
    <?php endif; ?>

    <div class="results-list">
        <?php foreach ($results as $res): ?>
            <div class="result-item">
                <span><?php echo htmlspecialchars($res['gtin']); ?></span>
                <span class="<?php echo $res['status'] === 'Valid' ? 'status-valid' : 'status-invalid'; ?>">
                    <?php echo $res['status']; ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>