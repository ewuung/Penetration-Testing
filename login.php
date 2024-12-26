<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && md5($password) === $user['password']) { // MD5는 예제용. 실제론 password_verify 사용 권장.
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: home.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>현대오토에버 VaatzIT 로그인</title>
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
            padding: 20px;
            padding-left: 160px;
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
        }
        h2 {
            text-align: center;
            color: #003399;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: calc(100% - 20px);
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
        footer {
            background-color: #003399;
            color: white;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px 0;
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
        <h2>로그인</h2>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">아이디</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">로그인</button>
        </form>
    </div>
    <footer>
        <p>COPYRIGHT 2019 HYUNDAI AUTOEVER CORP. ALL RIGHTS RESERVED.</p>
    </footer>
</body>
</html>