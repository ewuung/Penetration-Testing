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
        .form-group input, .form-group select {
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
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>결제 계좌 정보를 입력해주세요.</h2>
    <form method="POST" action="payment_complete.php?amount=<?php echo $convert_amount; ?>">
        <div class="form-group">
            <label>입금 은행</label>
            <select name="bank_name" required>
                <option value="KB국민">KB국민</option>
                <option value="기업">기업</option>
                <option value="농협">농협</option>
                <option value="산업">산업</option>
                <option value="수협">수협</option>
                <option value="신한">신한</option>
                <option value="우리">우리</option>
                <option value="우체국">우체국</option>
                <option value="하나">하나</option>
                <option value="한국씨티">한국씨티</option>
                <option value="SC제일">SC제일</option>
                <option value="카카오뱅크">카카오뱅크</option>
                <option value="케이뱅크">케이뱅크</option>
                <option value="토스뱅크">토스뱅크</option>
                <option value="경남">경남</option>
                <option value="광주">광주</option>
                <option value="아이엠뱅크(구 대구)">아이엠뱅크(구 대구)</option>
                <option value="부산">부산</option>
                <option value="전북">전북</option>
                <option value="제주">제주</option>
                <option value="저축">저축</option>
                <option value="산림조합">산림조합</option>
                <option value="새마을">새마을</option>
                <option value="신협">신협</option>
                <option value="도이치">도이치</option>
                <option value="뱅크오브아메리카">뱅크오브아메리카</option>
                <option value="중국건설">중국건설</option>
                <option value="중국공상">중국공상</option>
                <option value="중국">중국</option>
                <option value="HSBC">HSBC</option>
                <option value="BNP파리바">BNP파리바</option>
                <option value="JP모간체이스">JP모간체이스</option>
            </select>
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
    <p style="color: gray; font-size: 14px;">
        전환 금액: <?php echo $convert_amount; ?> H캐쉬<br>
        전환 후 보유 H캐쉬: <?php echo $user_point - $convert_amount; ?>
    </p>
</div>
</body>
</html>
