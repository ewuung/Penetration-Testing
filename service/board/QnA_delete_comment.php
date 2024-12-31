<?php
require '../../db.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$comment_id = (int)$_GET['id'];

try {
    // 댓글 조회
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment) {
        die("존재하지 않는 댓글입니다.");
    }

    // 작성자 확인
    if ($comment['MEM_ID'] !== $_SESSION['user_id']) {
        echo "<div style='max-width: 600px; margin: 50px auto; text-align: center;'>
                <h2>댓글 삭제 권한이 없습니다.</h2>
                <a href='javascript:history.back()' style='color: #003399; text-decoration: none; padding: 10px 20px; background-color: #f1f1f1; border-radius: 4px;'>뒤로가기</a>
              </div>";
        exit;
    }

    // 댓글 삭제
    $delete_stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $delete_stmt->execute([$comment_id]);

    // 게시글로 리다이렉트
    header("Location: QnA_detail.php?id=" . $comment['post_id']);
    exit;

} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}
?>
