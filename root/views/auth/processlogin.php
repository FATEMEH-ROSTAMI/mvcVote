<?php
require_once __DIR__ . '/../../controllers/AuthController.php';

$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    $result = $auth->login($email, $password, $csrf_token);

    if ($result['success']) {
        header('Location: ' . $result['redirect']);
        exit;
    } else {
        header('Location: login.php?error=' . urlencode($result['message']));
        exit;
    }
}
?>