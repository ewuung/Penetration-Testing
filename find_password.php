<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $manager_name = $_POST['manager_name'];

    // 아이디와 담당자 이름으로 사용자 정보 찾기
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND manager_name = :manager_name");
    $stmt->execute(['username' => $username, 'manager_name' => $manager_name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // 이메일로 임시 패스워드 전송
        $temporary_password = bin2hex(random_bytes(8)); // 임시 비밀번호 생성
        $hashed_password = md5($temporary_password); // MD5는 예제용. 실제론 password_hash 사용 권장.

        // 사용자 비밀번호를 임시 비밀번호로 업데이트
        $update_stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $update_stmt->execute(['password' => $hashed_password, 'id' => $user['id']]);

        // 이메일로 임시 비밀번호 전송 (메일 전송 로직 필요)
        mail($user['email'], '임시 비밀번호 안내', "임시 비밀번호: $temporary_password");

        $success_message = "임시 비밀번호가 등록된 이메일로 전송되었습니다.";
    } else {
        $error = "아이디 또는 담당자 이름이 일치하지 않습니다!";
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>현대오토에버 VaatzIT 비밀번호 찾기</title>
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
            display: inline-block;
            font-weight: bold;
            width: 120px;
        }
        input {
            width: calc(60% - 20px);
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
        <h2>등록 정보로 찾기</h2>
        <p>담당자로 등록되어 있는 이메일 주소로 임시 패스워드가 전송됩니다.<br>아이디와 담당자 이름을 입력하세요.</p>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php elseif (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">아이디</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="manager_name">담당자</label>
                <input type="text" id="manager_name" name="manager_name" required>
            </div>
            <button type="submit">조회</button>
        </form>
    </div>
    <footer>
        <p>COPYRIGHT 2019 HYUNDAI AUTOEVER CORP. ALL RIGHTS RESERVED.</p>
    </footer>
</body>
</html>
