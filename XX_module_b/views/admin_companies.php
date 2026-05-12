<?php
require_once 'db.php';

// Xử lý bật/tắt trạng thái
if (isset($_GET['toggle_id'])) {
    $toggle_id = $_GET['toggle_id'];
    // Lấy trạng thái hiện tại
    $stmt = $pdo->prepare("SELECT is_deactivated FROM companies WHERE id = ?");
    $stmt->execute([$toggle_id]);
    $company = $stmt->fetch();
    
    if ($company) {
        $new_status = 1 - $company['is_deactivated'];
        $pdo->beginTransaction();
        try {
            // Cập nhật trạng thái công ty
            $stmtUpdate = $pdo->prepare("UPDATE companies SET is_deactivated = ? WHERE id = ?");
            $stmtUpdate->execute([$new_status, $toggle_id]);
            
            // Cascade ẩn sản phẩm nếu công ty bị vô hiệu hóa
            if ($new_status == 1) {
                $stmtProd = $pdo->prepare("UPDATE products SET is_hidden = 1 WHERE company_id = ?");
                $stmtProd->execute([$toggle_id]);
            }
            
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
        }
    }
    header("Location: companies");
    exit;
}

$companiesActive = $pdo->query("SELECT * FROM companies WHERE is_deactivated = 0")->fetchAll();
$companiesDeactivated = $pdo->query("SELECT * FROM companies WHERE is_deactivated = 1")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Companies</title>
    <style>
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        nav { margin-bottom: 20px; padding: 10px; background: #eee; }
        .btn-new { display: inline-block; padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 3px; margin-bottom: 15px; }
        .btn-edit { background: #ffc107; color: black; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-toggle { background: #17a2b8; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <nav>
        <a href="products">Products</a> | <b>Companies</b> | <a href="logout">Logout</a>
    </nav>

    <h2>Companies Management</h2>
    <a href="companies/new" class="btn-new">Create New Company</a>

    <h3>Active Companies</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach($companiesActive as $c): ?>
        <tr>
            <td><?php echo $c['id']; ?></td>
            <td><a href="companies/<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></a></td>
            <td><?php echo htmlspecialchars($c['email']); ?></td>
            <td class="status-active">Active</td>
            <td>
                <a href="companies/<?php echo $c['id']; ?>/edit" class="btn-edit">Edit</a>
                <a href="companies?toggle_id=<?php echo $c['id']; ?>" class="btn-toggle" onclick="return confirm('Deactivate this company? Its products will be hidden.');">Deactivate</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($companiesActive)): ?>
        <tr><td colspan="5">No active companies.</td></tr>
        <?php endif; ?>
    </table>

    <h3>Deactivated Companies</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach($companiesDeactivated as $c): ?>
        <tr>
            <td><?php echo $c['id']; ?></td>
            <td><a href="companies/<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></a></td>
            <td><?php echo htmlspecialchars($c['email']); ?></td>
            <td class="status-inactive">Deactivated</td>
            <td>
                <a href="companies/<?php echo $c['id']; ?>/edit" class="btn-edit">Edit</a>
                <a href="companies?toggle_id=<?php echo $c['id']; ?>" class="btn-toggle">Activate</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($companiesDeactivated)): ?>
        <tr><td colspan="5">No deactivated companies.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>