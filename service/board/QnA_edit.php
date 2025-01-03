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

    // 인증 여부 확인
    if (!isset($_SESSION['authenticated'])) {
        $_SESSION['authenticated'] = false;
    }

    if (!$_SESSION['authenticated']) {
        if ($post['MEM_ID'] !== $_SESSION['user_id']) {
            die("<div style='max-width: 600px; margin: 50px auto; text-align: center;'>
                    <h2>권한이 없습니다.</h2>
                    <a href='QnA_board.php' style='color: #003399; text-decoration: none;'>뒤로가기</a>
                 </div>");
        }
        $_SESSION['authenticated'] = true;
    }

    // 수정 처리
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $is_private = isset($_POST['is_private']) ? 1 : 0;
        $password = $is_private && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $post['password'];

        // 파일 처리
        $file_path = $post['file_path'];
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

        // 데이터 업데이트
        $stmt = $pdo->prepare("
            UPDATE board
            SET title = ?, content = ?, is_private = ?, password = ?, file_path = ?
            WHERE id = ?
        ");
        $stmt->execute([$title, $content, $is_private, $password, $file_path, $id]);

        $_SESSION['authenticated'] = false;

        echo "<script>
            alert('글이 성공적으로 수정되었습니다.');
            location.href = 'QnA_detail.php?id=$id';
        </script>";
        exit;
    }
} catch (Exception $e) {

    $_SESSION['authenticated'] = false;
    echo "<script>
        alert('오류 발생: {$e->getMessage()}');
        history.back();
    </script>";
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
        .back-button {
            margin-top: 20px;
            background-color: #888;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>글 수정</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

            <label for="content">내용</label>
            <textarea id="content" name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>

            <label>
                <input type="checkbox" id="is_private" name="is_private" value="1" <?= $post['is_private'] ? 'checked' : '' ?> onclick="togglePasswordField()"> 비밀글로 설정
            </label>

            <div id="password-section" style="<?= $post['is_private'] ? 'block' : 'none' ?>">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password" minlength="4" placeholder="비밀번호 (최소 4자리)">
            </div>

            <label for="uploaded_file">첨부파일</label>
            <?php if ($post['file_path']): ?>
                <p>기존 파일: <?= htmlspecialchars($post['file_path']) ?></p>
            <?php endif; ?>
            <input type="file" id="uploaded_file" name="uploaded_file">

            <button type="submit">수정하기</button>
            
        </form>
        <button class="back-button" onclick="goBack()">뒤로가기</button>

        <script>
            function togglePasswordField() {
                const isChecked = document.getElementById('is_private').checked;
                const passwordSection = document.getElementById('password-section');
                passwordSection.style.display = isChecked ? 'block' : 'none';
            }
            function goBack() {
            // 뒤로가기 경로
            window.location.href = 'QnA_board.php';
            }
        </script>
    </div>
</body>
</html>
