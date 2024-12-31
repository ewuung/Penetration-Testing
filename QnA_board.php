<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect_to=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Q&A 게시판</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: white;
            color: #003399;
            padding: 20px;
            padding-left: 160px;
            text-align: left;
            border-bottom: 4px solid #003399;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            font-size: 20px;
        }
        .title_main {
            font-weight: bold;
            color: #003399;
            font-size: 36px;
            font-family: 'Arial', sans-serif;
            cursor: pointer;
        }
        .title_sub {
            font-weight: normal;
            color: rgb(1, 68, 202);
            font-size: 36px;
            font-family: 'Arial', sans-serif;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .board-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .board-header button {
            background-color: #003399;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .board-header button:hover {
            background-color: #002266;
        }
        .board-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .board-table th, .board-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .board-table th {
            background-color: #f1f1f1;
            color: #003399;
        }
        .board-table tr:hover {
            background-color: #f9f9f9;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            text-decoration: none;
            color: #003399;
            padding: 10px 15px;
            border: 1px solid #ddd;
            margin: 0 5px;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #f1f1f1;
        }
        .pagination .active {
            font-weight: bold;
            background-color: #003399;
            color: white;
        }
        footer {
            background-color: #003399;
            color: white;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>
            <span class="title_main" onclick="location.href='main.php'">현대오토에버</span> 
            <span class="title_sub">Q&A 게시판</span>
        </h1>
    </header>
    <div class="container">
        <div class="board-header">
            <h2>Q&A 목록</h2>
            <form method="GET" style="display: flex; align-items: center;">
                <input type="text" name="search" placeholder="검색어를 입력하세요" style="padding: 5px; margin-right: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <button type="submit" style="padding: 5px 10px; background-color: #003399; color: white; border: none; border-radius: 4px;">검색</button>
            </form>
            <button onclick="location.href='QnA_form.php'">글 작성</button>
        </div>
        <table class="board-table">
    <thead>
        <tr>
            <th>번호</th>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require 'db.php';

        // 페이지네이션 설정
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 2;
        $offset = ($page - 1) * $limit;

        // 검색 조건 추가
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // SQL Injection이 가능하도록 쿼리 작성
        $sql = "
            SELECT id, title, created_at, MEM_ID, is_private
            FROM board
            WHERE title LIKE '%$search%'
            ORDER BY created_at DESC
            LIMIT $offset, $limit
        ";

        // 전체 게시글 수 확인 쿼리
        $totalSql = "
            SELECT COUNT(*) FROM board
            WHERE board.title LIKE '%$search%'
        ";


        try {
            // 전체 게시글 수 계산
            $totalStmt = $pdo->query($totalSql);
            $totalRows = $totalStmt->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            // 게시글 조회 실행
            $stmt = $pdo->query($sql);
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post['id']) ?></td>
                    <td>
                        <?php if ($post['is_private']): ?>
                            <a href="QnA_detail.php?id=<?= htmlspecialchars($post['id']) ?>">
                                <span>🔒</span> <?= htmlspecialchars($post['title']) ?>
                            </a>
                        <?php else: ?>
                            <a href="QnA_detail.php?id=<?= htmlspecialchars($post['id']) ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($post['MEM_ID']) ?></td>
                    <td><?= htmlspecialchars($post['created_at']) ?></td>
                </tr>
            <?php endforeach;
        } catch (PDOException $e) {
            echo "<tr><td colspan='4'>오류 발생: {$e->getMessage()}</td></tr>";
        }
        ?>
    </tbody>
</table>

        <!-- 페이지네이션 -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">« 이전</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">다음 »</a>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>COPYRIGHT 2019 HYUNDAI AUTOEVER CORP. ALL RIGHTS RESERVED.</p>
    </footer>
</body>
</html>
