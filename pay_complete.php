<?php
session_start();
require 'db.php';

// 사용자 세션 정보
$user['MEM_ID'] = $_SESSION['user_id'] ?? null;
$user['MEM_NAME'] = $_SESSION['username'] ?? null;

// POST로 전달된 데이터 처리
$pro_id = isset($_POST['pro_id']) ? (int)$_POST['pro_id'] : 0;
$purchase_num = isset($_POST['purchase_num']) ? (int)$_POST['purchase_num'] : 1;
$user_points = isset($_POST['user_points']) ? (int)$_POST['user_points'] : 0;

try {
    // PRODUCT 테이블에서 제품 정보 가져오기
    $stmt = $pdo->prepare("SELECT PRO_NAME, PRO_COST FROM PRODUCT WHERE PRO_ID = :pro_id");
    $stmt->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception("Product not found.");
    }

    $total_cost = $product['PRO_COST'] * $purchase_num;

    // 포인트 확인 후 결제 처리
    if ($user_points >= $total_cost) {
        // 포인트 차감
        $new_points = $user_points - $total_cost;

        // MEMBERS 테이블에서 사용자 포인트 업데이트
        $stmt = $pdo->prepare("UPDATE MEMBERS SET MEM_POINT = :new_points WHERE MEM_ID = :mem_id");
        $stmt->bindParam(':new_points', $new_points, PDO::PARAM_INT);
        $stmt->bindParam(':mem_id', $user['MEM_ID'], PDO::PARAM_STR);
        $stmt->execute();

        echo "결제가 완료되었습니다. 잔여 포인트: " . $new_points . "원";
    } else {
        echo "포인트가 부족합니다.";
    }
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
