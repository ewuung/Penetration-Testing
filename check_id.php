<?php
// check_id.php

require 'db.php';

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM MEMBERS WHERE MEM_ID = :user_id");
    $stmt->execute(['user_id' => $user_id]);

    if ($stmt->fetchColumn() > 0) {
        echo 'exists';
    } else {
        echo 'available';
    }
}
?>
