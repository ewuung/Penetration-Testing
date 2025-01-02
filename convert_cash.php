<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user['MEM_ID'] = $_SESSION['user_id'];
$user['MEM_NAME'] = $_SESSION['username'];

try {
    $stmt = $pdo->prepare("SELECT MEM_POINT FROM MEMBERS WHERE MEM_ID = ?");
    $stmt->execute([$user['MEM_ID']]);
    $user['MEM_POINT'] = $stmt->fetchColumn();
} catch (PDOException $e) {
    die("Error fetching user points: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $convert_amount = (int)$_POST['convert_amount'];

    if ($convert_amount < 100 || $convert_amount % 100 !== 0) {
        $error = "H캐쉬 전환은 100 단위로만 가능합니다.";
    } elseif ($convert_amount > $user['MEM_POINT']) {
        $error = "보유한 H캐쉬보다 큰 금액은 전환할 수 없습니다.";
    } else {
        header("Location: payment.php?amount=$convert_amount");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H캐쉬→현금 전환</title>
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
    </style>
    <script>
        function updateRemainingPoint() {
            const currentPoint = parseInt(document.getElementById('current_point').value);
            const convertAmount = parseInt(document.getElementById('convert_amount').value) || 0;
            const remainingPoint = currentPoint - convertAmount;
            document.getElementById('remaining_point').value = remainingPoint >= 0 ? remainingPoint : '0';
        }
    </script>
</head>
<body>
<div class="container">
    <h2>H캐쉬→현금 전환 신청</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>보유 H캐쉬</label>
            <input type="text" id="current_point" value="<?php echo $user['MEM_POINT']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>전환할 H캐쉬</label>
            <input type="number" id="convert_amount" name="convert_amount" oninput="updateRemainingPoint()" required>
        </div>
        <div class="form-group">
            <label>전환 후 보유 H캐쉬</label>
            <input type="text" id="remaining_point" value="<?php echo $user['MEM_POINT']; ?>" readonly>
        </div>
        <div class="form-group">
            <button type="submit">신청하기</button>
        </div>
    </form>
    <p style="color: gray; font-size: 14px;">
        <strong>유의사항</strong><br>
        - 전환 신청은 100 H캐쉬 단위로 가능합니다.<br>
        - 100 H캐쉬 미만 전환은 VaatzIT 고객센터를 통해 신청할 수 있습니다.
    </p>
    <a href="VaatzIT_Mall.php" class="back-button">VaatzIT Mall로 돌아가기</a>
</div>
</body>
</html>
