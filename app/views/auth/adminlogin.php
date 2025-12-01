<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/PAS/public/css/styles.css?v=10">
</head>
<body class="admin-login-page">
    <div class="login-container">
        <div class="avatar admin-icon">
        </div>
        <h2>Admin Login</h2>
        
        <?php if (!empty($errors ?? [])): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="/PAS/public/auth/admin/login">
            <div class="input-container">
                <input type="email" name="email" placeholder="Admin Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            </div>
            <div class="input-container">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="login-btn">LOGIN</button>
        </form>
        
        <p class="back-link"><a href="/PAS/public/">← Back to Home</a></p>
    </div>
</body>
</html>