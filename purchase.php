<?php
session_start();
require 'db.php';

if (isset($_POST['user_point'])) {
    $_SESSION['user_point'] = (int)$_POST['user_point'];
}

// 세션에서 사용자 정보 가져오기
$user['MEM_ID'] = $_SESSION['user_id'];
$user['MEM_NAME'] = $_SESSION['username'];

// 사용자 포인트 처리
if (isset($_SESSION['user_point'])) {
    $user_point = $_SESSION['user_point'];
} else {
    die("Error: User point not set.");
}

// 카테고리 및 구매 정보 가져오기
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$purchase_num = isset($_GET['purchase_num']) ? (int)$_GET['purchase_num'] : 1;

if ($category_id <= 0) {
    echo "Invalid category.";
    exit;
}

try {
    // 데이터베이스에서 제품 정보 가져오기
    $query = "SELECT PRO_ID, PRO_NAME, PRO_COST, PRO_IMG, PRO_DESC FROM PRODUCT WHERE PRO_ID = $category_id";
    $stmt = $pdo->query($query);  
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found.";
        exit;
    }

    $pro_cost = (int)$product['PRO_COST'];
    $total_price = $purchase_num * $pro_cost;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 총 결제 금액이 포인트보다 클 경우
        if ($user_point < $total_price) {
            echo "<script>alert('포인트가 부족합니다. 결제를 진행할 수 없습니다.'); window.history.back();</script>";
            exit;
        }
    
        // 포인트가 결제 금액 이상인 경우
        $remaining_points = $user_point - $total_price; // 잔여 포인트 계산    
        $final_price = $total_price;
    
        // UPDATE 쿼리 실행
        $update_query = "UPDATE MEMBERS SET MEM_POINT = $remaining_points WHERE MEM_ID = '" . $user['MEM_ID'] . "'";
        $pdo->exec($update_query);
    
        // DB에서 남은 포인트 다시 가져오기
        $refresh_query = "SELECT MEM_POINT FROM MEMBERS WHERE MEM_ID = '" . $user['MEM_ID'] . "'";
        $result = $pdo->query($refresh_query)->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $_SESSION['user_point'] = (int)$result['MEM_POINT']; // DB의 실제 값으로 세션 값 갱신
        } else {
            die("Error: Unable to fetch updated points from the database.");
        }
    
        // INSERT 쿼리 실행
        $purchase_date = date('Y-m-d H:i:s');
        $purchase_query = "INSERT INTO PURCHASE (PU_ID, PU_NUM, PU_DATE) VALUES ('" . $user['MEM_ID'] . "', $purchase_num, '$purchase_date')";
        $pdo->exec($purchase_query);
    
        // 결제 완료 메시지 및 리디렉션
        $message = "결제가 완료되었습니다. 최종 결제 금액: " . number_format($final_price) . " 원. 잔여 포인트: " . number_format($_SESSION['user_point']) . "원";
        $redirect_url = "VaatzIT_Mall.php"; 
        echo "<script>alert('$message'); window.location.href='$redirect_url';</script>";
        exit;
    }

    $expected_points = $user_point - $total_price;
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
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
    <p><strong>제품명:</strong> <?php echo $product['PRO_NAME'], ENT_QUOTES; ?></p>
    <p><strong>구매 수량:</strong> <?php echo $purchase_num; ?></p>
    <p><strong>총 가격:</strong> <?php echo number_format($total_price); ?> 원</p>
    <p><strong>현재 내 H 캐시:</strong> <?php echo number_format($user_point); ?> 원</p>
    <p><strong>결제 후 예상 H 캐시:</strong> <?php echo number_format($expected_points); ?> 원</p>

    <form method="POST">
        <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
        <input type="hidden" name="purchase_num" value="<?php echo $purchase_num; ?>">
        <button type="submit">결제하기</button>
    </form>
</div>
</body>
</html>