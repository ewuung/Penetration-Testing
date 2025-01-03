<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("로그인이 필요합니다.");
}

$user_id = $_SESSION['user_id'];

// 기존 사용자 정보를 가져오기 위한 코드
$query = "SELECT * FROM MEMBERS WHERE MEM_ID = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("사용자 정보를 찾을 수 없습니다.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 사용자가 제출한 폼 데이터 처리
    $customer_company = $_POST['customer_company'];
    $department = $_POST['department'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $user_passwd = isset($_POST['user_passwd']) ? $_POST['user_passwd'] : null; // 비밀번호가 제출되었는지 확인

    // 비밀번호가 수정된 경우에만 비밀번호를 업데이트
    $update_fields = [
        'customer_company' => $customer_company,
        'department' => $department,
        'username' => $username,
        'email' => $email,
        'phone_number' => $phone_number,
        'user_id' => $user_id
    ];

    if ($user_passwd) {
        $update_fields['user_passwd'] = md5($user_passwd);
        $sql = "UPDATE MEMBERS SET COM_ID = :customer_company, MEM_TEAM = :department, MEM_NAME = :username, MEM_PW = :user_passwd, MEM_EMAIL = :email, MEM_PHONENUM = :phone_number WHERE MEM_ID = :user_id";
    } else {
        $sql = "UPDATE MEMBERS SET COM_ID = :customer_company, MEM_TEAM = :department, MEM_NAME = :username, MEM_EMAIL = :email, MEM_PHONENUM = :phone_number WHERE MEM_ID = :user_id";
    }

    // 데이터베이스에서 사용자 정보 업데이트
    $stmt = $pdo->prepare($sql);
    $stmt->execute($update_fields);

    $success_message = "회원 정보가 성공적으로 수정되었습니다.";
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
        <h2>회원 정보 수정</h2>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
            <script>
                // 회원 정보 수정 알림창
                alert('회원 정보가 성공적으로 수정되었습니다.');
                // home.php로 이동
                window.location.href = 'home.php';
            </script>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="customer_company">고객사</label>
                <select id="customer_company" name="customer_company" required>
                <option value="">고객사를 선택하세요.</option>
                    <option value="현대오토에버(자산)" <?php echo ($user['COM_ID'] == '현대오토에버(자산)') ? 'selected' : ''; ?>>현대오토에버(자산)</option>
                    <option value="현대오토에버(판매)" <?php echo ($user['COM_ID'] == '현대오토에버(판매)') ? 'selected' : ''; ?>>현대오토에버(판매)</option>
                    <option value="현대자동차" <?php echo ($user['COM_ID'] == '현대자동차') ? 'selected' : ''; ?>>현대자동차</option>
                    <option value="기아자동차" <?php echo ($user['COM_ID'] == '기아자동차') ? 'selected' : ''; ?>>기아자동차</option>
                    <option value="현대건설" <?php echo ($user['COM_ID'] == '현대건설') ? 'selected' : ''; ?>>현대건설</option>
                    <option value="HYUNDAI E&C" <?php echo ($user['COM_ID'] == 'HYUNDAI E&C') ? 'selected' : ''; ?>>HYUNDAI E&C</option>
                    <option value="현대 엔지니어링" <?php echo ($user['COM_ID'] == '현대 엔지니어링') ? 'selected' : ''; ?>>현대 엔지니어링</option>
                    <option value="현대종합설계건축사무소" <?php echo ($user['COM_ID'] == '현대종합설계건축사무소') ? 'selected' : ''; ?>>현대종합설계건축사무소</option>
                    <option value="현대스틸산업주식회사" <?php echo ($user['COM_ID'] == '현대스틸산업주식회사') ? 'selected' : ''; ?>>현대스틸산업주식회사</option>
                    <option value="현대카드" <?php echo ($user['COM_ID'] == '현대카드') ? 'selected' : ''; ?>>현대카드</option>
                    <option value="블루월넛" <?php echo ($user['COM_ID'] == '블루월넛') ? 'selected' : ''; ?>>블루월넛</option>
                    <option value="현대캐피탈" <?php echo ($user['COM_ID'] == '현대캐피탈') ? 'selected' : ''; ?>>현대캐피탈</option>
                    <option value="현대모비스" <?php echo ($user['COM_ID'] == '현대모비스') ? 'selected' : ''; ?>>현대모비스</option>
                    <option value="현대하이스코" <?php echo ($user['COM_ID'] == '현대하이스코') ? 'selected' : ''; ?>>현대하이스코</option>
                    <option value="케피코" <?php echo ($user['COM_ID'] == '케피코') ? 'selected' : ''; ?>>케피코</option>
                    <option value="본텍" <?php echo ($user['COM_ID'] == '본텍') ? 'selected' : ''; ?>>본텍</option>
                    <option value="기아타이거스" <?php echo ($user['COM_ID'] == '기아타이거스') ? 'selected' : ''; ?>>기아타이거스</option>
                    <option value="현대제철주식회사" <?php echo ($user['COM_ID'] == '현대제철주식회사') ? 'selected' : ''; ?>>현대제철주식회사</option>
                    <option value="현대종합특수강" <?php echo ($user['COM_ID'] == '현대종합특수강') ? 'selected' : ''; ?>>현대종합특수강</option>
                    <option value="삼우 당진공장" <?php echo ($user['COM_ID'] == '삼우 당진공장') ? 'selected' : ''; ?>>삼우 당진공장</option>
                    <option value="그린에어주식회사" <?php echo ($user['COM_ID'] == '그린에어주식회사') ? 'selected' : ''; ?>>그린에어주식회사</option>
                    <option value="현대서산농장" <?php echo ($user['COM_ID'] == '현대서산농장') ? 'selected' : ''; ?>>현대서산농장</option>
                    <option value="비앤지스틸" <?php echo ($user['COM_ID'] == '비앤지스틸') ? 'selected' : ''; ?>>비앤지스틸</option>
                </select>
            </div>
            <div class="form-group">
                <label for="department">부서</label>
                <select id="department" name="department" required>
                <option value="">부서를 선택하세요.</option>
                    <option value="경영지원팀" <?php echo ($user['MEM_TEAM'] == '경영지원팀') ? 'selected' : ''; ?>>경영지원팀</option>
                    <option value="경영지원시스템팀" <?php echo ($user['MEM_TEAM'] == '경영지원시스템팀') ? 'selected' : ''; ?>>경영지원시스템팀</option>
                    <option value="구매팀" <?php echo ($user['MEM_TEAM'] == '구매팀') ? 'selected' : ''; ?>>구매팀</option>
                    <option value="연구개발사업팀" <?php echo ($user['MEM_TEAM'] == '연구개발사업팀') ? 'selected' : ''; ?>>연구개발사업팀</option>
                    <option value="연구개발시스템팀" <?php echo ($user['MEM_TEAM'] == '연구개발시스템팀') ? 'selected' : ''; ?>>연구개발시스템팀</option>
                    <option value="판매시스템팀" <?php echo ($user['MEM_TEAM'] == '판매시스템팀') ? 'selected' : ''; ?>>판매시스템팀</option>
                    <option value="완성차IT사업팀" <?php echo ($user['MEM_TEAM'] == '완성차IT사업팀') ? 'selected' : ''; ?>>완성차IT사업팀</option>
                    <option value="상품개발팀" <?php echo ($user['MEM_TEAM'] == '상품개발팀') ? 'selected' : ''; ?>>상품개발팀</option>
                    <option value="건설사업팀" <?php echo ($user['MEM_TEAM'] == '건설사업팀') ? 'selected' : ''; ?>>건설사업팀</option>
                    <option value="모비스시스템팀" <?php echo ($user['MEM_TEAM'] == '모비스시스템팀') ? 'selected' : ''; ?>>모비스시스템팀</option>
                    <option value="부품시스템팀" <?php echo ($user['MEM_TEAM'] == '부품시스템팀') ? 'selected' : ''; ?>>부품시스템팀</option>
                    <option value="Steel Management Systems" <?php echo ($user['MEM_TEAM'] == 'Steel Management Systems') ? 'selected' : ''; ?>>철강경영시스템팀</option>
                    <option value="철강경영시스템팀" <?php echo ($user['MEM_TEAM'] == '철강경영시스템팀') ? 'selected' : ''; ?>>철강생산시스템팀</option>
                </select>
                <div class="form-group">
                <label for="username">성명</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['MEM_NAME']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">연락처(휴대폰)</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['MEM_PHONENUM']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['MEM_EMAIL']); ?>" required>
            </div>
            <div class="form-group">
                <label for="user_passwd">비밀번호</label>
                <input type="password" id="user_passwd" name="user_passwd" placeholder="비밀번호를 입력하세요 (수정 시)">
            </div>
            <button type="submit">수정</button>
        </form>
    </div>
</body>
</html>
