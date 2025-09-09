
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به سیستم</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500&display=swap">
</head>
<body>
    <div class="auth-container">
        <form class="auth-form" action="processlogin.php" method="POST">
            <h2>ورود به سیستم</h2>
            <?php
            require_once __DIR__ . '/../../controllers/AuthController.php';
            $auth = new AuthController();
            $loginData = $auth->showLogin();
            ?>
            <input type="text" name="csrf_token" value="<?php echo($loginData['csrf_token']); ?>">
            
            <?php if (isset($_GET['error'])): ?>
                <p class="error"><?php echo($_GET['error']); ?></p>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="email">ایمیل</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">رمز عبور</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">ورود</button>
            <p class="link">حساب کاربری ندارید؟ <a href="register.php">ثبت‌نام کنید</a></p>
        </form>
    </div>
</body>
</html>