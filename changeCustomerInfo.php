<?php
session_start();
require 'db.php';

try {
    // 사용자 세션 정보 확인
    if (!isset($_SESSION['MEM_ID'])) {
        die("로그인이 필요합니다.");
    }

    $user_id = $_SESSION['MEM_ID'];

    // 사용자 정보 가져오기
    $query = "SELECT * FROM MEMBER WHERE MEM_ID = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("사용자 정보를 찾을 수 없습니다.");
    }
} catch (PDOException $e) {
    error_log("DB 오류: " . $e->getMessage(), 3, '/path/to/logfile.log');
    die("데이터를 가져오는데 문제가 발생했습니다.");
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원 정보 수정</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
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
        .container p {
            color: red;
            font-weight: bold;
            font-size: 14px;
        }
        h2 {
            text-align: center;
            color: #003399;
        }
        .form-group {
            margin-bottom: 15px;
        }
        select {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        form {
            width: 400px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="tel"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #003399;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0055cc;
        }
        /* 버튼을 가운데 정렬 */
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>회원 정보 수정</h2>
        <form action="updateCustomerInfo.php" method="POST">
            <div class="form-group">
                <label for="user_id">아이디 (변경 불가)</label>
                <input type="text" id="user_id" name="user_id" value="<?= htmlspecialchars($user['MEM_ID']) ?>" readonly>
            </div>
            <div class="form-group">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password" placeholder="새 비밀번호">
            </div>
            <div class="form-group">
                <label for="password_confirm">비밀번호 확인</label>
                <input type="password" id="password_confirm" name="password_confirm" placeholder="비밀번호 확인">
            </div>
            <div class="form-group">
                <label for="company_name">고객사명</label>
                <input type="text" id="company_name" name="company_name" value="<?= htmlspecialchars($user['COM_NAME']) ?>">
            </div>
            <div class="form-group">
                <label for="company_code">고객사 코드 (자동 입력)</label>
                <input type="text" id="company_code" name="company_code" value="<?= htmlspecialchars($user['COM_CODE']) ?>" readonly>
            </div>
            <div class="form-group">
                <label for="team">부서</label>
                <input type="text" id="team" name="team" value="<?= htmlspecialchars($user['MEM_TEAM']) ?>">
            </div>
            <div class="form-group">
                <label for="name">성명</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['MEM_NAME']) ?>">
            </div>
            <div class="form-group">
                <label for="phone">연락처</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['MEM_PHONNUM']) ?>">
            </div>
            <div class="form-group">
                <label for="email">이메일</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['MEM_EMAIL']) ?>">
            </div>
            <div class="button-container">
                <button type="submit">정보 수정</button>
            </div>
        </form>

        <script>
            // 고객사명 입력 시 고객사 코드 자동 가져오기
            $('#company_name').on('input', function() {
                var companyName = $(this).val();
                $.ajax({
                    url: 'getCompanyCode.php',
                    type: 'POST',
                    data: { company_name: companyName },
                    success: function(data) {
                        $('#company_code').val(data); // 고객사 코드 입력 필드에 데이터 설정
                    },
                    error: function() {
                        alert('고객사 코드를 가져오는데 실패했습니다.');
                    }
                });
            });
        </script>
    </div>
</body>
</html>
