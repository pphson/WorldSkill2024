<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - WorldSkills</title>
    <style>
        /* CSS dùng chung cho Admin */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background-color: #f8f9fa; }
        nav { background-color: #343a40; padding: 1rem; color: white; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: rgba(255,255,255,.5); text-decoration: none; margin-right: 15px; transition: 0.3s; }
        nav a:hover, nav a.active { color: white; }
        .container { padding: 20px; max-width: 1200px; margin: 0 auto; background: white; margin-top: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; }
        
        /* Style cho Table */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { background-color: #f1f1f1; }
        tr:hover { background-color: #f8f9fa; }

        /* Style cho Button */
        .btn { padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block; cursor: pointer; border: none; }
        .btn-primary { background: #007bff; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; }
        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<nav>
    <div>
        <strong>WS ADMIN</strong>
        <a href="<?php echo $base_path; ?>/products" class="<?php echo ($route === '/products' ? 'active' : ''); ?>">Products</a>
        <a href="<?php echo $base_path; ?>/companies" class="<?php echo ($route === '/companies' ? 'active' : ''); ?>">Companies</a>
    </div>
    <div>
        <span>Chào Admin!</span>
        <a href="<?php echo $base_path; ?>/logout" style="margin-left: 20px; color: #ff6b6b;">Logout</a>
    </div>
</nav>

<div class="container">