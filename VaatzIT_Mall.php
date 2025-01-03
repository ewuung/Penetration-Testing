<?php
// DB 연결
session_start();
require 'db.php';

// Check login status
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 세션에서 사용자 정보 설정
$user['MEM_ID'] = $_SESSION['user_id'];
$user['MEM_NAME'] = $_SESSION['username'];

// Fetch user points from MEMBERS table
try {
    $query = "SELECT MEM_POINT FROM MEMBERS WHERE MEM_ID = '" . $user['MEM_ID'] . "'";
    $result = $pdo->query($query);

    if ($result) {
        $user['MEM_POINT'] = $result->fetchColumn();
        if ($user['MEM_POINT'] !== false) {
            $_SESSION['user_point'] = $user['MEM_POINT']; // 세션에 포인트 저장
        } else {
            die("Error: User point not found.");
        }
    } else {
        die("Error: Query execution failed.");
    }
} catch (PDOException $e) {
    die("Error fetching user points: " . $e->getMessage());
}

// Fetch categories for display
try {
    $query = "SELECT PRO_ID, PRO_NAME, PRO_COST, PRO_IMG FROM PRODUCT";
    $result = $pdo->query($query);
    $categories = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching categories: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaatz Mall - Main</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            overflow-x: hidden;
        }
        header {
            background-color: white;
            color: #003399;
            padding-left: 11%;
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
        }
        .title_sub {
            font-weight: normal;
            color: rgb(1, 68, 202);
            font-size: 36px;
            font-family: 'Arial', sans-serif;
        }
        .welcome-section {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .welcome-section h2 {
            margin: 0 0 10px;
            color: #003399;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .card h3 {
            color: #003399;
            margin-bottom: 10px;
        }
        .card h4{
            color: #000000;
            margin-bottom: 5px;
        }
        .card a {
            text-decoration: none;
            color: rgb(1, 68, 202);
            font-weight: bold;
        }
        .card a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
    <h1>
        <a href="main.php" style="text-decoration: none; color: inherit;">
            <span class="title_main">현대오토에버</span>
            <span class="title_sub">VaatzIT Mall</span>  
        </a>
    </h1>
    </header>
    <div class="container">
        <div class="welcome-section">
            <h2>환영합니다, <?php echo $user['MEM_NAME']; ?>님!</h2>
            <p>보유 H캐시 : <?php echo number_format($user['MEM_POINT']); ?>원</p>
            <input type="hidden" id="user_point" value="<?php echo $user['MEM_POINT']; ?>">
            <a href="convert_cash.php" style="display: inline-block; margin-top: 10px; padding: 10px 20px; background-color: #003399; color: white; text-decoration: none; border-radius: 4px;">
        H캐시→현금 전환
            </a>
            <a href="convert_money.php" style="display: inline-block; margin-top: 10px; padding: 10px 20px; background-color: #003399; color: white; text-decoration: none; border-radius: 4px;">
        현금→H캐시 전환
            </a>
        </div>
        <h2>CATEGORY</h2>
        <div class="grid">
        <?php foreach ($categories as $category): ?>
            <div class="card">
                <img src="<?php echo $category['PRO_IMG']; ?>" 
                     alt="<?php echo $category['PRO_NAME']; ?>">
                <h3>
                    <a href="category_detail.php?category_id=<?php echo $category['PRO_ID']; ?>&user_point=<?php echo $user['MEM_POINT']; ?>">
                        <?php echo $category['PRO_NAME']; ?>
                    </a>
                </h3>
                <h4>
                    <?php echo number_format($category['PRO_COST']); ?>원
                </h4>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</body>
</html>