<?php
// 로그인 상태 확인
session_start();
if (isset($_SESSION['user_id'])) {
    // 로그인이 되어있는 경우, 로그인 후 페이지(home.php)로 리다이렉트
    header("Location: home.php");
    exit();
}

// 사용자 정보 (세션에서 가져오기)
$user['MEM_ID'] = $_SESSION['user_id'];
$user['MEM_NAME'] = $_SESSION['username']; // 로그인 시 저장된 사용자 이름
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>현대오토에버 VaatzIT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: white;
            color:  #003399;
            padding-left: 11%;
            text-align: left;
            border-bottom: 4px solid #003399;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            font-size: 20px;
        }
        .title_main{
            font-weight: bold;
            color: #003399;
            font-size: 36px;
            font-family: 'Arial', sans-serif;
        }
        .title_sub{
            font-weight: normal;
            color:rgb(1, 68, 202);
            font-size: 36px;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .login-section {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .login-section button {
            background-color: #003399;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-section button:hover {
            background-color: #002266;
        }
        .login-links {
            margin-top: 10px;
            font-size: 14px;
        }
        .login-links a {
            color: #003399;
            text-decoration: none;
            margin: 0 10px;
        }
        .login-links a:hover {
            text-decoration: underline;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background-color: white;
            color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            z-index: 1; 
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('https://t4.ftcdn.net/jpg/09/24/39/59/240_F_924395993_QpPRBVyawzTWUJ5sfai5wIn0qVlTJidd.jpg'); 
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            z-index: -1; 
        }

        .card a {
            text-decoration: none;
            color:rgb(190, 210, 252);
            font-weight: bold;
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
        <a href="main.php" class="title_main" style="text-decoration: none; color: inherit;">
            <span class="title_main">현대오토에버</span>
        </a>
        <span class="title_sub">VaatzIT</span>
    </h1>
</header>
    <div class="container">
        <!-- 로그인 섹션 -->
        <div class="login-section">
            <button onclick="location.href='login.php'">로그인</button>
            <div class="login-links">
                <a href="find_id.php">ID 찾기</a> | 
                <a href="find_password.php">비밀번호 찾기</a>
            </div>
        </div>
        <!-- 메인 콘텐츠 -->
        <div class="grid">
            <div class="card">
                <h2>고객센터</h2>
                <a href="#">자세히 보기</a>
            </div>
            <div class="card">
                <h2>고객담당자 등록</h2>
                <a href="registCustomerUser.php">회원가입</a>
            </div>
            <div class="card">
                <h2>VaatzIT Mall</h2>
                <a href="VaatzIT_Mall.php">접속하기</a>
            </div>
            <div class="card">
                <h2>4대 실천사항</h2>
                <a href="#">자세히 보기</a>
            </div>
            <div class="card">
                <h2>동반성장</h2>
                <a href="#">자세히 보기</a>
            </div>
            <div class="card">
                <h2>공정 거래</h2>
                <a href="#">자세히 보기</a>
            </div>
        </div>
    </div>
    <footer>
        <p>COPYRIGHT 2019 HYUNDAI AUTOEVER CORP. ALL RIGHTS RESERVED.</p>
    </footer>
</body>
</html>
