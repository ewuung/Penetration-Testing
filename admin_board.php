<?php
session_start();
require_once 'db_connect.php';

// Delete user functionality
if(isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM members WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: admin_board.php");
    exit();
}

// Update user functionality
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $mem_id = $_POST['mem_id'];
    $mem_pw = $_POST['mem_pw'];
    $com_id = $_POST['com_id'];
    $team = $_POST['team'];
    $mem_name = $_POST['mem_name'];
    $mem_phonnum = $_POST['mem_phonnum'];
    $mem_email = $_POST['mem_email'];

    $update_query = "UPDATE members SET mem_id=?, mem_pw=?, com_id=?, team=?, 
                    mem_name=?, mem_phonnum=?, mem_email=? WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssssi", $mem_id, $mem_pw, $com_id, $team, $mem_name, 
                      $mem_phonnum, $mem_email, $id);
    $stmt->execute();
    header("Location: admin_board.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>현대오토에버 VaatzIT - 관리자 페이지</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }
        
        .navbar {
            background-color: #0066ff;
            padding: 1rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            height: 40px;
        }

        .hamburger {
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            margin-right: 1rem;
            border: none;
            background: none;
            padding: 0.5rem;
        }
        
        .navbar-title {
            color: white;
            margin: 0;
            font-size: 1.2rem;
        }
        
        .page-container {
            display: flex;
            margin-top: 72px; /* navbar height + padding */
            min-height: calc(100vh - 72px);
        }
        
        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            position: fixed;
            left: -250px;
            top: 72px;
            height: calc(100vh - 72px);
            transition: left 0.3s ease;
            z-index: 900;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar.active {
            left: 0;
        }
        
        .content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 0;
            transition: margin-left 0.3s ease;
            width: 100%;
        }
        
        .content.sidebar-active {
            margin-left: 250px;
        }
        
        .menu-item {
            padding: 15px 20px;
            cursor: pointer;
            border-bottom: 1px solid #dee2e6;
        }
        
        .menu-item:hover {
            background-color: #e9ecef;
        }
        
        .menu-item.active {
            background-color: #0066ff;
            color: white;
        }
        
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        th {
            background-color: #f8f9fa;
            position: sticky;
            top: 0;
        }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-edit {
            background-color: #0066ff;
            color: white;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        @media (max-width: 768px) {
            .content.sidebar-active {
                margin-left: 0;
            }
            
            .content {
                padding: 15px;
            }
            
            .table-container {
                margin-top: 15px;
            }
            
            table {
                font-size: 14px;
            }
            
            .btn {
                padding: 4px 8px;
            }
        }

        /* 테이블 반응형 스타일 */
        @media (max-width: 1024px) {
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            table {
                min-width: 800px;
            }
        }

        /* 페이지 제목 스타일 */
        .page-title {
            margin: 0 0 20px 0;
            font-size: 1.5rem;
            color: #333;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <button class="hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="navbar-title">현대오토에버 VaatzIT 관리자페이지</h1>
    </nav>

    <div class="page-container">
        <div class="sidebar">
            <div class="menu-item active" onclick="showSection('customer-list')">고객사 조회</div>
            <div class="menu-item" onclick="showSection('add-customer')">고객사 추가</div>
        </div>

        <div class="content">
            <div id="customer-list">
                <h2 class="page-title">계정 리스트</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>사용자 ID</th>
                                <th>회사 ID</th>
                                <th>팀</th>
                                <th>이름</th>
                                <th>전화번호</th>
                                <th>이메일</th>
                                <th>작업</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM members ORDER BY id DESC";
                            $result = $conn->query($query);
                            
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['mem_id'] . "</td>";
                                echo "<td>" . $row['com_id'] . "</td>";
                                echo "<td>" . $row['team'] . "</td>";
                                echo "<td>" . $row['mem_name'] . "</td>";
                                echo "<td>" . $row['mem_phonnum'] . "</td>";
                                echo "<td>" . $row['mem_email'] . "</td>";
                                echo "<td>
                                        <button class='btn btn-edit' onclick='editUser({$row['id']})'>수정</button>
                                        <button class='btn btn-delete' onclick='deleteUser({$row['id']})'>삭제</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

    <div id="add-customer" style="display: none;">
    <h2 class="page-title">고객사 추가</h2>
    <div class="form-container">
        <form class="add-form" method="POST" action="add_customer.php">
            <table class="form-table">
                <thead>
                    <tr>
                        <th colspan="2">신규 계정 정보 입력</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><label for="mem_id">사용자 ID</label></td>
                        <td><input type="text" id="mem_id" name="mem_id" required></td>
                    </tr>
                    <tr>
                        <td><label for="mem_pw">비밀번호</label></td>
                        <td><input type="password" id="mem_pw" name="mem_pw" required></td>
                    </tr>
                    <tr>
                        <td><label for="com_id">회사 ID</label></td>
                        <td><input type="text" id="com_id" name="com_id" required></td>
                    </tr>
                    <tr>
                        <td><label for="team">팀</label></td>
                        <td><input type="text" id="team" name="team" required></td>
                    </tr>
                    <tr>
                        <td><label for="mem_name">이름</label></td>
                        <td><input type="text" id="mem_name" name="mem_name" required></td>
                    </tr>
                    <tr>
                        <td><label for="mem_phonnum">전화번호</label></td>
                        <td><input type="tel" id="mem_phonnum" name="mem_phonnum" required></td>
                    </tr>
                    <tr>
                        <td><label for="mem_email">이메일</label></td>
                        <td><input type="email" id="mem_email" name="mem_email" required></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="button-cell">
                            <button type="submit" class="btn btn-edit">추가</button>
                            <button type="button" class="btn btn-cancel" onclick="cancelAdd()">취소</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

    <script>
        let sidebarActive = false;

        function toggleSidebar() {
            sidebarActive = !sidebarActive;
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            
            if (sidebarActive) {
                sidebar.classList.add('active');
                content.classList.add('sidebar-active');
            } else {
                sidebar.classList.remove('active');
                content.classList.remove('sidebar-active');
            }
        }

        function showSection(sectionId) {
            document.getElementById('customer-list').style.display = 'none';
            document.getElementById('add-customer').style.display = 'none';
            document.getElementById(sectionId).style.display = 'block';
            
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.classList.add('active');

            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        }
        

        function deleteUser(id) {
            if(confirm('정말 삭제하시겠습니까?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'admin_board.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_id';
                input.value = id;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editUser(id) {
            window.location.href = `edit_user.php?id=${id}`;
        }
    </script>
</body>
</html>