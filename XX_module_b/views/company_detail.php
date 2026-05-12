<?php
require_once 'db.php';

$stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->execute([$company_id]);
$company = $stmt->fetch();

if (!$company) {
    die("Company not found!");
}

// Fetch associated products
$stmtP = $pdo->prepare("
    SELECT p.*, t.name as product_name 
    FROM products p 
    LEFT JOIN product_translations t ON p.id = t.product_id AND t.language_code = 'en'
    WHERE p.company_id = ?
");
$stmtP->execute([$company_id]);
$products = $stmtP->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Company Details - <?php echo htmlspecialchars($company['name']); ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .details-box { border: 1px solid #ddd; padding: 20px; border-radius: 5px; margin-bottom: 20px; background: #fdfdfd; }
        .row { display: flex; gap: 40px; }
        .col { flex: 1; }
        h2 { margin-top: 0; }
        h3 { border-bottom: 1px solid #eee; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .hidden-row { background-color: #f9f9f9; color: #999; }
        nav { margin-bottom: 20px; padding: 10px; background: #eee; }
    </style>
</head>
<body>
    <nav>
        <a href="<?php echo $base_path; ?>/products">Products</a> | <a href="<?php echo $base_path; ?>/companies">Companies</a> | <a href="<?php echo $base_path; ?>/logout">Logout</a>
    </nav>

    <div class="details-box">
        <h2>Company: <?php echo htmlspecialchars($company['name']); ?> <?php echo $company['is_deactivated'] ? '<span style="color:red">(Deactivated)</span>' : ''; ?></h2>
        
        <div class="row">
            <div class="col">
                <h3>Contact Info</h3>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($company['address']); ?></p>
                <p><strong>Telephone:</strong> <?php echo htmlspecialchars($company['telephone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($company['email']); ?></p>
            </div>
            
            <div class="col">
                <h3>Owner</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($company['owner_name']); ?></p>
                <p><strong>Mobile:</strong> <?php echo htmlspecialchars($company['owner_mobile']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($company['owner_email']); ?></p>
            </div>
            
            <div class="col">
                <h3>Primary Contact</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($company['contact_name']); ?></p>
                <p><strong>Mobile:</strong> <?php echo htmlspecialchars($company['contact_mobile']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($company['contact_email']); ?></p>
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="<?php echo $base_path; ?>/companies/<?php echo $company['id']; ?>/edit" style="background: #ffc107; color: black; padding: 5px 15px; text-decoration: none; border-radius: 3px;">Edit Company</a>
        </div>
    </div>

    <h3>Associated Products</h3>
    <table>
        <thead>
            <tr>
                <th>GTIN</th>
                <th>Product Name (EN)</th>
                <th>Brand</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr class="<?php echo $p['is_hidden'] ? 'hidden-row' : ''; ?>">
                    <td><?php echo htmlspecialchars($p['gtin']); ?></td>
                    <td><?php echo htmlspecialchars($p['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($p['brand']); ?></td>
                    <td><?php echo $p['is_hidden'] ? 'Hidden' : 'Visible'; ?></td>
                    <td>
                        <a href="<?php echo $base_path; ?>/products/<?php echo $p['gtin']; ?>" style="color: #007bff; text-decoration: none;">View/Edit Product</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
                <tr><td colspan="5">No products associated with this company.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
