<?php
session_start();
require 'db.php'; // 데이터베이스 연결 파일 포함

// 세션에서 사용자 정보 가져오기
$user['MEM_ID'] = $_SESSION['user_id'];
$user['MEM_NAME'] = $_SESSION['username'];
$user['MEM_POINT'] = $_SESSION['user_points'];

// POST로 전달된 데이터 처리
$pro_id = isset($_POST['pro_id']) ? (int)$_POST['pro_id'] : 0;
$purchase_num = isset($_POST['purchase_num']) ? (int)$_POST['purchase_num'] : 1;

// GET으로 전달된 데이터 처리
$category_id = isset($_POST['pro_id']) ? (int)$_POST['pro_id'] : (isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0);

// 유효한 카테고리인지 확인
if (!$category_id) {
    echo "Invalid category.";
    exit;
}

try {
    // 데이터베이스에서 제품 정보 가져오기
    $query = "SELECT PRO_ID, PRO_NAME, PRO_COST, PRO_IMG, PRO_DESC FROM PRODUCT WHERE PRO_ID = $category_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':category_id' => $category_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found.";
        exit;
    }

    // 클라이언트에서 전달받은 값 가져오기
    $user_point = isset($_POST['user_point']) ? (int)$_POST['user_point'] : $user['MEM_POINT'];
    $pro_cost = (int)$product['PRO_COST'];

    // 총 비용 계산
    $total_price = $purchase_num * $pro_cost;

    // 결제 처리
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($user_point >= $total_price) {
            $remaining_points = $user_point - $total_price;

            // 사용자 포인트 업데이트
            $update_query = "UPDATE MEMBERS SET MEM_POINT = $remaining_points WHERE MEM_ID = {$user['MEM_ID']}";
            $pdo->exec($update_query);            
            $stmt->execute([
                ':remaining_points' => $remaining_points,
                ':user_id' => $user['MEM_ID'],
            ]);

            // 세션 값 갱신
            $_SESSION['user_points'] = $remaining_points;

            // 구매 기록 저장 (선택 사항)
            $purchase_date = date('Y-m-d H:i:s');
            $purchase_query = "INSERT INTO PURCHASE (PU_ID, PU_NUM, PU_DATE) VALUES ({$user['MEM_ID']}, $purchase_num, '$purchase_date')";
            $pdo->exec($purchase_query);            
            $stmt->execute([
                ':user_id' => $user['MEM_ID'],
                ':purchase_num' => $purchase_num,
                ':purchase_date' => $purchase_date,
            ]);

            $message = "결제가 완료되었습니다. 잔여 포인트: " . number_format($remaining_points) . "원";
        } else {
            $message = "포인트가 부족합니다.";
        }

        echo "<script>alert('" . htmlspecialchars($message, ENT_QUOTES) . "'); window.location.href='" . $_SERVER['PHP_SELF'] . "?category_id=$category_id&purchase_num=$purchase_num';</script>";
        exit;
    }

    // 결제 후 예상 포인트 계산
    $expected_points = $user_point - $total_price;
} catch (PDOException $e) {
    echo "Database error: " . htmlspecialchars($e->getMessage());
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
    <p><strong>제품명:</strong> <?php echo htmlspecialchars($product['PRO_NAME'], ENT_QUOTES); ?></p>
    <p><strong>구매 수량:</strong> <?php echo $purchase_num; ?></p>
    <p><strong>총 가격:</strong> <?php echo number_format($total_price); ?> 원</p>
    <p><strong>현재 내 포인트:</strong> <?php echo number_format($user_point); ?> 원</p>
    <p><strong>결제 후 예상 포인트:</strong> <?php echo number_format($expected_points); ?> 원</p>

    <form method="POST">
        <input type="hidden" name="pro_id" value="<?php echo $category_id; ?>">
        <input type="hidden" name="purchase_num" value="<?php echo $purchase_num; ?>">
        <input type="hidden" name="user_point" value="<?php echo $user_point; ?>">

        <div class="input-section">
            <button type="submit">결제하기</button>
        </div>
    </form>
</div>
</body>
</html>
