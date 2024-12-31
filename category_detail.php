<?php
session_start();

// Database connection
require 'db.php';

// Check login status
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Mock session data for testing without login
$user['MEM_ID'] = $_SESSION['user_id'];
$user['MEM_NAME'] = $_SESSION['username'];

$user_point = isset($_GET['user_point']) ? $_GET['user_point'] : 0;
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Fetch category details from database
try {
    // 제품 정보 가져오기
    $query = "SELECT PRO_ID, PRO_NAME, PRO_COST, PRO_IMG, PRO_DESC FROM PRODUCT WHERE PRO_ID = $category_id";
    $result = $pdo->query($query);
    $category = $result->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}

// If category not found
if (!$category) {
    echo "Category not found.";
    exit;
}

// Handle purchase request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $purchase_num = isset($_POST['purchase_num']) ? (int)$_POST['purchase_num'] : 0;
    $user_id = $user['MEM_ID'];
    $purchase_date = date('Y-m-d H:i:s');

    // Validate purchase quantity
    if ($purchase_num <= 0) {
        echo "Invalid purchase quantity.";
        exit;
    }

    try {
        // Insert purchase into PURCHASE table
        $query = "INSERT INTO PURCHASE (PU_ID, PU_NUM, PU_DATE) VALUES ('$user_id', $purchase_num, '$purchase_date')";
        $pdo->query($query);
    
        echo "<script>alert('구매가 완료되었습니다.');</script>";
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit;
    }
    
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category['PRO_NAME']; ?> Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
        .container {
            max-width: 1200px;
            width: 95%;
            margin: 0 auto;
            padding: 20px;
            flex: 1;
            display: flex; 
            justify-content: center;
        }
        .detail {
            display: flex;
            justify-content: space-between;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            height: 100%
            box-sizing: border-box;
        }
        .detail img {
            width: 40%; 
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            margin-right: 60px;
        }
        .detail div {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .detail h2 {
            color: #003399;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .detail h3 {
            color: #000000;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .input-section {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }
        .input-section input {
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            width: 100px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .input-section button {
            padding: 10px;
            background-color: #003399;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 50%;
        }
        .input-section button:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #003399;
            color: white;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* 배경 어두운 색 */
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        </style>
</head>
<body>
    <header>
        <h1>
            <a href="VaatzIT_Mall.php" style="text-decoration: none; color: inherit;">
                <span class="title_main">현대오토에버</span>
              <span class="title_sub">VaatzIT Mall</span>
            </a>  
        </h1>
    </header>
    <div class="container">
        <div class="detail">
            <img src="<?php echo $category['PRO_IMG']; ?>" alt="<?php echo $category['PRO_NAME']; ?>">
            <div>
                <h2>제품 품명: <?php echo $category['PRO_NAME']; ?></h2>
                <h3>제품 가격: <?php echo $category['PRO_COST']; ?></h3>
                <h3>상세 설명: </h3>
                <p><?php echo $category['PRO_DESC'] ?? '설명이 없습니다.'; ?></p>
                <form method="POST" action="purchase.php"> 
                    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                    <input type="hidden" name="user_point" value="<?php echo $_SESSION['user_points']; ?>">
                    <input type="hidden" name="pro_cost" value="<?php echo $category['PRO_COST']; ?>">              
                    <div class="input-section">
                        <label for="purchase_num">구매 개수:</label>
                        <input type="number" id="purchase_num" name="purchase_num" value="1" min="1">
                        <button type="button" onclick="openPopup(<?php echo $category['PRO_ID']; ?>)">구매하기</button>
                        <script>
                        function openPopup(category_id) {
                            var purchase_num = document.getElementById('purchase_num').value;
                            if (purchase_num <= 0) {
                                alert("구매 개수를 입력하세요.");
                                return;
                            }
                            var url = 'purchase.php?category_id=' + category_id + '&purchase_num=' + purchase_num;
                            var windowName = 'purchasePopup';
                            var windowFeatures = 'width=600,height=400,resizable=yes,scrollbars=yes';
                            window.open(url, windowName, windowFeatures);
                        }
                        </script>
                    </div>
                </form>       
            </div>
        </div>
    </div>
    <footer>
        <p>COPYRIGHT 2019 HYUNDAI AUTOEVER CORP. ALL RIGHTS RESERVED.</p>
    </footer>
</body>
</html>