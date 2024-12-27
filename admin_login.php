<?php
session_start();  // 세션 시작

// DB 연결 설정
$servername = "localhost";
$username = "root"; // DB 사용자 이름
$password = ""; // DB 비밀번호
$dbname = "vaatzit"; // 실제 DB 이름으로 변경

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 로그인 처리
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 사용자 입력값 받기
    $userID = $_POST['userID'];
    $password = $_POST['password'];

    // SQL 쿼리 작성 (userID로 사용자 정보 가져오기)
    $sql = "SELECT id, admin_id, admin_pw FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID); // 's'는 문자열 형식
    $stmt->execute();
    $result = $stmt->get_result();

    // 사용자 정보가 존재하면
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 비밀번호 검증 (admin_pw와 사용자가 입력한 비밀번호 비교)
        if ($password === $user['admin_pw']) {
            // 로그인 성공 시 세션에 사용자 정보 저장
            $_SESSION['userID'] = $userID;
            $_SESSION['user_name'] = $user['admin_id'];  // 예시로 사용자 이름도 저장 가능

            // 로그인 성공 후 admin_board.php로 리디렉션
            header("Location: admin_board.php");  // admin_board.php로 이동
            exit();
        } else {
            // 비밀번호가 틀린 경우
            $error = "잘못된 비밀번호입니다.";
        }
    } else {
        // 사용자 ID가 존재하지 않는 경우
        $error = "존재하지 않는 아이디입니다.";
    }

    // 연결 종료
    $stmt->close();
}

// DB 연결 종료
$conn->close();
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
            width: 100%;
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
            ※ ID/비밀번호 분실, 재발급 건은 담당자에게 연락주시기 바랍니다.
        </p>
    </div>
</body>
</html>
