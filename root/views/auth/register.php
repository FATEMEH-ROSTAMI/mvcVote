<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت‌نام</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="auth-container">
        <form class="auth-form" action="process_register.php" method="POST">
            <h2>ثبت‌نام</h2>
            <?php
            require_once __DIR__ . '/../controllers/AuthController.php';
            $auth = new AuthController();
            $registerData = $auth->showRegister();
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($registerData['csrf_token']); ?>">
            
            <?php if (isset($_GET['error'])): ?>
                <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">نام</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">ایمیل</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">رمز عبور</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">ثبت‌نام</button>
            <p class="link">قبلاً حساب کاربری دارید؟ <a href="login.php">وارد شوید</a></p>
        </form>
    </div>
</body>
</html>