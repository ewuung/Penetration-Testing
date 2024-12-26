<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 사용자가 제출한 폼 데이터 처리
    $customer_company = $_POST['customer_company'];
    $manager_id = $_POST['manager_id'];
    $department = $_POST['department'];
    $full_name = $_POST['full_name'];
    $employee_id = $_POST['employee_id'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // 데이터베이스에 입력
    $stmt = $pdo->prepare("INSERT INTO customer_managers (customer_company, manager_id, department, full_name, employee_id, email, phone_number) 
    VALUES (:customer_company, :manager_id, :department, :full_name, :employee_id, :email, :phone_number)");
    $stmt->execute([
        'customer_company' => $customer_company,
        'manager_id' => $manager_id,
        'department' => $department,
        'full_name' => $full_name,
        'employee_id' => $employee_id,
        'email' => $email,
        'phone_number' => $phone_number
    ]);
    
    $success_message = "고객 담당자가 성공적으로 등록되었습니다.";
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>고객담당자 등록</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
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
        }
        .container p {
            color: red;
            font-weight: bold;
            font-size: 14px;
        }
        h2 {
            text-align: center;
            color: #003399;
        }
        .form-group {
            margin-bottom: 15px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>고객사 등록 요청</h2>
        <p>● 등록 요청은 구매 담당자의 승인 뒤, 등록된 계정으로 비밀번호를 발송합니다.<br>● 고객사ID를 모르실 경우, 구매 담당자 또는 영업 담당자에게 문의 바랍니다.<br>● 부서가 목록에 없는 경우, 신규부서등록 항목 선택 후 부서명을 직접 입력 바랍니다.</p>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="customer_company">고객사</label>
                <select id="customer_company" name="customer_company" required>
                    <option value="">고객사를 선택하세요.</option>
                    <option value="company1">현대오토에버(자산)</option>
                    <option value="company2">현대오토에버(판매)</option>
                    <option value="company3">현대자동차</option>
                    <option value="company4">기아자동차</option>
                    <option value="company5">현대건설</option>
                    <option value="company6">HYUNDAI E&C</option>
                    <option value="company7">현대 엔지니어링</option>
                    <option value="company8">현대종합설계건축사무소</option>
                    <option value="company9">현대스틸산업주식회사</option>
                    <option value="company10">현대카드</option>
                    <option value="company11">블루월넛</option>
                    <option value="company12">현대캐피탈</option>
                    <option value="company13">현대모비스</option>
                    <option value="company14">현대하이스코</option>
                    <option value="company15">케피코</option>
                    <option value="company16">본텍</option>
                    <option value="company17">기아타이거스</option>
                    <option value="company18">현대제철주식회사</option>
                    <option value="company19">현대종합특수강</option>
                    <option value="company20">삼우 당진공장</option>
                    <option value="company21">그린에어주식회사</option>
                    <option value="company22">현대서산농장</option>
                    <option value="company23">비앤지스틸</option>
                </select>
            </div>
            <div class="form-group">
                <label for="manager_id">담당자ID</label>
                <input type="text" id="manager_id" name="manager_id" required>
            </div>
            <div class="form-group">
                <label for="department">부서</label>
                <select id="department" name="department" required>
                    <option value="">부서를 선택하세요.</option>
                    <option value="Management Support">경영지원팀</option>
                    <option value="Management Support Systems">경영지원시스템팀</option>
                    <option value="Purchasing">구매팀</option>
                    <option value="R&D Business">연구개발사업팀</option>
                    <option value="R&D Systems">연구개발시스템팀</option>
                    <option value="Sales Systems">판매시스템팀</option>
                    <option value="Finished Vehicle IT Business">완성차IT사업팀</option>
                    <option value="Product Development">상품개발팀</option>
                    <option value="Construction Business">건설사업팀</option>
                    <option value="Mobis Systems">모비스시스템팀</option>
                    <option value="Parts Systems">부품시스템팀</option>
                    <option value="Steel Management Systems">철강경영시스템팀</option>
                    <option value="Steel Product Systems">철강생산시스템팀</option>
                </select>
            </div>
            <div class="form-group">
                <label for="full_name">성명</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="employee_id">사번</label>
                <input type="number" id="employee_id" name="employee_id" required>
            </div>
            <div class="form-group">
                <label for="email">이메일</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone_number">회사 전화번호</label>
                <input type="tel" id="phone_number" name="phone_number" required>
            </div>
            <button type="submit">등록</button>
        </form>
    </div>
</body>
</html>
