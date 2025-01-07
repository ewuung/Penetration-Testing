<?php
session_start();
require 'db.php';

if (!isset($_GET['amount']) || !is_numeric($_GET['amount']) ||
    !isset($_GET['bank_name']) || !isset($_GET['account_number'])) {
    die("잘못된 요청입니다.");
}

$convert_amount = (int)$_GET['amount'];
$bank_name = htmlspecialchars($_GET['bank_name']);
$account_number = htmlspecialchars($_GET['account_number']);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT MEM_POINT FROM MEMBERS WHERE MEM_ID = ?");
    $stmt->execute([$user_id]);
    $user_point = $stmt->fetchColumn();

    if ($convert_amount > $user_point) {
        die("보유한 H캐쉬보다 큰 금액은 전환할 수 없습니다.");
    }

    $stmt = $pdo->prepare("UPDATE MEMBERS SET MEM_POINT = MEM_POINT - ? WHERE MEM_ID = ?");
    $stmt->execute([$convert_amount, $user_id]);

    $stmt = $pdo->prepare("SELECT MEM_POINT FROM MEMBERS WHERE MEM_ID = ?");
    $stmt->execute([$user_id]);
    $remaining_points = $stmt->fetchColumn();

} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>송금 완료</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            color: #003399;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group p {
            font-size: 16px;
            color: #333;
            margin: 10px 0;
        }
        .back-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #ddd;
            color: black;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: #ccc;
        }
        .success {
            color: green;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>송금 완료</h2>
    <p class="success">송금이 성공적으로 완료되었습니다!</p>

    <!-- 입금 정보 섹션 -->
    <div class="form-group" style="background-color: #f1f8ff; padding: 15px; border-radius: 6px;">
        <h3 style="color: #003399;">입금 정보</h3>
        <p><strong>입금 은행:</strong> <?php echo $bank_name; ?></p>
        <p><strong>계좌 번호:</strong> <?php echo $account_number; ?></p>
    </div>

    <!-- 금액 정보 섹션 -->
    <div class="form-group" style="background-color: #f1f8ff; padding: 15px; border-radius: 6px;">
        <h3 style="color: #003399;">금액 정보</h3>
        <p><strong>송금 금액:</strong> <?php echo $convert_amount; ?> H캐시</p>
        <p><strong>남은 H캐시:</strong> <?php echo $remaining_points; ?> H캐시</p>
    </div>

    <!-- 뒤로 가기 버튼 -->
    <a href="VaatzIT_Mall.php" class="back-button">VaatzIT Mall로 돌아가기</a>
</div>
</body>
</html>
