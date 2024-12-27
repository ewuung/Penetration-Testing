<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 사용자가 제출한 폼 데이터 처리
    $user_id = $_SESSION['user_id'];
    $customer_company = $_POST['customer_company'];
    $department = $_POST['department'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $user_passwd = isset($_POST['user_passwd']) ? $_POST['user_passwd'] : null; // 비밀번호가 제출되었는지 확인

    // 비밀번호가 수정된 경우에만 비밀번호를 업데이트
    $update_fields = [
        'customer_company' => $customer_company,
        'department' => $department,
        'full_name' => $full_name,
        'email' => $email,
        'phone_number' => $phone_number,
        'user_id' => $user_id
    ];

    if ($user_passwd) {
        $update_fields['user_passwd'] = password_hash($user_passwd, PASSWORD_DEFAULT); // 비밀번호 해시화
        $sql = "UPDATE MEMBERS SET COM_ID = :customer_company, MEM_TEAM = :department, MEM_NAME = :full_name, MEM_PW = :user_passwd, MEM_EMAIL = :email, MEM_PHONENUM = :phone_number WHERE MEM_ID = :user_id";
    } else {
        $sql = "UPDATE MEMBERS SET COM_ID = :customer_company, MEM_TEAM = :department, MEM_NAME = :full_name, MEM_EMAIL = :email, MEM_PHONENUM = :phone_number WHERE MEM_ID = :user_id";
    }

    // 데이터베이스에서 사용자 정보 업데이트
    $stmt = $pdo->prepare($sql);
    $stmt->execute($update_fields);

    $success_message = "회원 정보가 성공적으로 수정되었습니다.";
}

// 기존 사용자 정보를 가져오기 위한 코드
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM MEMBERS WHERE MEM_ID = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    die("로그인이 필요합니다.");
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
    <div class="container">
        <h2>회원 정보 수정</h2>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="customer_company">고객사</label>
                <select id="customer_company" name="customer_company" required>
                    <option value="">고객사를 선택하세요.</option>
                    <option value="company1" <?php if ($user['customer_company'] === 'company1') echo 'selected'; ?>>현대오토에버(자산)</option>
                    <option value="company2" <?php if ($user['customer_company'] === 'company2') echo 'selected'; ?>>현대오토에버(판매)</option>
                    <!-- 추가 회사 옵션들 -->
                </select>
            </div>
            <div class="form-group">
                <label for="department">부서</label>
                <select id="department" name="department" required>
                    <option value="">부서를 선택하세요.</option>
                    <option value="Management Support" <?php if ($user['department'] === 'Management Support') echo 'selected'; ?>>경영지원팀</option>
                    <option value="Management Support Systems" <?php if ($user['department'] === 'Management Support Systems') echo 'selected'; ?>>경영지원시스템팀</option>
                    <!-- 추가 부서 옵션들 -->
                </select>
            </div>
            <div class="form-group">
                <label for="full_name">성명</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">연락처(휴대폰)</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
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
