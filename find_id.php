<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $business_number = $_POST['business_number'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE business_number = :business_number");
    $stmt->execute(['business_number' => $business_number]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $found_username = $user['username'];
    } else {
        $error = "등록된 사업자 등록번호가 없습니다!";
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>현대오토에버 VaatzIT 아이디 찾기</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: white;
            color:  #003399;
            padding-left: 11%;
            text-align: left;
            border-bottom: 4px solid #003399;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            font-size: 20px;
        }
        .title_main {
            font-weight: bold;
            color: #003399;
            font-size: 36px;
            font-family: 'Arial', sans-serif;
        }
        .title_sub {
            font-weight: normal;
            color: rgb(1, 68, 202);
            font-size: 36px;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #003399;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            margin-right: 20px;
        }
        input {
            width: calc(50% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #003399;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #002266;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
        footer {
            background-color: #003399;
            color: white;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>
            <span class="title_main">현대오토에버</span>
            <span class="title_sub">VaatzIT</span>
        </h1>
    </header>
    <div class="container">
        <h2>ID 찾기</h2>
        <p>가입 시 등록한 사업자등록번호로 ID를 찾습니다.<br>사업자 등록번호를 입력하세요.</p>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php elseif (!empty($found_username)): ?>
            <p class="success-message">등록된 사업자 등록번호에 해당하는 아이디는 <strong><?php echo $found_username; ?></strong> 입니다.</p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="business_number">사업자 등록번호</label>
                <input type="text" id="business_number" name="business_number" required>
            </div>
            <button type="submit">확인</button>
        </form>
    </div>
    <footer>
        <p>COPYRIGHT 2019 HYUNDAI AUTOEVER CORP. ALL RIGHTS RESERVED.</p>
    </footer>
</body>
</html>
