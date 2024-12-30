<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>고객센터 - 현대오토에버</title>
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
            text-align: left;
            border-bottom: 4px solid #003399;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            font-size: 20px;
        }
        header a {
            color: #003399;
            text-decoration: none;
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
            margin: 20px auto;
            display: flex;
            gap: 20px;
        }
        .sidebar {
            width: 30%;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .sidebar button {
            width: 100%;
            padding: 15px;
            margin-bottom: 10px;
            font-size: 16px;
            background-color: #003399;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: left;
        }
        .sidebar button:hover {
            background-color: #002266;
        }
        .content {
            flex: 1;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .content h1 {
            font-size: 24px;
            color: #003399;
            margin-bottom: 20px;
        }
        .content ul {
            list-style: none;
            padding: 0;
        }
        .content ul li {
            margin-bottom: 15px;
        }
        .content ul li span {
            font-weight: bold;
            color: #003399;
        }
        footer {
            background-color: #003399;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
<header>
    <a href="main.php">
        <span class="title_main">현대오토에버</span>
        <span class="title_sub">고객센터</span>
    </a>
</header>
<div class="container">
    <div class="sidebar">
        <button onclick="location.href='notice.php'">공지사항</button>
        <button onclick="location.href='QnA.php'">FAQ 및 Q&A</button>
        <button onclick="location.href='member_guide.php'">회원사 가입안내</button>
    </div>
    <div class="content">
        <h1>Vaatz 고객지원센터입니다.</h1>
        <p>고객을 소중히 생각하는 Vaatz가 되겠습니다.</p>
        <ul>
            <li><span>마켓플레이스 구매관련 문의</span></li>
            <li>통합구매1팀 권째즈 책임매니저 (kwonjjazz@hyundai.com, 02-6516-0000)</li>
            <li><span>VAATZ카드 가입 및 결제관련 문의</span></li>
            <li>고객상담센터 5577-0000</li>
            <li><span>디지털 세금계산서 문의</span></li>
            <li>jz-bank 콜센터 5588-0000</li>
            <li><span>공동인증 문의처</span></li>
            <li>디지털 세금계산서 (현대, 기아 e-pay) 인증서 문의 5588-0000</li>
        </ul>
    </div>
</div>

</body>
</html>
