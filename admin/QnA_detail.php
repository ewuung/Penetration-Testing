<?php
require '../db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$id = (int)$_GET['id'];

try {
    // Q&A 데이터 조회
    $stmt = $pdo->prepare("SELECT * FROM board WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        die("Q&A가 존재하지 않습니다.");
    }

    // 댓글 데이터 조회
    $commentStmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at ASC");
    $commentStmt->execute([$id]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

    // 댓글 삭제 처리
    if (isset($_POST['delete_comment_id'])) {
        $deleteCommentId = (int)$_POST['delete_comment_id'];
        $deleteStmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $deleteStmt->execute([$deleteCommentId]);
        header("Location: QnA_detail.php?id=$id");
        exit();
    }

    // 댓글 수정 처리
    if (isset($_POST['edit_comment_id'])) {
        $editCommentId = (int)$_POST['edit_comment_id'];
        $newContent = trim($_POST['content']);
        if (!empty($newContent)) {
            $editStmt = $pdo->prepare("
                UPDATE comments 
                SET content = ?, updated_at = NOW(), MEM_ID = 'superadmin' 
                WHERE id = ?
            ");
            $editStmt->execute([$newContent, $editCommentId]);
            header("Location: QnA_detail.php?id=$id");
            exit();
        }
    }

    // 댓글 작성 처리
    if (isset($_POST['new_comment'])) {
        $content = trim($_POST['new_comment']);
        if (!empty($content)) {
            $addStmt = $pdo->prepare("
                INSERT INTO comments (post_id, content, MEM_ID, created_at) 
                VALUES (?, ?, 'superadmin', NOW())
            ");
            $addStmt->execute([$id, $content]);
            header("Location: QnA_detail.php?id=$id");
            exit();
        }
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
    <title>Q&A 상세 보기</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #003399;
        }
        .content {
            margin-top: 20px;
            line-height: 1.6;
        }
        .attachment {
            margin-top: 20px;
        }
        .attachment a {
            color: #0066ff;
            text-decoration: none;
        }
        .attachment a:hover {
            text-decoration: underline;
        }
        .comments-section {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .comment {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .comment p {
            margin: 0;
        }
        .comment-actions {
            margin-top: 10px;
            text-align: right;
        }
        .comment-actions button {
            background-color: #0066ff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .comment-actions button.delete {
            background-color: #d9534f;
        }
        .comment-actions button:hover {
            opacity: 0.9;
        }
        .comment-form {
            margin-top: 20px;
        }
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .comment-form button {
            background-color: #0066ff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .comment-form button:hover {
            background-color: #0056d2;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #003399;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-button:hover {
            background-color: #002266;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p><strong>작성자:</strong> <?= htmlspecialchars($post['MEM_ID']) ?></p>
        <p><strong>작성일:</strong> <?= htmlspecialchars($post['created_at']) ?></p>
        <div class="content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>

        <!-- 첨부파일 -->
        <?php if ($post['file_path']): ?>
            <div class="attachment">
                <strong>첨부 파일:</strong>
                <a href="<?= htmlspecialchars($post['file_path']) ?>" download>다운로드</a>
            </div>
        <?php endif; ?>

        <!-- 댓글 섹션 -->
        <div class="comments-section">
            <h2>댓글</h2>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                    <p style="font-size: 12px; color: #888;">
                        작성자: <?= htmlspecialchars($comment['MEM_ID']) ?><br>
                        <?= $comment['updated_at'] ? "수정일: " . htmlspecialchars($comment['updated_at']) : "작성일: " . htmlspecialchars($comment['created_at']) ?>
                    </p>
                    <div class="comment-actions">
                        <!-- 댓글 수정 -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="edit_comment_id" value="<?= $comment['id'] ?>">
                            <textarea name="content" rows="2" required><?= htmlspecialchars($comment['content']) ?></textarea>
                            <button type="submit">수정</button>
                        </form>

                        <!-- 댓글 삭제 -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="delete_comment_id" value="<?= $comment['id'] ?>">
                            <button type="submit" class="delete">삭제</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- 댓글 작성 -->
            <form method="POST" class="comment-form">
                <textarea name="new_comment" rows="3" placeholder="댓글을 입력하세요" required></textarea>
                <button type="submit">댓글 작성</button>
            </form>
        </div>

        <a href="board.php" class="back-button">← 뒤로가기</a>
    </div>
</body>
</html>
