<?php
require '../db.php';

// Q&A ID 확인
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$id = (int)$_GET['id'];

// Q&A 조회
try {
    $stmt = $pdo->prepare("SELECT * FROM board WHERE id = ?");
    $stmt->execute([$id]);
    $qa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$qa) {
        die("Q&A가 존재하지 않습니다.");
    }
} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}

// 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $phone = trim($_POST['phone']);
    $is_private = isset($_POST['is_private']) ? 1 : 0;
    $password = $is_private && !empty($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_BCRYPT) : $qa['password'];
    $file_path = $qa['file_path'];

    if (empty($title) || empty($content)) {
        $error_message = "제목과 내용을 모두 입력해주세요.";
    } elseif ($is_private && empty($_POST['password']) && !$qa['password']) {
        $error_message = "비밀글로 설정할 경우 비밀번호를 입력해주세요.";
    } else {
        // 첨부파일 처리
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = basename($_FILES['attachment']['name']);
            $target_path = $upload_dir . time() . '_' . $file_name;

            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_path)) {
                $file_path = $target_path;
            } else {
                $error_message = "파일 업로드 중 오류가 발생했습니다.";
            }
        }

        if (empty($error_message)) {
            try {
                $stmt = $pdo->prepare("
                    UPDATE board
                    SET title = ?, content = ?, phone = ?, is_private = ?, password = ?, file_path = ?
                    WHERE id = ?
                ");
                $stmt->execute([$title, $content, $phone, $is_private, $password, $file_path, $id]);
                header("Location: board.php");
                exit;
            } catch (PDOException $e) {
                $error_message = "오류 발생: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Q&A 수정</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        .form-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }
        .form-container label {
            display: block;
            margin: 10px 0 5px;
            font-size: 14px;
            color: #555;
        }
        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #0066ff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056d2;
        }
        .form-container .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-container .file-info {
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Q&A 수정</h1>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($qa['title']) ?>" required>

            <label for="content">내용</label>
            <textarea id="content" name="content" rows="8" required><?= htmlspecialchars($qa['content']) ?></textarea>

            <label for="phone">전화번호</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($qa['phone']) ?>">

            <label for="is_private">비밀글</label>
            <input type="checkbox" id="is_private" name="is_private" <?= $qa['is_private'] ? 'checked' : '' ?> onchange="togglePasswordField(this)">

            <div id="password-field" style="<?= $qa['is_private'] ? 'block' : 'none' ?>">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password" placeholder="비밀번호를 입력하세요">
            </div>

            <label for="attachment">첨부파일</label>
            <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
            <p class="file-info">현재 파일: <?= $qa['file_path'] ? "<a href='" . htmlspecialchars($qa['file_path']) . "' download>다운로드</a>" : "없음" ?></p>

            <button type="submit">수정</button>
        </form>
    </div>

    <script>
        function togglePasswordField(checkbox) {
            const passwordField = document.getElementById('password-field');
            passwordField.style.display = checkbox.checked ? 'block' : 'none';
        }
    </script>
</body>
</html>
