<?php
session_start();
require_once 'db_connect.php';

// Fetch user data if ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM members WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<script>
                alert('사용자를 찾을 수 없습니다.');
                window.location.href = 'admin_board.php';
              </script>";
        exit();
    }
} else {
    header("Location: admin_board.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사용자 정보 수정 - 현대오토에버 VaatzIT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>사용자 정보 수정</h2>
        <form method="POST" action="admin_board.php">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <input type="hidden" name="update" value="1">
            
            <div class="form-group">
                <label>사용자 ID</label>
                <input type="text" name="mem_id" value="<?php echo htmlspecialchars($user['mem_id']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>비밀번호</label>
                <input type="password" name="mem_pw" placeholder="변경하려면 새 비밀번호를 입력하세요">
            </div>
            
            <div class="form-group">
                <label>회사 ID</label>
                <input type="text" name="com_id" value="<?php echo htmlspecialchars($user['com_id']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>팀</label>
                <input type="text" name="team" value="<?php echo htmlspecialchars($user['team']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>이름</label>
                <input type="text" name="mem_name" value="<?php echo htmlspecialchars($user['mem_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>전화번호</label>
                <input type="tel" name="mem_phonnum" value="<?php echo htmlspecialchars($user['mem_phonnum']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>이메일</label>
                <input type="email" name="mem_email" value="<?php echo htmlspecialchars($user['mem_email']); ?>" required>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">저장</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='admin_board.php'">취소</button>
            </div>
        </form>
    </div>
</body>
</html>