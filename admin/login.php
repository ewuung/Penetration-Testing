<?php
session_start();
include('../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $password = $_POST['password'];

    $hashed_password = md5($password);

    $sql = "SELECT ADMIN_ID, ADMIN_NAME, ADMIN_PW FROM ADMIN WHERE ADMIN_ID = '$userID' AND ADMIN_PW = '$hashed_password'";
    $result = $pdo->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $_SESSION['user_id'] = $row['ADMIN_ID'];
        $_SESSION['username'] = $row['ADMIN_NAME'];
        header("Location: board.php");
        exit();
    } else {
        $error = "아이디 또는 비밀번호가 잘못되었습니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>현대오토에버 VaatzIT 관리자페이지</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 400px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #005bac;
            margin-bottom: 20px;
            font-size: 24px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            padding: 10px;
            background-color: #005bac;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #004080;
        }

        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }

        .info-message {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
            text-align: center;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>현대오토에버 VaatzIT 관리자페이지</h2>
        <form action="" method="POST">
            <div>
                <label for="userID">User ID</label>
                <input type="text" name="userID" id="userID" placeholder="아이디" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="비밀번호" required>
            </div>
            <button type="submit">로그인</button>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
        <p class="info-message">
            ※ ID/비밀번호 분실, 재발급 건은<br>담당자에게 연락주시기 바랍니다.
        </p>
    </div>
</body>
</html>
