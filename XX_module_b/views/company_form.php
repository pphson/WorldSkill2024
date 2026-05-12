<?php
require_once 'db.php';

$isEdit = isset($company_id);
$company = null;
$errors = [];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([$company_id]);
    $company = $stmt->fetch();
    if (!$company) {
        die("Company not found!");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $owner_name = trim($_POST['owner_name'] ?? '');
    $owner_mobile = trim($_POST['owner_mobile'] ?? '');
    $owner_email = trim($_POST['owner_email'] ?? '');
    $contact_name = trim($_POST['contact_name'] ?? '');
    $contact_mobile = trim($_POST['contact_mobile'] ?? '');
    $contact_email = trim($_POST['contact_email'] ?? '');

    if (empty($name)) {
        $errors[] = "Company name is required.";
    }

    if (empty($errors)) {
        try {
            if ($isEdit) {
                $sql = "UPDATE companies SET name=?, address=?, telephone=?, email=?, owner_name=?, owner_mobile=?, owner_email=?, contact_name=?, contact_mobile=?, contact_email=? WHERE id=?";
                $pdo->prepare($sql)->execute([$name, $address, $telephone, $email, $owner_name, $owner_mobile, $owner_email, $contact_name, $contact_mobile, $contact_email, $company_id]);
            } else {
                $sql = "INSERT INTO companies (name, address, telephone, email, owner_name, owner_mobile, owner_email, contact_name, contact_mobile, contact_email) VALUES (?,?,?,?,?,?,?,?,?,?)";
                $pdo->prepare($sql)->execute([$name, $address, $telephone, $email, $owner_name, $owner_mobile, $owner_email, $contact_name, $contact_mobile, $contact_email]);
            }
            header("Location: " . $base_path . "/companies");
            exit;
        } catch (Exception $e) {
            $errors[] = "System error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $isEdit ? 'Edit Company' : 'New Company'; ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="tel"] { width: 100%; padding: 8px; box-sizing: border-box; }
        .row { display: flex; gap: 20px; flex-wrap: wrap; }
        .col { flex: 1; min-width: 300px; border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #fdfdfd; }
        .error-box { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; }
        .btn-save { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; font-size: 16px; margin-top: 20px; }
        nav { margin-bottom: 20px; padding: 10px; background: #eee; }
    </style>
</head>
<body>
    <nav>
        <a href="<?php echo $base_path; ?>/products">Products</a> | <a href="<?php echo $base_path; ?>/companies">Companies</a> | <a href="<?php echo $base_path; ?>/logout">Logout</a>
    </nav>

    <h2><?php echo $isEdit ? 'Edit Company: ' . htmlspecialchars($company['name']) : 'Create New Company'; ?></h2>

    <?php if(!empty($errors)): ?>
        <div class="error-box">
            <?php foreach($errors as $err) echo "<div>$err</div>"; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col">
                <h3>Company Info</h3>
                <div class="form-group">
                    <label>Company Name *</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? $company['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($_POST['address'] ?? $company['address'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Telephone</label>
                    <input type="text" name="telephone" value="<?php echo htmlspecialchars($_POST['telephone'] ?? $company['telephone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? $company['email'] ?? ''); ?>">
                </div>
            </div>

            <div class="col">
                <h3>Owner Info</h3>
                <div class="form-group">
                    <label>Owner Name</label>
                    <input type="text" name="owner_name" value="<?php echo htmlspecialchars($_POST['owner_name'] ?? $company['owner_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Owner Mobile</label>
                    <input type="text" name="owner_mobile" value="<?php echo htmlspecialchars($_POST['owner_mobile'] ?? $company['owner_mobile'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Owner Email</label>
                    <input type="email" name="owner_email" value="<?php echo htmlspecialchars($_POST['owner_email'] ?? $company['owner_email'] ?? ''); ?>">
                </div>
            </div>

            <div class="col">
                <h3>Contact Info</h3>
                <div class="form-group">
                    <label>Contact Name</label>
                    <input type="text" name="contact_name" value="<?php echo htmlspecialchars($_POST['contact_name'] ?? $company['contact_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Contact Mobile</label>
                    <input type="text" name="contact_mobile" value="<?php echo htmlspecialchars($_POST['contact_mobile'] ?? $company['contact_mobile'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Contact Email</label>
                    <input type="email" name="contact_email" value="<?php echo htmlspecialchars($_POST['contact_email'] ?? $company['contact_email'] ?? ''); ?>">
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save">Save Company</button>
        <a href="<?php echo $base_path; ?>/companies" style="margin-left: 10px;">Cancel</a>
    </form>
</body>
</html>
