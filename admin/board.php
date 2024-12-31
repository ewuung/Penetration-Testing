<?php
session_start();
require_once '../db.php';

// Delete user functionality
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM MEMBERS WHERE id = :id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->bindValue(':id', $delete_id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: board.php");
    exit();
}

// Update user functionality
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $mem_id = $_POST['MEM_ID'];
    $mem_pw = $_POST['MEM_PW'];
    $com_id = $_POST['COM_ID'];
    $mem_team = $_POST['MEM_TEAM'];
    $mem_name = $_POST['MEM_NAME'];
    $mem_phonnum = $_POST['MEM_PHONENUM'];
    $mem_email = $_POST['MEM_EMAIL'];

    $update_query = "UPDATE MEMBERS SET MEM_ID = :mem_id, MEM_PW = :mem_pw, COM_ID = :com_id, MEM_TEAM = :mem_team, 
                    MEM_NAME = :mem_name, MEM_PHONENUM = :mem_phonnum, MEM_EMAIL = :mem_email WHERE id = :id";
    $stmt = $pdo->prepare($update_query);
    $stmt->bindValue(':mem_id', $mem_id, PDO::PARAM_STR);
    $stmt->bindValue(':mem_pw', $mem_pw, PDO::PARAM_STR);
    $stmt->bindValue(':com_id', $com_id, PDO::PARAM_STR);
    $stmt->bindValue(':mem_team', $mem_team, PDO::PARAM_STR);
    $stmt->bindValue(':mem_name', $mem_name, PDO::PARAM_STR);
    $stmt->bindValue(':mem_phonnum', $mem_phonnum, PDO::PARAM_STR);
    $stmt->bindValue(':mem_email', $mem_email, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: board.php");
    exit();
}
// 공지사항 삭제 기능
if (isset($_POST['delete_notice_id'])) {
    $delete_id = $_POST['delete_notice_id'];
    $delete_query = "DELETE FROM notice WHERE id = :id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->bindValue(':id', $delete_id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: board.php");
    exit();
}

// Delete Q&A functionality
if (isset($_POST['delete_qa_id'])) {
    $delete_id = $_POST['delete_qa_id'];
    $delete_query = "DELETE FROM board WHERE id = :id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->bindValue(':id', $delete_id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: board.php");
    exit();
}

// Update Q&A functionality
if (isset($_POST['update_qa'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $MEM_ID = $_POST['MEM_ID'];
    $phone = $_POST['phone'];
    $content = $_POST['content'];
    $is_private = isset($_POST['is_private']) ? 1 : 0;

    $update_query = "UPDATE board SET title = :title, MEM_ID = :MEM_ID, phone = :phone, content = :content, is_private = :is_private WHERE id = :id";
    $stmt = $pdo->prepare($update_query);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':mem_id', $mem_id, PDO::PARAM_STR);
    $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':is_private', $is_private, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: board.php");
    exit();
}

// Add Q&A functionality
if (isset($_POST['add_qa'])) {
    $title = $_POST['title'];
    $mem_id = $_POST['mem_id'];
    $phone = $_POST['phone'];
    $content = $_POST['content'];
    $is_private = isset($_POST['is_private']) ? 1 : 0;

    $add_query = "INSERT INTO board (title, MEM_ID, phone, content, is_private, created_at) VALUES (:title, :mem_id, :phone, :content, :is_private, NOW())";
    $stmt = $pdo->prepare($add_query);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':mem_id', $mem_id, PDO::PARAM_STR);
    $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':is_private', $is_private, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: board.php");
    exit();
}

