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

    // 수정 처리
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $stmt = $pdo->prepare("UPDATE board SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);
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
    <title>글 수정</title>
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
        }
        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        form button {
            margin-top: 20px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>글 수정</h2>
        <form method="POST">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

            <label for="content">내용</label>
            <textarea id="content" name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>

            <button type="submit">수정하기</button>
        </form>
    </div>
</body>
</html>

