<?php
require '../../db.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$id = (int)$_GET['id'];

try {
    // 데이터 조회
    $stmt = $pdo->prepare("SELECT * FROM board WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        die("존재하지 않는 글입니다.");
    }

    // 비밀글 확인
    if ($post['is_private'] == 1 && (!isset($_SESSION['authenticated_posts'][$id]) || $_SESSION['authenticated_posts'][$id] !== true)) {
        die("비밀글에 접근 권한이 없습니다.");
    }

    // 작성자 확인
    if ($post['MEM_ID'] !== $_SESSION['user_id']) {
        die("<div style='max-width: 600px; margin: 50px auto; text-align: center;'>
                <h2>권한이 없습니다.</h2>
                <a href='QnA_board.php' style='color: #003399; text-decoration: none;'>뒤로가기</a>
             </div>");
    }

    // 글 삭제
    $stmt = $pdo->prepare("DELETE FROM board WHERE id = ?");
    $stmt->execute([$id]);

    // 댓글 삭제는 외래 키 제약 조건으로 처리됨 (ON DELETE CASCADE)
    header("Location: QnA_board.php");
    exit;
} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}
?>

