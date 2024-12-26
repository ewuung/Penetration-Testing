<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $mem_id = mysqli_real_escape_string($conn, $_POST['mem_id']);
    $mem_pw = password_hash($_POST['mem_pw'], PASSWORD_DEFAULT); // Hash the password
    $com_id = mysqli_real_escape_string($conn, $_POST['com_id']);
    $team = mysqli_real_escape_string($conn, $_POST['team']);
    $mem_name = mysqli_real_escape_string($conn, $_POST['mem_name']);
    $mem_phonnum = mysqli_real_escape_string($conn, $_POST['mem_phonnum']);
    $mem_email = mysqli_real_escape_string($conn, $_POST['mem_email']);

    // Check if user already exists
    $check_query = "SELECT * FROM members WHERE mem_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $mem_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('이미 존재하는 사용자 ID입니다.');
                window.location.href = 'admin_board.php';
              </script>";
        exit();
    }

    // Insert new user
    $insert_query = "INSERT INTO members (mem_id, mem_pw, com_id, team, mem_name, mem_phonnum, mem_email) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssssss", $mem_id, $mem_pw, $com_id, $team, $mem_name, $mem_phonnum, $mem_email);

    if ($stmt->execute()) {
        echo "<script>
                alert('고객사가 성공적으로 추가되었습니다.');
                window.location.href = 'admin_board.php';
              </script>";
    } else {
        echo "<script>
                alert('오류가 발생했습니다: " . $stmt->error . "');
                window.location.href = 'admin_board.php';
              </script>";
    }

    $stmt->close();
} else {
    // Redirect if accessed directly without POST
    header("Location: admin_board.php");
    exit();
}
?>