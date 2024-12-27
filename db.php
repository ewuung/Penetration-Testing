<?php
$host = 'localhost';
$dbname = 'vaatzit';
$username = 'root'; // XAMPP 기본 사용자
$password = ''; // XAMPP 기본 비밀번호 없음

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>
