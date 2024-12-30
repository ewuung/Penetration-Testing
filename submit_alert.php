<?php
// submit_inquiry.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 데이터 수집
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);

    // 데이터 유효성 검사
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        echo "<script>alert('모든 필드를 채워주세요.'); window.history.back();</script>";
        exit;
    }

    // 이메일 형식 검증
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('유효한 이메일 주소를 입력해주세요.'); window.history.back();</script>";
        exit;
    }

    // 데이터 저장 (예: 데이터베이스에 저장하거나 이메일로 전송)
    // 여기에 데이터베이스 저장 코드를 추가하거나 이메일 전송 로직을 작성하십시오.

    // 성공 메시지 출력 및 리다이렉트
    echo "<script>alert('문의가 성공적으로 접수되었습니다. 감사합니다. 곧 연락드리겠습니다.'); window.location.href='QnA.php';</script>";
    exit;
} else {
    // 잘못된 접근 방지
    echo "<script>alert('잘못된 요청입니다.'); window.location.href='QnA.php';</script>";
    exit;
}
?>

