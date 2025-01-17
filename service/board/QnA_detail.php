<?php
require '../../db.php';
session_start();

//(추가) 변수 세션 절대권한임
if (!isset($_SESSION['authenticated'])) {
    $_SESSION['authenticated'] = false;
}
// $_SESSION['authenticated'] = true;
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$id = (int)$_GET['id'];

//test
// echo "<pre>전역 인증 상태: " . ($_SESSION['authenticated'] ? "활성화됨" : "비활성화됨") . "</pre>";
// echo "<pre>글별 인증 상태: " . print_r($_SESSION['authenticated_posts'], true) . "</pre>";

try {
    // 데이터 조회
    $stmt = $pdo->prepare("SELECT * FROM board WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        die("존재하지 않는 글입니다.");
    }

    // 비밀글 확인
    if ($post['is_private'] == 1) {
        // 전역 인증 상태 확인
        if ($_SESSION['authenticated'] !== true) {
            // 글별 인증 확인
            if (!isset($_SESSION['authenticated_posts'][$id]) || $_SESSION['authenticated_posts'][$id] !== true) {
                if (!isset($_POST['password'])) {
                    echo "<form method='POST'>
                            <div style='max-width: 600px; margin: 50px auto; text-align: center;'>
                                <h2>비밀글입니다</h2>
                                <p>비밀번호를 입력해주세요</p>
                                <input type='password' name='password' required style='padding: 10px; width: 80%; margin-bottom: 10px;'/>
                                <br/>
                                <button type='submit' style='padding: 10px 20px; background-color: #003399; color: white; border: none; border-radius: 4px; cursor: pointer;'>확인</button>
                            </div>
                          </form>";
                    exit;
                }

                if (password_verify($_POST['password'], $post['password'])) {
                    $_SESSION['authenticated'] = true; // 전역 인증 활성화
                    // $_SESSION['authenticated_posts'][$id] = true; // 개별 인증 저장
                } else {
                    die("<div style='max-width: 600px; margin: 50px auto; text-align: center;'>
                            <h2>비밀번호가 틀렸습니다</h2>
                            <a href='QnA_board.php' style='color: #003399; text-decoration: none;'>뒤로가기</a>
                         </div>");
                }
            }
        } else {
            // 전역 인증 활성화 시 글별 인증 자동 통과
            $_SESSION['authenticated_posts'][$id] = true;
        }
    }

    //test용 echo 
    // echo "<pre>인증 후 글별 인증 상태: " . print_r($_SESSION['authenticated_posts'], true) . "</pre>";

    // 댓글 조회
    $commentStmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at ASC");
    $commentStmt->execute([$id]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

    // 댓글 삽입 처리
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
        $comment = $_POST['comment']; // 필터링 없이 입력값 그대로 사용
        $mem_id = $_SESSION['user_id'] ?? '익명';

        $commentStmt = $pdo->prepare("
            INSERT INTO comments (post_id, MEM_ID, content, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $commentStmt->execute([$id, $mem_id, $comment]);
        header("Location: QnA_detail.php?id=$id");
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
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #003399;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-button:hover {
            background-color: #002266;
        }
        h2 {
            color: #003399;
            margin-bottom: 20px;
        }
        .content {
            margin-top: 20px;
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
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .comment-form button {
            background-color: #003399;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .comment-form button:hover {
            background-color: #002266;
        }
        .edit-button {
            font-size: 12px;
            color: #003399;
            text-decoration: none;
            margin-left: 10px;
        }
        .edit-button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="QnA_board.php?reset_auth=true" class="back-button">← 뒤로가기</a>
        <h2><?= $post['title'] ?></h2>
        <p><strong>작성자:</strong> <?= $post['MEM_ID'] ?></p>
        <p><strong>작성일:</strong> <?= $post['created_at'] ?></p>
        <div class="content">
            <p><?= nl2br($post['content']) ?></p>
        </div>

        <?php if ($post['file_path']): ?>
            <?php $fileName = basename($post['file_path']); ?>
            <p><strong>첨부 파일:</strong> <a href="<?= $post['file_path'] ?>" download="<?= $fileName ?>"><?= $fileName ?></a></p>
        <?php endif; ?>

        <!-- 글 수정 삭제 버튼 -->
        <div style="margin-top: 20px;">
            <a href="QnA_edit.php?id=<?= $id ?>" style="padding: 10px 20px; background-color: #003399; color: white; text-decoration: none; border-radius: 4px;">글 수정</a>
            <a href="QnA_delete.php?id=<?= $id ?>" style="padding: 10px 20px; background-color: #d9534f; color: white; text-decoration: none; border-radius: 4px;" onclick="return confirm('정말로 삭제하시겠습니까?');">글 삭제</a>
        </div>

        <div class="comments-section">
            <h3>댓글</h3>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><?= nl2br($comment['content']) ?></p>
                    <p style="text-align: right; font-size: 12px; color: #888;">
                        작성자: <?= $comment['MEM_ID'] ?><br>
                        <?= $comment['updated_at'] ? "수정일: " . $comment['updated_at'] : "작성일: " . $comment['created_at'] ?>
                    </p>
                    <!-- 댓글 수정 및 삭제 버튼 -->
                    <div style="text-align: right; margin-top: 10px;">
                        <a href="QnA_edit_comment.php?id=<?= $comment['id'] ?>" class="edit-button">✏️ 수정</a>
                        <a href="QnA_delete_comment.php?id=<?= $comment['id'] ?>" class="edit-button" style="color: #d9534f;" onclick="return confirm('정말로 삭제하시겠습니까?');">🗑 삭제</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <form method="POST" class="comment-form">
                <textarea name="comment" rows="3" placeholder="댓글을 입력하세요" required></textarea>
                <button type="submit">댓글 작성</button>
            </form>
        </div>
    </div>
</body>

</html>

