<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Module B</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 100px; background-color: #f4f4f9; }
        .login-card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 300px; }
        .error { color: #d9534f; margin-bottom: 15px; font-size: 14px; }
        input[type="password"] { width: 100%; padding: 10px; margin-bottom: 15px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #0275d8; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #025aa5; }
    </style>
</head>
<body>

<div class="login-card">
    <h2 style="text-align: center; margin-top: 0;">Admin Access</h2>
    
    <?php if(!empty($error)): ?>
        <div class="error" id="login-error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo $base_path; ?>/login" id="form-login">
        <label for="passphrase">Passphrase:</label>
        <input type="password" id="passphrase" name="passphrase" required autofocus>
        <button type="submit" id="btn-login">Login</button>
    </form>
</div>

</body>
</html>