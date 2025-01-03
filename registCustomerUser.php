<?php
session_start();
require 'db.php';

$success_message = ''; // 초기화
$error_message = ''; // 오류 메시지 초기화
$duplicate_id_error = ''; // 아이디 중복 오류 메시지

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
    if (empty($error_message) && empty($duplicate_id_error)) {
        try {
            // 사용자 ID 중복 체크
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM MEMBERS WHERE MEM_ID = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $result = $stmt->fetchColumn();

            // 중복이 있으면 오류 메시지 표시
            if ($result > 0) {
                $duplicate_id_error = "이미 사용 중인 아이디가 있습니다. 다른 값을 입력해주세요.";
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
            padding-right: 11%;
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
        /* 아이디 중복 확인 버튼을 입력란 옆에 배치 */
        .input-wrapper {
            display: flex;
            align-items: center;
        }
        .input-wrapper input {
            width: calc(100% -120px);
            font-size: 14px;
            padding: 8px;
        }
        .input-wrapper button {
            width: 90px;
            margin-left: 10px;
            font-size: 12px;
            padding: 5px 10px;
        }
          /* 글로벌 내비게이션 스타일 */
        .global-nav {
            background-color: white;
            width: 100%;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .global-nav ul {
            list-style-type: none;
            justify-content: center;
            align-items: center;
            width: auto;
            padding: 0;
            margin: 0 auto;
        }

        .global-nav li {
            position: relative; /* 서브메뉴를 절대 위치로 띄우기 위해 필요 */
            display: inline-block;
            margin: 0 20px;
        }

        .global-nav a {
            text-decoration: none;
            color: #003399;
            font-weight: bold;
            font-size: 18px;
            padding: 15px 20px;
            display: block;
            transition: color 0.3s ease;
            cursor: pointer;
        }

        .global-nav a:hover {
            color: #f9f9f9;
            background-color: #003399;
        }

        /* 서브메뉴 기본 숨김 */
        .submenu {
            display: none; /* 기본적으로 숨겨두기 */
            position: absolute;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            min-width: 160px;
        }

        /* 부모 항목에 커서 올렸을 때 서브메뉴 표시 */
        .global-nav li:hover .submenu {
            display: block; /* 부모 항목에 마우스를 올리면 표시 */
        }

        /* 서브메뉴 항목 스타일 */
        .submenu li {
            margin: 0;
            display: block;
        }

        .submenu a {
            padding: 10px 20px;
            color: #003399;
            text-decoration: none;
            font-size: 15px;
            display: block;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .submenu a:hover {
            background-color: #f1f1f1;
            color: #002266;
        }
    </style>
    <script>
        // 아이디 중복 확인 버튼 클릭 시 실행
        function checkUserId() {
            var userId = document.getElementById("user_id").value;
            var message = document.getElementById("user-id-message");

            if (userId) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "checkId.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        if (xhr.responseText === "exists") {
                            message.style.color = "red";
                            message.innerHTML = "이미 사용 중인 아이디입니다.";
                        } else {
                            message.style.color = "green";
                            message.innerHTML = "사용 가능한 아이디입니다.";
                        }
                    }
                };
                xhr.send("user_id=" + userId);
            } else {
                message.innerHTML = "";
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>
            <a href="main.php" class="title_main" style="text-decoration: none; color: inherit;">
                <span class="title_main">현대오토에버</span>
            </a>
            <span class="title_sub">VaatzIT</span>
        </h1>
        <nav class="global-nav">
          <ul>
              <li>
                  <a href="./service/support.php">고객센터</a>
                  <ul class="submenu">
                      <li><a href="./service/notice/notice.php">공지사항</a></li>
                      <li><a href="./service/board/QnA.php">FAQ 및 Q&A</a></li>
                      <li><a href="./service/member_guide.php">회원사 가입 안내</a></li>
                  </ul>
              </li>
              <li>
                  <a href="registCustomerUser.php">고객담당자 등록</a>
                  <ul class="submenu">
                      <li><a href="registCustomerUser.php">회원 가입</a></li>  
                  </ul>
              </li>
              <li>
                  <a href="VaatzIT_Mall.php">VaatzIT Mall</a>
                  <ul class="submenu">
                      <li><a href="VaatzIT_Mall.php">상품 보기</a></li>
                  </ul>
              </li>
              <li><a href="#">4대 실천사항</a></li>
              <li><a href="#">동반성장</a></li>
              <li><a href="#">공정 거래</a></li>
          </ul>
      </nav>
    </header>
    <div class="container">
        <h2>고객사 회원가입</h2>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($duplicate_id_error)): ?>
            <p class="error-message"><?php echo $duplicate_id_error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
            <script type="text/javascript">
                alert("<?php echo $success_message; ?>");
                window.location.href = "main.php";
            </script>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group input-wrapper">
                <label for="user_id">아이디</label>
                <input type="text" id="user_id" name="user_id" required>
                <button type="button" onclick="checkUserId()">중복 확인</button>
            </div>
            <p id="user-id-message"></p>
            <div class="form-group">
                <label for="user_passwd">비밀번호</label>
                <input type="password" id="user_passwd" name="user_passwd" required>
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
                    <option value="현대오토에버(자산)">현대오토에버(자산)</option>
                    <option value="현대오토에버(판매)">현대오토에버(판매)</option>
                    <option value="현대자동차">현대자동차</option>
                    <option value="기아자동차">기아자동차</option>
                    <option value="현대건설">현대건설</option>
                    <option value="HYUNDAI E&C">HYUNDAI E&C</option>
                    <option value="현대 엔지니어링">현대 엔지니어링</option>
                    <option value="현대종합설계건축사무소">현대종합설계건축사무소</option>
                    <option value="현대스틸산업주식회사">현대스틸산업주식회사</option>
                    <option value="현대카드">현대카드</option>
                    <option value="블루월넛">블루월넛</option>
                    <option value="현대캐피탈">현대캐피탈</option>
                    <option value="현대모비스">현대모비스</option>
                    <option value="현대하이스코">현대하이스코</option>
                    <option value="케피코">케피코</option>
                    <option value="본텍">본텍</option>
                    <option value="기아타이거스">기아타이거스</option>
                    <option value="현대제철주식회사">현대제철주식회사</option>
                    <option value="현대종합특수강">현대종합특수강</option>
                    <option value="삼우 당진공장">삼우 당진공장</option>
                    <option value="그린에어주식회사">그린에어주식회사</option>
                    <option value="현대서산농장">현대서산농장</option>
                    <option value="비앤지스틸">비앤지스틸</option>
                </select>
            </div>
            <div class="form-group">
                <label for="department">부서</label>
                <select id="department" name="department" required>
                    <option value="">부서를 선택하세요.</option>
                    <option value="경영지원팀">경영지원팀</option>
                    <option value="경영지원시스템팀">경영지원시스템팀</option>
                    <option value="구매팀">구매팀</option>
                    <option value="연구개발사업팀">연구개발사업팀</option>
                    <option value="연구개발시스템팀">연구개발시스템팀</option>
                    <option value="판매시스템팀">판매시스템팀</option>
                    <option value="완성차IT사업팀">완성차IT사업팀</option>
                    <option value="상품개발팀">상품개발팀</option>
                    <option value="건설사업팀">건설사업팀</option>
                    <option value="모비스시스템팀">모비스시스템팀</option>
                    <option value="부품시스템팀">부품시스템팀</option>
                    <option value="철강경영시스템팀">철강경영시스템팀</option>
                    <option value="철강생산시스템팀">철강생산시스템팀</option>
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



/////////////////////
<?php
session_start();
require 'db.php';

$success_message = ''; // 초기화
$error_message = ''; // 오류 메시지 초기화
$duplicate_id_error = ''; // 아이디 중복 오류 메시지

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

    // 비밀번호 조건 검사 (서버 측에서도 체크)
    $upperCase = preg_match('/[A-Z]/', $password);
    $lowerCase = preg_match('/[a-z]/', $password);
    $numbers = preg_match('/[0-9]/', $password);
    $specialChars = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);

    $hasUpperCase = preg_match($upperCase, $user_passwd);
    $hasLowerCase = preg_match($lowerCase, $user_passwd);
    $hasNumber = preg_match($numbers, $user_passwd);
    $hasSpecialChar = preg_match($specialChars, $user_passwd);

    $charTypesCount = 0;
    if ($hasUpperCase) $charTypesCount++;
    if ($hasLowerCase) $charTypesCount++;
    if ($hasNumber) $charTypesCount++;
    if ($hasSpecialChar) $charTypesCount++;

    if (!(($user_passwd.length >= 10 && $charTypesCount >= 2) || ($user_passwd.length >= 8 && $charTypesCount >= 3))) {
        $error_message = '비밀번호는 최소 8자리 이상이며, 3종류 이상의 문자를 포함해야 합니다.';
    }

    // 중복 체크
    if (empty($error_message) && empty($duplicate_id_error)) {
        try {
            // 사용자 ID 중복 체크
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM MEMBERS WHERE MEM_ID = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $result = $stmt->fetchColumn();

            // 중복이 있으면 오류 메시지 표시
            if ($result > 0) {
                $duplicate_id_error = "이미 사용 중인 아이디가 있습니다. 다른 값을 입력해주세요.";
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
        .input-wrapper {
            display: flex;
            align-items: center;
        }
        .input-wrapper input {
            width: calc(100% -120px);
            font-size: 14px;
            padding: 8px;
        }
        .input-wrapper button {
            width: 90px;
            margin-left: 10px;
            font-size: 12px;
            padding: 5px 10px;
        }
    </style>
    <script>
        // 비밀번호 강도 검사 함수
        function validatePassword() {
            var password = document.getElementById("user_passwd").value;
            var confirmPassword = document.getElementById("confirm_passwd").value;
            var passwordMessage = document.getElementById("password-match-message");
            var passwordStrengthMessage = document.getElementById("password-strength-message");

            // 정규식 패턴을 정의하여 문자 종류를 체크
            var upperCase = /[A-Z]/;
            var lowerCase = /[a-z]/;
            var numbers = /[0-9]/;
            var specialChars = /[!@#$%^&*(),.?":{}|<>]/;

            var hasUpperCase = upperCase.test(password);
            var hasLowerCase = lowerCase.test(password);
            var hasNumber = numbers.test(password);
            var hasSpecialChar = specialChars.test(password);

            var charTypesCount = 0;
            if (hasUpperCase) charTypesCount++;
            if (hasLowerCase) charTypesCount++;
            if (hasNumber) charTypesCount++;
            if (hasSpecialChar) charTypesCount++;

            // 비밀번호 조건 검사
            if (password.length >= 10 && charTypesCount >= 2) {
                passwordStrengthMessage.style.color = "green";
                passwordStrengthMessage.innerHTML = "비밀번호가 안전합니다.";
            } else if (password.length >= 8 && charTypesCount >= 3) {
                passwordStrengthMessage.style.color = "green";
                passwordStrengthMessage.innerHTML = "비밀번호가 안전합니다.";
            } else {
                passwordStrengthMessage.style.color = "red";
                passwordStrengthMessage.innerHTML = "비밀번호는 최소 8자리 이상이며, 3종류 이상의 문자를 포함해야 합니다.";
            }

            // 비밀번호 확인 일치 여부 검사
            if (password !== confirmPassword) {
                passwordMessage.style.display = "block";
                passwordMessage.style.color = "red";
                passwordMessage.innerHTML = "비밀번호가 일치하지 않습니다.";
            } else {
                passwordMessage.style.display = "none";
            }
        }

        // 비밀번호 입력 시 강도 체크와 일치 여부 체크
        document.getElementById("user_passwd").addEventListener("input", validatePassword);
        document.getElementById("confirm_passwd").addEventListener("input", validatePassword);
    </script>
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
        <?php if (!empty($duplicate_id_error)): ?>
            <p class="error-message"><?php echo $duplicate_id_error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="user_id">아이디</label>
                <input type="text" id="user_id" name="user_id" required>
            </div>
            <div class="form-group">
                <label for="user_passwd">비밀번호</label>
                <input type="password" id="user_passwd" name="user_passwd" required>
                <p id="password-strength-message" style="color: red; font-size: 12px;"></p> <!-- 비밀번호 강도 메시지 -->
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
                <label for="email">이메일</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone_number">전화번호</label>
                <input type="tel" id="phone_number" name="phone_number" required>
            </div>
            <button type="submit">등록</button>
        </form>
    </div>
</body>
</html>