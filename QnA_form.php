<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect_to=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$logged_in_user = $_SESSION['user_id']; // 로그인된 사용자 MEM_ID
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1:1 문의하기</title>
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
        }
        .title_sub {
            font-weight: normal;
            color: rgb(1, 68, 202);
            font-size: 36px;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #003399;
            text-align: center;
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        form button {
            background-color: #003399;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        form button:hover {
            background-color: #002266;
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
</head>
<body>
    <header>
        <h1>
            <span class="title_main" onclick="location.href='main.php'" style="cursor: pointer;">현대오토에버</span> 
            <span class="title_sub">Q&A 작성</span>
        </h1>
    </header>
    <div class="container">
        <h2>문의 내용을 입력해주세요</h2>
        <form action="QnA_submit.php" method="POST" enctype="multipart/form-data">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" required>

            <label for="author">작성자</label>
            <!-- 로그인된 사용자의 MEM_ID를 표시 -->
            <input type="text" id="author" name="author" value="<?= htmlspecialchars($logged_in_user) ?>" readonly>

            <label for="email">이메일</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">전화번호</label>
            <input type="text" id="phone" name="phone">

            <label for="message">내용</label>
            <textarea id="message" name="content" rows="5" required></textarea>

            <label>
                <input type="checkbox" id="is_private" name="is_private" value="1" onclick="togglePasswordField()"> 비밀글로 설정
            </label>

            <div id="password-section" style="display: none;">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password" minlength="4" placeholder="비밀번호 (최소 4자리)">
            </div>

            <label for="file">파일 업로드</label>
            <input type="file" id="file" name="uploaded_file">

            <button type="submit">문의하기</button>
        </form>

        <script>
            function togglePasswordField() {
                const isChecked = document.getElementById('is_private').checked;
                const passwordSection = document.getElementById('password-section');
                passwordSection.style.display = isChecked ? 'block' : 'none';
            }
        </script>
    </div>
</body>
</html>