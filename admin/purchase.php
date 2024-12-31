<?php
require '../db.php';

// VaatzIT Mall ID 확인
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$id = (int)$_GET['id'];

// 사용자 구매 기록 조회
$sql = "SELECT 
            m.MEM_ID, m.MEM_POINT, 
            p.PRO_ID, p.PRO_NAME, p.PRO_COST, 
            pu.PURCHASE_DATE, pu.PURCHASE_COST, pu.NEW_MEM_POINT
        FROM PURCHASE pu
        JOIN MEMBERS m ON pu.MEM_ID = m.MEM_ID
        JOIN PRODUCT p ON pu.PRO_ID = p.PRO_ID";

$stmt = $pdo->query($sql);
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>구매 관리 페이지</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        .form-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-container label {
            display: block;
            margin: 10px 0 5px;
            font-size: 14px;
            color: #555;
        }
        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #0066ff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056d2;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>사용자 구매 관리</h1>

    <table border="1">
        <thead>
            <tr>
                <th>사용자 ID</th>
                <th>사용자 포인트</th>
                <th>제품 ID</th>
                <th>제품명</th>
                <th>제품 가격</th>
                <th>구매 일자</th>
                <th>구매 금액</th>
                <th>구매 후 포인트</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchases as $purchase): ?>
            <tr>
                <td><?= htmlspecialchars($purchase['MEM_ID']) ?></td>
                <td><?= htmlspecialchars($purchase['MEM_POINT']) ?></td>
                <td><?= htmlspecialchars($purchase['PRO_ID']) ?></td>
                <td><?= htmlspecialchars($purchase['PRO_NAME']) ?></td>
                <td><?= htmlspecialchars($purchase['PRO_COST']) ?></td>
                <td><?= htmlspecialchars($purchase['PURCHASE_DATE']) ?></td>
                <td><?= htmlspecialchars($purchase['PURCHASE_COST']) ?></td>
                <td><?= htmlspecialchars($purchase['NEW_MEM_POINT']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h2>사용자 포인트 업데이트</h2>
    <form action="update_point.php" method="POST">
        <label for="mem_id">사용자 ID:</label>
        <input type="text" id="mem_id" name="mem_id" required><br><br>

        <label for="new_point">새로운 포인트:</label>
        <input type="number" id="new_point" name="new_point" required><br><br>

        <input type="submit" value="포인트 업데이트">
    </form>
</body>
</html>
