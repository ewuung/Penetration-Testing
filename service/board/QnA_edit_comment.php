<?php
require '../../db.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$commentId = (int)$_GET['id'];

try {
    // 댓글 데이터 가져오기
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment) {
        die("존재하지 않는 댓글입니다.");
    }

    // 댓글 작성자 확인
    if ($comment['MEM_ID'] !== $_SESSION['user_id']) {
        echo "<div style='max-width: 600px; margin: 50px auto; text-align: center;'>
                <h2>댓글 수정 권한이 없습니다.</h2>
                <a href='javascript:history.back()' style='color: #003399; text-decoration: none; padding: 10px 20px; background-color: #f1f1f1; border-radius: 4px;'>뒤로가기</a>
              </div>";
        exit;
    }

    // 댓글 수정 처리
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edited_comment'])) {
        $editedComment = htmlspecialchars($_POST['edited_comment']);
        $updateStmt = $pdo->prepare("UPDATE comments SET content = ?, updated_at = NOW() WHERE id = ?");
        $updateStmt->execute([$editedComment, $commentId]);
        header("Location: QnA_detail.php?id=" . $comment['post_id']);
        exit;
    }
} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>댓글 수정</title>
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
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        form button {
            background-color: #003399;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #002266;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #d9534f;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
        }
        .back-button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="QnA_detail.php?id=<?= htmlspecialchars($comment['post_id']) ?>" class="back-button">← 돌아가기</a>
        <h2>댓글 수정</h2>
        <form method="POST">
            <textarea name="edited_comment" rows="5" required><?= htmlspecialchars($comment['content']) ?></textarea>
            <button type="submit">수정 완료</button>
        </form>
    </div>
</body>
</html>

