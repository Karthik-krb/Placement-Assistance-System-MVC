<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registration</title>
    <link rel="stylesheet" href="/PAS/public/css/company-register.css?v=1">
</head>
<body>
    <div class="container">
        <form class="form-box" method="POST" action="/PAS/public/auth/company/register" enctype="multipart/form-data">
            <h2>Company Registration</h2>

            <?php if (!empty($errors ?? [])): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p class="error"><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" value="<?= htmlspecialchars($old['company_name'] ?? '') ?>" required>

            <label for="company_email">Company Email:</label>
            <input type="email" id="company_email" name="company_email" value="<?= htmlspecialchars($old['company_email'] ?? '') ?>" required>

            <label for="company_password">Password:</label>
            <input type="password" id="company_password" name="company_password" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($old['address'] ?? '') ?>" required>

            <label for="contact_no">Contact No:</label>
            <input type="text" id="contact_no" name="contact_no" value="<?= htmlspecialchars($old['contact_no'] ?? '') ?>" required>

            <label for="logo">Company Logo:</label>
            <input type="file" id="logo" name="logo" accept="image/*" required>

            <button type="submit">Register</button>

            <p class="login-link"><a href="/PAS/public/auth/company/login">Already have an account? Login</a></p>
            <p class="back-link"><a href="/PAS/public/">← Back to Home</a></p>
        </form>
    </div>
</body>
</html>
