<?php
session_start();
require 'db.php';

$success_message = ''; // 초기화
$error_message = ''; // 오류 메시지 초기화

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 사용자가 제출한 폼 데이터 처리
    $customer_company = $_POST['customer_company'] ?? null;
    $user_id = $_POST['user_id'] ?? null;
    $user_passwd = $_POST['user_passwd'] ?? null;
    $confirm_passwd = $_POST['confirm_passwd'] ?? null;
    $department = $_POST['department'] ?? null;
    $full_name = $_POST['full_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone_number = $_POST['phone_number'] ?? null;

    // 비밀번호 확인
    if (($user_passwd) !== $confirm_passwd) {
        $error_message = '비밀번호가 일치하지 않습니다. 다시 시도해주세요.';
    }

    // 중복 체크
    if (empty($error_message)) {
        try {
            // 사용자 ID 중복 체크
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM MEMBERS WHERE MEM_ID = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $result = $stmt->fetchColumn();

            // 중복이 있으면 오류 메시지 표시
            if ($result > 0) {
                $error_message = "이미 사용 중인 아이디가 있습니다. 다른 값을 입력해주세요.";
            } else {
                // 중복 없으면 데이터베이스에 입력
                $stmt = $pdo->prepare("
                    INSERT INTO MEMBERS (COM_ID, MEM_ID, MEM_PW, MEM_TEAM, MEM_NAME, MEM_EMAIL, MEM_PHONENUM) 
                    VALUES (:customer_company, :user_id, :user_passwd, :department, :full_name, :email, :phone_number)
                ");
                $stmt->execute([
                    'customer_company' => $customer_company,
                    'user_id' => $user_id,
                    'user_passwd' => md5($user_passwd),
                    'department' => $department,
                    'full_name' => $full_name,
                    'email' => $email,
                    'phone_number' => $phone_number
                ]);

                $success_message = "고객 담당자가 성공적으로 등록되었습니다.";
            }
        } catch (PDOException $e) {
            $error_message = "데이터베이스 에러: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!-- HTML 폼 부분 -->
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>고객담당자 등록</title>
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
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
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
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<header>
    <h1>
        <a href="main.php" class="title_main" style="text-decoration: none; color: inherit;">
            <span class="title_main">현대오토에버</span>
        </a>
        <span class="title_sub">VaatzIT</span>
    </h1>
</header>
    <div class="container">
        <h2>고객사 회원가입</h2>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
            <script type="text/javascript">
                // 알림창 표시 후 main.php로 리디렉션
                alert("<?php echo $success_message; ?>");
                window.location.href = "main.php"; // main.php로 이동
            </script>
            <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="user_id">아이디</label>
                <input type="text" id="user_id" name="user_id" required>
            </div>
            <div class="form-group">
                <label for="user_passwd">비밀번호</label>
                <input type="text" id="user_passwd" name="user_passwd" required>
            </div>
            <div class="form-group">
                <label for="confirm_passwd">비밀번호 확인</label>
                <input type="password" id="confirm_passwd" name="confirm_passwd" required>
                <small id="password-match-message" style="color: red; display: none;">비밀번호가 일치하지 않습니다.</small>
            </div>
            <div class="form-group">
                <label for="customer_company">고객사</label>
                <select id="customer_company" name="customer_company" required>
                    <option value="">고객사를 선택하세요.</option>
                    <option value="company1">현대오토에버(자산)</option>
                    <option value="company2">현대오토에버(판매)</option>
                    <option value="company3">현대자동차</option>
                    <option value="company4">기아자동차</option>
                    <option value="company5">현대건설</option>
                    <option value="company6">HYUNDAI E&C</option>
                    <option value="company7">현대 엔지니어링</option>
                    <option value="company8">현대종합설계건축사무소</option>
                    <option value="company9">현대스틸산업주식회사</option>
                    <option value="company10">현대카드</option>
                    <option value="company11">블루월넛</option>
                    <option value="company12">현대캐피탈</option>
                    <option value="company13">현대모비스</option>
                    <option value="company14">현대하이스코</option>
                    <option value="company15">케피코</option>
                    <option value="company16">본텍</option>
                    <option value="company17">기아타이거스</option>
                    <option value="company18">현대제철주식회사</option>
                    <option value="company19">현대종합특수강</option>
                    <option value="company20">삼우 당진공장</option>
                    <option value="company21">그린에어주식회사</option>
                    <option value="company22">현대서산농장</option>
                    <option value="company23">비앤지스틸</option>
                </select>
            </div>
            <div class="form-group">
                <label for="department">부서</label>
                <select id="department" name="department" required>
                    <option value="">부서를 선택하세요.</option>
                    <option value="Management Support">경영지원팀</option>
                    <option value="Management Support Systems">경영지원시스템팀</option>
                    <option value="Purchasing">구매팀</option>
                    <option value="R&D Business">연구개발사업팀</option>
                    <option value="R&D Systems">연구개발시스템팀</option>
                    <option value="Sales Systems">판매시스템팀</option>
                    <option value="Finished Vehicle IT Business">완성차IT사업팀</option>
                    <option value="Product Development">상품개발팀</option>
                    <option value="Construction Business">건설사업팀</option>
                    <option value="Mobis Systems">모비스시스템팀</option>
                    <option value="Parts Systems">부품시스템팀</option>
                    <option value="Steel Management Systems">철강경영시스템팀</option>
                    <option value="Steel Product Systems">철강생산시스템팀</option>
                </select>
            </div>
            <div class="form-group">
                <label for="full_name">성명</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="phone_number">연락처(휴대폰)</label>
                <input type="tel" id="phone_number" name="phone_number" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">등록</button>
        </form>
    </div>
</body>
</html>
