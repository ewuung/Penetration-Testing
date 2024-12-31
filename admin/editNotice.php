<?php
require '../db.php';

// 공지사항 ID 확인
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$id = (int)$_GET['id'];

// 공지사항 조회
try {
    $stmt = $pdo->prepare("SELECT * FROM notice WHERE id = ?");
    $stmt->execute([$id]);
    $notice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$notice) {
        die("공지사항이 존재하지 않습니다.");
    }
} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}

// 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error_message = "제목과 내용을 모두 입력해주세요.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE notice SET title = ?, content = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$title, $content, $id]);
            header("Location: board.php");
            exit;
        } catch (PDOException $e) {
            $error_message = "오류 발생: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 수정</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        .form-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
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
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>공지사항 수정</h1>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($notice['title']) ?>" required>

            <label for="content">내용</label>
            <textarea id="content" name="content" rows="8" required><?= htmlspecialchars($notice['content']) ?></textarea>

            <button type="submit">수정</button>
        </form>
    </div>
</body>
</html>
