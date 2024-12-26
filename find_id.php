<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_option = $_POST['search_option'];

    if ($search_option === 'phone') {
        $phone_number = $_POST['phone_number'];

        $stmt = $pdo->prepare("SELECT * FROM MEMBERS WHERE MEM_PHONENUM = :phone_number");
        $stmt->execute(['phone_number' => $phone_number]);
    } elseif ($search_option === 'email') {
        $email = $_POST['email'];

        $stmt = $pdo->prepare("SELECT * FROM MEMBERS WHERE MEM_EMAIL = :email");
        $stmt->execute(['email' => $email]);
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $found_username = $user['username'];
    } else {
        $error = "입력하신 정보와 일치하는 아이디가 없습니다!";
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>현대오토에버 VaatzIT 아이디 찾기</title>
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
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #003399;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #003399;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #002266;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
        .small-hint {
            font-size: 12px;
            color: gray;
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
            <span class="title_main">현대오토에버</span>
            <span class="title_sub">VaatzIT</span>
        </h1>
    </header>
    <div class="container">
        <h2>Search ID 아이디 찾기</h2>
        <p>아래 정보를 입력하시면 본인 확인을 거쳐 아이디를 찾아 드립니다.<br><br>옵션을 선택하고 정보를 입력해주세요.</p>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php elseif (!empty($found_username)): ?>
            <p class="success-message">등록된 정보에 해당하는 아이디는 <strong><?php echo $found_username; ?></strong> 입니다.</p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <select id="search_option" name="search_option" onchange="toggleFields()" required>
                    <option value="">옵션을 선택하세요</option>
                    <option value="phone">휴대폰 번호로 찾기</option>
                    <option value="email">E-mail로 찾기</option>
                </select>
            </div>
            <div class="form-group" id="name-group" style="display: none;">
                <label for="full_name">성명</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group" id="phone-group" style="display: none;">
                <label for="phone_number">휴대폰</label>
                <input type="text" id="phone_number" name="phone_number">
                <p class="small-hint">예) 01012345678로 '-' 제외하고 입력</p>
            </div>
            <div class="form-group" id="email-group" style="display: none;">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email">
                <p class="small-hint">예) ***@hyundai.com 등의 형식으로 전체 입력</p>
            </div>
            <button type="submit">아이디 찾기</button>
        </form>
    </div>
    <footer>
        <p>COPYRIGHT 2019 HYUNDAI AUTOEVER CORP. ALL RIGHTS RESERVED.</p>
    </footer>

    <script>
    function toggleFields() {
        const searchOption = document.getElementById('search_option').value;
        const groups = document.querySelectorAll('#name-group, #phone-group, #email-group');

        // 모든 그룹을 숨김
        groups.forEach(group => {
            group.style.display = 'none';
        });

        // 선택된 옵션에 따라 관련 그룹만 표시
        if (searchOption === 'phone') {
            document.getElementById('phone-group').style.display = 'block';
            document.getElementById('name-group').style.display = 'block';
        } else if (searchOption === 'email') {
            document.getElementById('email-group').style.display = 'block';
            document.getElementById('name-group').style.display = 'block';
        }
    }
</script>

</body>
</html>
