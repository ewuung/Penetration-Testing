<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $mem_id = htmlspecialchars($_POST['MEM_ID']); // Sanitize input
    $mem_pw = password_hash($_POST['MEM_PW'], PASSWORD_DEFAULT); // Hash the password
    $com_id = htmlspecialchars($_POST['COM_ID']);
    $mem_team = htmlspecialchars($_POST['MEM_TEAM']);
    $mem_name = htmlspecialchars($_POST['MEM_NAME']);
    $mem_phonenum = htmlspecialchars($_POST['MEM_PHONENUM']);
    $mem_email = htmlspecialchars($_POST['MEM_EMAIL']);

    // Check if user already exists
    $check_query = "SELECT * FROM MEMBERS WHERE MEM_ID = :mem_id";
    $stmt = $pdo->prepare($check_query);
    $stmt->bindParam(':mem_id', $mem_id, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<script>
                alert('이미 존재하는 사용자 ID입니다.');
                window.location.href = 'board.php';
              </script>";
        exit();
    }

    // Insert new user
    $insert_query = "INSERT INTO MEMBERS (MEM_ID, MEM_PW, COM_ID, MEM_TEAM, MEM_NAME, MEM_PHONENUM, MEM_EMAIL) 
                     VALUES (:mem_id, :mem_pw, :com_id, :mem_team, :mem_name, :mem_phonenum, :mem_email)";
    
    $stmt = $pdo->prepare($insert_query);
    $stmt->bindParam(':mem_id', $mem_id, PDO::PARAM_STR);
    $stmt->bindParam(':mem_pw', $mem_pw, PDO::PARAM_STR);
    $stmt->bindParam(':com_id', $com_id, PDO::PARAM_STR);
    $stmt->bindParam(':mem_team', $mem_team, PDO::PARAM_STR);
    $stmt->bindParam(':mem_name', $mem_name, PDO::PARAM_STR);
    $stmt->bindParam(':mem_phonenum', $mem_phonenum, PDO::PARAM_STR);
    $stmt->bindParam(':mem_email', $mem_email, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "<script>
                alert('고객사가 성공적으로 추가되었습니다.');
                window.location.href = 'board.php';
              </script>";
    } else {
        echo "<script>
                alert('오류가 발생했습니다.');
                window.location.href = 'board.php';
              </script>";
    }

    $stmt->closeCursor(); // Close the statement
} else {
    // Redirect if accessed directly without POST
    header("Location: board.php");
    exit();
}
?>
