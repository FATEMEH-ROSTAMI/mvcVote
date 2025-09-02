<?php

//  اتصال دیتابیس
$host = 'localhost'; 
$dbname = 'voting_system';
$username = 'root'; 
$password = '';    

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die(" No connection to the database " . $e->getMessage());
}
?>