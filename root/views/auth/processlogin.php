<?php
session_start();
require_once __DIR__ . '/../../controllers/AuthController.php';

$auth = new AuthController();


$email      = $_POST['email'] ?? '';
$password   = $_POST['password'] ?? '';
$csrf_token = $_POST['csrf_token'] ?? '';

$result = $auth->login($email, $password, $csrf_token);


if (is_array($result)) {
    if ($result['success']) {
        echo "✅ ورود موفق: خوش آمدی " . htmlspecialchars($_SESSION['user_name']);
    } else {
        echo "❌ خطا: " . $result['message'];
    }
}
