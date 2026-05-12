<?php
require_once 'db.php';

// Xử lý Xóa vĩnh viễn sản phẩm đang ẩn [cite: 636]
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND is_hidden = 1");
    $stmt->execute([$_GET['id']]);
    header("Location: products");
    exit;
}

// Lấy danh sách sản phẩm kèm tên công ty và tên (EN) [cite: 575, 645]
$sql = "SELECT p.*, c.name as company_name, t.name as product_name 
        FROM products p 
        JOIN companies c ON p.company_id = c.id 
        LEFT JOIN product_translations t ON p.id = t.product_id AND t.language_code = 'en'
        ORDER BY p.id DESC";
$products = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Products</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .hidden-row { background-color: #f9f9f9; color: #999; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 13px; }
        .btn-edit { background: #ffc107; color: black; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-new { background: #28a745; color: white; float: right; padding: 10px 15px; }
    </style>
</head>
<body>
    <h2>Products Management</h2>
    <a href="products/new" class="btn btn-new" id="btn-create-product">Add New Product</a>
    
    <table>
        <thead>
            <tr>
                <th>GTIN</th>
                <th>Product Name (EN)</th>
                <th>Company</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr class="<?php echo $p['is_hidden'] ? 'hidden-row' : ''; ?>">
                    <td><?php echo htmlspecialchars($p['gtin']); ?></td>
                    <td><?php echo htmlspecialchars($p['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($p['company_name']); ?></td>
                    <td><?php echo $p['is_hidden'] ? 'Hidden' : 'Visible'; ?></td>
                    <td>
                        <a href="products/<?php echo $p['gtin']; ?>" class="btn btn-edit">Edit</a>
                        
                        <?php if ($p['is_hidden']): ?>
                            <a href="products?action=delete&id=<?php echo $p['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Permanently delete this product?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>