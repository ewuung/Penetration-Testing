<?php
require '../../db.php'; // 데이터베이스 연결

// 공지사항 ID 확인
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$id = (int)$_GET['id'];

try {
    // 공지사항 데이터 조회
    $stmt = $pdo->prepare("SELECT * FROM notice WHERE id = ?");
    $stmt->execute([$id]);
    $notice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$notice) {
        die("존재하지 않는 공지사항입니다.");
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
    <title>공지사항 상세 보기</title>
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
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #003399;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-button:hover {
            background-color: #002266;
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
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="notice.php" class="back-button">← 목록으로</a>
        <h1><?= $notice['title'] ?></h1>
        <p class="footer">작성일: <?= $notice['created_at'] ?></p>
        <div class="content">
            <?= nl2br($notice['content']) ?>
        </div>

        <!-- 첨부파일 다운로드 -->
        <?php if (!empty($notice['file_path'])): ?>
            <div class="attachment">
                <strong>첨부파일:</strong>
                <a href="<?= $notice['file_path'] ?>" download>
                    <?= basename($notice['file_path']) ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
