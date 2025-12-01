<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Login</title>
    <link rel="stylesheet" href="/PAS/public/css/styles.css?v=8">
</head>
<body class="company-login-page">
    <div class="login-container">
        <div class="avatar company-icon">
            <img src="/PAS/public/assets/cmpy.jpeg" alt="Company Icon">
        </div>
        <h2>Company Login</h2>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-messages">
                <p class="success"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (!empty($errors ?? [])): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="/PAS/public/auth/company/login">       
            <div class="input-container">
                <input type="email" name="email" placeholder="Company Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            </div>
            <div class="input-container">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="login-btn">LOGIN</button>
            <p class="forgot-password"><a href="#">Forgot Password?</a></p>
            <p class="create-account"><a href="/PAS/public/auth/company/register">Don't have an account? Create one</a></p>
        </form>
        
        <p class="back-link"><a href="/PAS/public/">← Back to Home</a></p>
    </div>
</body>
</html>