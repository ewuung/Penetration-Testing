<?php
session_start();
require 'db.php'; // 데이터베이스 연결 파일 포함

// 사용자 세션 체크
if (!isset($_SESSION['user_id'])) {
    echo "로그인이 필요합니다.";
    exit;
}

$user['MEM_ID'] = $_SESSION['user_id'];
$user['MEM_NAME'] = $_SESSION['username'];

// GET으로 전달된 데이터 처리
$pro_id = isset($_GET['pro_id']) ? (int)$_GET['pro_id'] : 0;
$purchase_num = isset($_GET['purchase_num']) ? (int)$_GET['purchase_num'] : 1;
$user_points = isset($_GET['user_points']) ? (int)$_GET['user_points'] : 0;

// 유효한 카테고리인지 확인
if (!$pro_id) {
    echo "잘못된 제품입니다.";
    exit;
}

try {
    // 데이터베이스에서 제품 정보 가져오기
    $stmt = $pdo->prepare("SELECT PRO_ID, PRO_NAME, PRO_COST, PRO_IMG, PRO_DESC FROM PRODUCT WHERE PRO_ID = :pro_id");
    $stmt->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "제품을 찾을 수 없습니다.";
        exit;
    }

    // 사용자 포인트 가져오기
    $stmt = $pdo->prepare("SELECT MEM_POINT FROM MEMBERS WHERE MEM_ID = :mem_id");
    $stmt->bindParam(':mem_id', $user['MEM_ID'], PDO::PARAM_STR);
    $stmt->execute();
    $user_points = (int)$stmt->fetchColumn();

    $user['MEM_POINT'] = $user_points;

    // 총 가격 계산
    $total_price = (int)$product['PRO_COST'] * $purchase_num;

    // 결제 후 예상 포인트 계산
    $expected_points = $user_points - $total_price;
} catch (PDOException $e) {
    echo "데이터베이스 오류: " . htmlspecialchars($e->getMessage());
    exit;
}

// 결제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user_points >= $total_price) {
        $new_points = $user_points - $total_price;

        // 사용자 포인트 업데이트
        $stmt = $pdo->prepare("UPDATE MEMBERS SET MEM_POINT = :new_points WHERE MEM_ID = :mem_id");
        $stmt->bindParam(':new_points', $new_points, PDO::PARAM_INT);
        $stmt->bindParam(':mem_id', $user['MEM_ID'], PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['user_points'] = $new_points; // 세션 업데이트

        $message = "결제가 완료되었습니다. 잔여 포인트: " . number_format($new_points) . "원";
    } else {
        $message = "포인트가 부족합니다.";
    }

    echo "<script>alert('$message'); window.location.href='purchase.php?pro_id=$pro_id&purchase_num=$purchase_num&user_points=$new_points';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결제 페이지</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            color: #003399;
            margin-bottom: 20px;
        }
        .container p {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .container .input-section {
            margin-top: 20px;
        }
        .container .input-section input {
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .container button {
            padding: 10px;
            background-color: #003399;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }
        .container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>구매 내역</h2>
    <p><strong>제품명:</strong> <?php echo $product['PRO_NAME']; ?></p>
    <p><strong>구매 수량:</strong> <?php echo $purchase_num; ?></p>
    <p><strong>총 가격:</strong> <?php echo number_format($total_price); ?> 원</p>
    <p><strong>현재 내 포인트:</strong> <?php echo number_format($user['MEM_POINT']); ?> 원</p>
    <p><strong>결제 후 예상 포인트:</strong> <?php echo number_format($expected_points); ?> 원</p>

    <form method="POST">
        <input type="hidden" name="pro_id" value="<?php echo $pro_id; ?>">
        <input type="hidden" name="purchase_num" value="<?php echo $purchase_num; ?>">
        <input type="hidden" name="user_points" value="<?php echo $user['MEM_POINT']; ?>">

        <div class="input-section">
            <button type="submit">결제하기</button>
        </div>
    </form>
</div>
</body>
</html>
