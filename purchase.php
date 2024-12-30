<?php
session_start();
require 'db.php';

// 사용자 세션 정보
$user['MEM_ID'] = $_SESSION['user_id'] ?? null;
$user['MEM_NAME'] = $_SESSION['username'] ?? null;

// 파라미터로 제품 ID와 구매 수량을 받아 처리
$pro_id = isset($_GET['pro_id']) ? (int)$_GET['pro_id'] : 0;
$purchase_num = isset($_GET['purchase_num']) ? (int)$_GET['purchase_num'] : 1;

try {
    // PRODUCT 테이블에서 제품 정보 가져오기
    $stmt = $pdo->prepare("SELECT PRO_NAME, PRO_COST FROM PRODUCT WHERE PRO_ID = :pro_id");
    $stmt->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception("Product not found.");
    }

    // 사용자의 포인트 가져오기
    $stmt = $pdo->prepare("SELECT MEM_POINT FROM MEMBERS WHERE MEM_ID = :mem_id");
    $stmt->bindParam(':mem_id', $user['MEM_ID'], PDO::PARAM_STR);
    $stmt->execute();
    $user_points = $stmt->fetchColumn();

} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결제 확인</title>
</head>
<body>
    <h1>결제 확인</h1>
    <p>제품명: <?php echo htmlspecialchars($product['PRO_NAME']); ?></p>
    <p>제품 가격(개당): <?php echo htmlspecialchars($product['PRO_COST']); ?>원</p>
    <p>구매 수량: <?php echo $purchase_num; ?>개</p>

    <p>현재 내 포인트: <?php echo $user_points; ?>원</p>
    <p>결제 후 예상 포인트: <span id="expected_points"><?php echo $user_points - ($product['PRO_COST'] * $purchase_num); ?></span>원</p>

    <form method="POST" action="pay_complete.php">
        <input type="hidden" name="pro_id" value="<?php echo $pro_id; ?>">
        <input type="hidden" name="purchase_num" value="<?php echo $purchase_num; ?>">
        <input type="hidden" name="user_points" value="<?php echo $user_points; ?>">
        <button type="submit">결제하기</button>
    </form>
</body>
</html>