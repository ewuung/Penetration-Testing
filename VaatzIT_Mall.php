<?php
// DB 연결
session_start();
require 'db.php';

// Check login status
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user['MEM_ID'] = $_SESSION['user_id'];
$user['MEM_NAME'] = $_SESSION['username'];

// Fetch user points from MEMBERS table
try {
    $stmt = $pdo->prepare("SELECT MEM_POINT FROM MEMBERS WHERE MEM_ID = :mem_id");
    $stmt->bindParam(':mem_id', $user['MEM_ID'], PDO::PARAM_STR);
    $stmt->execute();
    $user['MEM_POINT'] = $stmt->fetchColumn();
} catch (PDOException $e) {
    die("Error fetching user points: " . $e->getMessage());
}

// Fetch categories for display
try {
    $stmt = $pdo->prepare("SELECT PRO_ID, PRO_NAME, PRO_COST, PRO_IMG FROM PRODUCT");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        footer {
            background-color: #003399;
            color: white;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
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
            <p>POINT : <?php echo $user['MEM_POINT']; ?>점</p>
        </div>
        <h2>CATEGORY</h2>
        <div class="grid">
        <?php foreach ($categories as $category): ?>
            <div class="card">
                    <img src="<?php echo $category['PRO_IMG']; ?>" alt="<?php echo $category['PRO_NAME']; ?>">
                    <h3><a href="category_detail.php?category_id=<?php echo $category['PRO_ID']; ?>">
                        <?php echo $category['PRO_NAME']; ?>
                    </a></h3>
                    <h4>
                        <?php echo $category['PRO_COST']; ?>원
                    </h4>
                </div>
        <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>COPYRIGHT 2019 HYUNDAI AUTOEVER CORP. ALL RIGHTS RESERVED.</p>
    </footer>
</body>
</html>
