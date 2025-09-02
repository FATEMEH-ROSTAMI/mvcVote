<?php
require_once __DIR__ . '/../config/database.php'; // اتصال به دیتابیس

class User {
    private $pdo;

    public function __construct() {
        global $pdo; // استفاده از PDO که تو database.php تعریف شده
        $this->pdo = $pdo;
    }

    // ثبت‌نام کاربر جدید
    public function register($name, $email, $password) {
        // هش کردن رمز عبور برای امنیت
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // آماده کردن کوئری برای ثبت کاربر
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password_hash, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
        try {
            $stmt->execute([$name, $email, $password_hash]);
            return true; // ثبت‌نام موفق
        } catch (PDOException $e) {
            // اگه ایمیل تکراری باشه یا خطای دیگه‌ای پیش بیاد
            return false;
        }
    }

    // ورود کاربر
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // چک کردن وجود کاربر و صحت رمز عبور
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user; // برگرداندن اطلاعات کاربر
        }
        return false; // ورود ناموفق
    }

    // چک کردن نقش کاربر (مثل admin یا user)
    public function isAdmin($user_id) {
        $stmt = $this->pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user && $user['role'] === 'admin';
    }

    // گرفتن اطلاعات کاربر بر اساس ID
    public function getUserById($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