// Corporate card update functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['corporate_card_file'])) {
    $upload_dir = 'member_guide_files/';
    $filename = 'memguide.pdf'; // Fixed filename
    $file_path = $upload_dir . $filename;

    // Ensure directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Move uploaded file to the directory, overwrite if exists
    if (move_uploaded_file($_FILES['corporate_card_file']['tmp_name'], $file_path)) {
        $upload_success = "파일이 성공적으로 업로드되었습니다.";
    } else {
        $upload_error = "파일 업로드 중 오류가 발생했습니다.";
    }
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

        .note {
            margin-top: 10px;
            font-size: 14px;
            color: gray;
        }
        .upload-section {
            margin-top: 20px;
        }
        .upload-button {
            margin-top: 10px;
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
            <div class="menu-item" onclick="showSection('notice-management')">공지사항 관리</div>
            <div class="menu-item" onclick="showSection('qa-management')">Q&A 관리</div>
            <div class="menu-item" onclick="showSection('card-update')">법인카드 신청서 업데이트</div>
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
                            $query = "SELECT * FROM MEMBERS ORDER BY id DESC";
                            $result = $pdo->query($query);
                            
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['MEM_ID'] . "</td>";
                                echo "<td>" . $row['COM_ID'] . "</td>";
                                echo "<td>" . $row['MEM_TEAM'] . "</td>";
                                echo "<td>" . $row['MEM_NAME'] . "</td>";
                                echo "<td>" . $row['MEM_PHONENUM'] . "</td>";
                                echo "<td>" . $row['MEM_EMAIL'] . "</td>";
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
                    <form class="add-form" method="POST" action="addCustomer.php">
                        <table class="form-table">
                            <thead>
                                <tr>
                                    <th colspan="2">신규 계정 정보 입력</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><label for="mem_id">아이디</label></td>
                                    <td><input type="text" id="mem_id" name="MEM_ID" required></td>
                                </tr>
                                <tr>
                                    <td><label for="mem_pw">비밀번호</label></td>
                                    <td><input type="password" id="mem_pw" name="MEM_PW" required></td>
                                </tr>
                                <tr>
                                    <td><label for="com_id">고객사 ID</label></td>
                                    <td><input type="text" id="com_id" name="COM_ID" required></td>
                                </tr>
                                <tr>
                                    <td><label for="mem_team">부서</label></td>
                                    <td><input type="text" id="mem_team" name="MEM_TEAM" required></td>
                                </tr>
                                <tr>
                                    <td><label for="mem_name">성명</label></td>
                                    <td><input type="text" id="mem_name" name="MEM_NAME" required></td>
                                </tr>
                                <tr>
                                    <td><label for="mem_phonenum">연락처(휴대폰)</label></td>
                                    <td><input type="tel" id="mem_phonenum" name="MEM_PHONENUM" required></td>
                                </tr>
                                <tr>
                                    <td><label for="mem_email">E-mail</label></td>
                                    <td><input type="email" id="mem_email" name="MEM_EMAIL" required></td>
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

            <!-- Q&A 관리 섹션 -->
            <div id="qa-management" style="display: none;">
                <h2 class="page-title">Q&A 관리</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>제목</th>
                                <th>작성자 ID</th>
                                <th>작성일</th>
                                <th>작업</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT id, title, MEM_ID, created_at FROM board ORDER BY created_at DESC";
                            $result = $pdo->query($query);

                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td><a href='QnA_detail.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></td>";
                                echo "<td>" . htmlspecialchars($row['MEM_ID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                echo "<td>
                                        <button class='btn btn-edit' onclick='editQnA(" . $row['id'] . ")'>수정</button>
                                        <button class='btn btn-delete' onclick='deleteQA(" . $row['id'] . ")'>삭제</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Q&A Button -->
                <div style="margin-top: 20px;">
                    <button class="btn btn-edit" onclick="addQnA()">Q&A 추가</button>
                </div>
            </div>

            <!-- 법인카드 신청서 업데이트 섹션 -->
            <div id="card-update" style="display: none;">
                <h2 class="page-title">법인카드 신청서 업데이트</h2>
                <div class="upload-section">
                    <?php if (isset($upload_success)): ?>
                        <p style="color: green;"><?= htmlspecialchars($upload_success) ?></p>
                    <?php endif; ?>
                    <?php if (isset($upload_error)): ?>
                        <p style="color: red;"><?= htmlspecialchars($upload_error) ?></p>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <label for="corporate_card_file">신청서 파일 업로드</label>
                        <input type="file" id="corporate_card_file" name="corporate_card_file" accept=".pdf" required>
                        <button type="submit" class="btn btn-edit upload-button">신청서 업로드</button>
                    </form>
                    <p class="note">memguide.pdf 파일명이 아닌 파일을 업로드하려면 관리자에게 문의하세요.</p>
                </div>
            </div>

            <!-- 공지사항 관리 섹션 -->
            <div id="notice-management" style="display: none;">
                <h2 class="page-title">공지사항 관리</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>제목</th>
                                <th>작성일</th>
                                <th>작업</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM notice ORDER BY created_at DESC";
                            $result = $pdo->query($query);

                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td><a href='notice_detail.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></td>";
                                echo "<td>" . $row['created_at'] . "</td>";
                                echo "<td>
                                        <button class='btn btn-edit' onclick='editNotice({$row['id']})'>수정</button>
                                        <button class='btn btn-delete' onclick='deleteNotice({$row['id']})'>삭제</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    <button class="btn btn-edit" onclick="addNotice()">공지사항 추가</button>
                </div>
            </div>
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
            document.getElementById('notice-management').style.display = 'none';
            document.getElementById('qa-management').style.display = 'none';
            document.getElementById('card-update').style.display = 'none';
            document.getElementById(sectionId).style.display = 'block';
            
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.classList.add('active');

            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        }

        function deleteNotice(id) {
            if (confirm('정말 삭제하시겠습니까?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_notice_id';
                input.value = id;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
        function deleteQA(id) {
            if (confirm('정말 삭제하시겠습니까?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_qa_id';
                input.value = id;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function addQnA() {
            window.location.href = 'addQnA.php';
        }

        function editQnA(id) {
            window.location.href = `editQnA.php?id=${id}`;
        }

        function editNotice(id) {
            window.location.href = `editNotice.php?id=${id}`;
        }

        function addNotice() {
            window.location.href = 'addNotice.php';
        }
    </script>
</body>
</html>