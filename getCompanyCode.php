<?php
session_start();
require 'db.php'; // PDO 연결 포함

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $company_name = $_POST['company_name'];

        // SQL 쿼리 준비
        $query = "SELECT COM_CODE FROM COMPANY WHERE COM_NAME = :company_name";
        $stmt = $pdo->prepare($query);

        // 파라미터 바인딩
        $stmt->bindParam(':company_name', $company_name, PDO::PARAM_STR);

        // 실행 및 결과 가져오기
        $stmt->execute();
        $company_code = $stmt->fetchColumn(); // 단일 결과 값 가져오기

        // AJAX 요청에 고객사 코드 반환
        echo $company_code ? $company_code : '코드가 없습니다.';
    }
} catch (PDOException $e) {
    // 에러 로깅 및 에러 메시지
    error_log("DB 작업 중 오류 발생: " . $e->getMessage(), 3, '/path/to/logfile.log');
    die("정보를 가져오는데 실패했습니다. 관리자에게 문의하세요.");
}
?>
