<?php
session_start();
require 'db.php';

if (!isset($_GET['amount']) || !is_numeric($_GET['amount'])) {
    die("잘못된 요청입니다.");
}

$convert_amount = (int)$_GET['amount'];

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
} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>결제 확인</title>
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
</head>
<body>
<div class="container">
    <h2>결제 계좌 정보를 입력해주세요.</h2>
    <form method="GET" action="payment_complete.php">
        <div class="form-group">
            <label>입금 은행</label>
            <input type="text" name="bank_name" required placeholder="입금 은행을 입력해주세요.">
        </div>
        <div class="form-group">
            <label>계좌 번호</label>
            <input type="text" name="account_number" required placeholder="계좌 번호를 입력해주세요.">
        </div>
        <div class="form-group">
            <button type="submit">송금하기</button>
            <input type="hidden" name="amount" value="<?php echo $convert_amount; ?>">
        </div>
    </form>
    <a href="convert_cash.php" class="back-button">뒤로 가기</a>
</div>
</body>
</html>
<?php
session_start();
require 'db.php';

if (!isset($_GET['amount']) || !is_numeric($_GET['amount'])) {
    die("잘못된 요청입니다.");
}

$convert_amount = (int)$_GET['amount'];

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
} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>결제 확인</title>
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
</head>
<body>
<div class="container">
    <h2>결제 계좌 정보를 입력해주세요.</h2>
    <form method="GET" action="payment_complete.php">
        <div class="form-group">
            <label>입금 은행</label>
            <input type="text" name="bank_name" required placeholder="입금 은행을 입력해주세요.">
        </div>
        <div class="form-group">
            <label>계좌 번호</label>
            <input type="text" name="account_number" required placeholder="계좌 번호를 입력해주세요.">
        </div>
        <div class="form-group">
            <button type="submit">송금하기</button>
            <input type="hidden" name="amount" value="<?php echo $convert_amount; ?>">
        </div>
    </form>
    <a href="convert_cash.php" class="back-button">뒤로 가기</a>
</div>
</body>
</html>
