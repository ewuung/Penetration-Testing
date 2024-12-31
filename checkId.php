<?php
require 'db.php';

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    
    $query = "SELECT COUNT(*) FROM MEMBERS WHERE MEM_ID = '$user_id'";
    $stmt = $pdo->query($query);
    
    $result = $stmt->fetchColumn();
    
    if ($result > 0) {
        echo "exists";
    } else {
        echo "available";
    }
}
?>
