<?php
session_start();

// Mock session data for testing
$user['MEM_ID'] = 'test_user';
$user['MEM_NAME'] = '테스트 사용자';
$user['MEM_POINT'] = 10000; // 예시로 10,000 포인트를 가정

// Mock product data (in a real application, this would come from a database)
$products = [
    1 => ['name' => 'Apple iMAC', 'cost' => 50000],
    2 => ['name' => 'Marshall Stanmore 3', 'cost' => 50000],
    3 => ['name' => 'Samsung Inkjet Plus', 'cost' => 50000]
];

// Get category_id and purchase_num from URL
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$purchase_num = isset($_GET['purchase_num']) ? (int)$_GET['purchase_num'] : 1;

// If category not found
if (!$category_id || !isset($products[$category_id])) {
    echo "Invalid category.";
    exit;
}

// Get product details
$product = $products[$category_id];

// Calculate total price
$total_price = $product['cost'] * $purchase_num;

// Calculate expected points after purchase
$expected_points = $user['MEM_POINT'] - $total_price; // 예시: 포인트는 결제 금액을 차감

// Handle payment action (when user clicks on "결제하기")
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate payment processing (you can replace this with actual payment logic)
    if ($user['MEM_POINT'] >= $total_price) {
        // Successful payment
        $_SESSION['success_message'] = "결제가 완료되었습니다. 잔여 포인트: " . $new_points . "원";
        header("Location: /path/to/VaatzIT_Mall.php"); // 성공 페이지 경로로 변경
        exit; // 반드시 exit 호출        // Here you would process the payment and deduct the points
    } else {
        // Insufficient points
        echo "<script>alert('포인트가 부족합니다.');</script>";
    }
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
    <p><strong>제품명:</strong> <?php echo htmlspecialchars($product['name']); ?></p>
    <p><strong>구매 수량:</strong> <?php echo $purchase_num; ?></p>
    <p><strong>총 가격:</strong> <?php echo number_format($total_price); ?> 원</p>
    <p><strong>현재 내 포인트:</strong> <?php echo number_format($user['MEM_POINT']); ?> 원</p>
    <p><strong>결제 후 예상 포인트:</strong> <?php echo number_format($expected_points); ?> 원</p>

    <form method="POST">
        <div class="input-section">
            <button type="submit">결제하기</button>
        </div>
    </form>
</div>

</body>
</html>
