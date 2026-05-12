<?php
session_start();

// Thiết lập Base Path
$base_path = '/XX_module_b'; // Thay XX bằng số báo danh
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

$route = str_replace($base_path, '', $path);
if ($route === '' || $route === '/') {
    $route = '/login';
}

function requireAdmin()
{
    if (empty($_SESSION['is_admin'])) {
        header('HTTP/1.1 401 Unauthorized');
        echo "401 Unauthorized - Bạn chưa đăng nhập.";
        exit;
    }
}

switch (true) {
    // --- ADMIN ROUTES ---
    case ($route === '/login'):
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['passphrase']) && $_POST['passphrase'] === 'admin') {
                $_SESSION['is_admin'] = true;
                header("Location: $base_path/products");
                exit;
            } else {
                $error = "Passphrase không chính xác!";
            }
        }
        require 'views/login.php';
        break;

    case ($route === '/logout'):
        session_destroy();
        header("Location: $base_path/login");
        exit;

    case ($route === '/products'):
        requireAdmin();
        require 'views/admin_products.php';
        break;

    case ($route === '/products/new'):
        requireAdmin();
        require 'views/product_form.php';
        break;

    // Quản lý chi tiết sản phẩm qua GTIN (Admin Edit)
    case (preg_match('/^\/products\/([0-9]{13,14})$/', $route, $matches)):
        requireAdmin();
        $gtin = $matches[1];
        require 'views/product_form.php';
        break;


    // --- API ROUTES ---
    case ($route === '/products.json'):
        require 'api/products.php';
        break;

    // --- PUBLIC ROUTES ---
    // Public Product Page
    case (preg_match('/^\/01\/([0-9]{13,14})$/', $route, $matches)):
        $gtin = $matches[1];
        require 'views/public_product.php';
        break;

    case ($route === '/bulk-verify'):
        require 'views/bulk_verify.php';
        break;

    // Thêm vào switch case
    case (preg_match('/^\/products\/([0-9]{13,14})\.json$/', $route, $matches)):
        $gtin = $matches[1];
        require 'api/product_detail.php';
        break;

    case ($route === '/companies'):
        requireAdmin();
        require 'views/admin_companies.php';
        break;

    case ($route === '/companies/new'):
        requireAdmin();
        require 'views/company_form.php';
        break;

    case (preg_match('/^\/companies\/([0-9]+)\/edit$/', $route, $matches)):
        requireAdmin();
        $company_id = $matches[1];
        require 'views/company_form.php';
        break;

    case (preg_match('/^\/companies\/([0-9]+)$/', $route, $matches)):
        requireAdmin();
        $company_id = $matches[1];
        require 'views/company_detail.php';
        break;

    // --- DEFAULT 404 ---
    default:
        header('HTTP/1.1 404 Not Found');
        echo "404 - Trang không tồn tại.";
        break;
}
