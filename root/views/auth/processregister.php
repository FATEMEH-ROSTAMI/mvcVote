<?php
session_start();
require_once __DIR__ . '/../../controllers/AuthController.php';

$auth = new AuthController();

$name       = $_POST['name'] ?? '';
$email      = $_POST['email'] ?? '';
$password   = $_POST['password'] ?? '';
$csrf_token = $_POST['csrf_token'] ?? '';

$result = $auth->register($name, $email, $password, $csrf_token);

if ($result['success']) {
    header("Location: login.php");
    exit;
} else {
    header("Location: register.php?error=" . urlencode($result['message']));
    exit;
}
