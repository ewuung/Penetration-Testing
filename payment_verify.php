<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error_message = ''; // 에러 메시지 초기화

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POST 요청이 있을 때만 실행되도록 조건 추가
    $amount = $_POST['amount'];
    $user_password = $_POST['password'];  // 사용자가 입력한 비밀번호
    $user_id = $_SESSION['user_id'];

    // 비밀번호가 입력되지 않은 경우 에러 메시지 표시
    if (empty($user_password)) {
        $error_message = "비밀번호를 입력해주세요.";
    } else {
        // 비밀번호 검증
        try {
            $stmt = $pdo->prepare("SELECT MEM_PW FROM MEMBERS WHERE MEM_ID = ?");
            $stmt->execute([$user_id]);
            $stored_password = $stmt->fetchColumn();  // DB에서 저장된 md5 비밀번호

            if ($stored_password === false) {
                die("회원 정보를 찾을 수 없습니다.");
            }

            // 입력된 비밀번호를 md5로 해시화 후 비교
            if (md5($user_password) === $stored_password) {  // md5 해시 비교
                // 비밀번호가 일치하면 payment_complete.php로 이동
                header("Location: payment_complete.php?amount=" . $amount);
                exit();
            } else {
                // 비밀번호가 일치하지 않으면 오류 메시지 출력
                $error_message = "비밀번호가 일치하지 않습니다.";
            }
        } catch (PDOException $e) {
            die("오류 발생: " . $e->getMessage());
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>비밀번호 확인</title>
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
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>비밀번호를 입력해주세요.</h2>

    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="password">비밀번호</label>
            <input type="password" name="password" required placeholder="비밀번호를 입력해주세요.">
        </div>
        <div class="form-group">
            <button type="submit">확인</button>
            <input type="hidden" name="amount" value="<?php echo $_GET['amount']; ?>">
        </div>
    </form>

    <a href="payment.php?amount=<?php echo $_GET['amount']; ?>" class="back-button">뒤로 가기</a>
</div>
</body>
</html>
