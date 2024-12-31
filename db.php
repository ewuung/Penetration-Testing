<?php
$host = 'localhost';  // 데이터베이스 호스트
$dbname = 'jjazztest';  // 데이터베이스 이름
$username = 'root';  // 사용자 이름
$password = 'grbhack';  // 사용자 비밀번호

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

