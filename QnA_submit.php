<?php
session_start();
require 'db.php';  // 데이터베이스 연결

try {
    // 로그인된 사용자 MEM_ID
    $author = $_SESSION['user_id'];
    if (!$author) {
        throw new Exception("로그인 상태가 아닙니다.");
    }

    // POST 데이터 수집
    $title = htmlspecialchars($_POST['title']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $content = htmlspecialchars($_POST['content']);
    $is_private = isset($_POST['is_private']) ? 1 : 0;
    $password = $is_private && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // 파일 업로드 처리
    $file_path = null;
    if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['uploaded_file']['name']);
        $file_path = $upload_dir . $file_name;
        if (!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
            throw new Exception("파일 업로드에 실패했습니다.");
        }
    }

    // 데이터 삽입
    $stmt = $pdo->prepare("
        INSERT INTO board (title, MEM_ID, email, phone, content, is_private, password, file_path)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$title, $author, $email, $phone, $content, $is_private, $password, $file_path]);

    echo "<script>
        alert('글이 성공적으로 저장되었습니다.');
        location.href = 'QnA_board.php';
    </script>";
} catch (Exception $e) {
    echo "<script>
        alert('오류 발생: {$e->getMessage()}');
        history.back();
    </script>";
}
