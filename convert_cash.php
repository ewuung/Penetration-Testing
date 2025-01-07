<?php
session_start();
require 'db.php';

// 세션 확인: 로그인된 사용자만 접근 가능
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // 사용자 보유 포인트 조회
    $stmt = $pdo->prepare("SELECT MEM_POINT FROM MEMBERS WHERE MEM_ID = ?");
    $stmt->execute([$user_id]);
    $user_point = $stmt->fetchColumn();

    if ($user_point === false) {
        die("사용자 정보를 확인할 수 없습니다.");
    }
} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}

// POST 요청 처리
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $convert_amount = $_POST['amount'] ?? 0;

    // 입력 금액 유효성 검증
    if (!is_numeric($convert_amount) || $convert_amount <= 0) {
        $error_message = "유효한 금액을 입력해주세요.";
    } elseif ($convert_amount > $user_point) {
        $error_message = "보유한 H캐시보다 큰 금액은 전환할 수 없습니다.";
    } else {
        // 입력 금액이 유효하면 payment.php로 이동
        header("Location: payment.php?amount=" . (int)$convert_amount);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>H캐시 전환</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
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
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #003399;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #002266;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .info {
            font-size: 14px;
            color: gray;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>H캐시 → 현금 전환</h2>
    <?php if ($error_message): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="amount">전환할 금액</label>
            <input type="number" id="amount" name="amount" min="1" max="<?php echo $user_point; ?>" required
                   placeholder="전환할 금액을 입력하세요.">
        </div>
        <div class="form-group">
            <button type="submit">다음</button>
        </div>
    </form>
    <p class="info">
        현재 보유 H캐시: <?php echo htmlspecialchars($user_point); ?> H캐시
    </p>
    <p style="color: gray; font-size: 14px;">
        <strong>유의사항</strong><br>
        - 전환 신청은 100 H캐시 단위로 가능합니다.<br>
        - 100 H캐시 미만 전환은 VaatzIT 고객센터를 통해 신청할 수 있습니다.
    </p>
    <a href="VaatzIT_Mall.php" class="back-button">VaatzIT Mall로 돌아가기</a>
</div>
</body>
</html>


