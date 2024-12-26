<?php
session_start();
require 'db.php'; // PDO 연결 포함

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['MEM_ID'];

        // 입력 데이터 가져오기
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $company_name = $_POST['COM_NAME'];
        $team = $_POST['MEM_TEAM'];
        $name = $_POST['MEM_NAME'];
        $phone = $_POST['MEM_PHONNUM'];
        $email = $_POST['MEM_EMAIL'];

        // SQL 쿼리 준비
        $query = "
            UPDATE MEMBERS 
            SET MEM_PW = :password, 
                COM_NAME = :company_name, 
                MEM_TEAM = :team, 
                MEM_NAME = :name, 
                MEM_PHONNUM = :phone, 
                MEM_EMAIL = :email 
            WHERE MEM_ID = :user_id
        ";
        $stmt = $pdo->prepare($query);

        // 파라미터 바인딩 및 실행
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':company_name', $company_name, PDO::PARAM_STR);
        $stmt->bindParam(':team', $team, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);

        $stmt->execute();

        echo "회원 정보가 성공적으로 업데이트되었습니다.";
    }
} catch (PDOException $e) {
    // 에러 로깅 및 에러 메시지
    error_log("DB 작업 중 오류 발생: " . $e->getMessage(), 3, '/path/to/logfile.log');
    die("정보 수정에 실패했습니다. 관리자에게 문의하세요.");
}
?>
