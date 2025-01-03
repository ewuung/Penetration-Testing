<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>현대오토에버 Q&A</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: white;
            color: #003399;
            padding: 20px;
            padding-left: 160px;
            text-align: left;
            border-bottom: 4px solid #003399;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            font-size: 20px;
        }
        .title_main {
            font-weight: bold;
            color: #003399;
            font-size: 36px;
            font-family: 'Arial', sans-serif;
            cursor: pointer;
        }
        .title_sub {
            font-weight: normal;
            color: rgb(1, 68, 202);
            font-size: 36px;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            gap: 20px;
        }
        .qna-section {
            flex: 1;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .qna-section h2 {
            color: #003399;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .faq {
            margin: 20px 0;
        }
        .faq-question {
            font-weight: bold;
            color: #003399;
            cursor: pointer;
            margin: 10px 0;
        }
        .faq-answer {
            display: none;
            padding: 10px 20px;
            background-color: #f1f1f1;
            border-radius: 4px;
            margin: 5px 0 15px 0;
        }
        .inquiry-button {
            text-align: center;
            margin-top: 20px;
        }
        .inquiry-button button {
            background-color: #003399;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
        }
        .inquiry-button button:hover {
            background-color: #002266;
        }
        footer {
            background-color: #003399;
            color: white;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px 0;
        }
    </style>
    <script>
        function toggleAnswer(id) {
            const answer = document.getElementById(id);
            answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</head>
<body>
    <header>
        <h1>
            <span class="title_main" onclick="location.href='../../main.php'">현대오토에버</span> 
            <span class="title_sub">FAQ 및 Q&A</span>
        </h1>
    </header>
    <div class="container">
        <div class="qna-section">
            <h2>자주 묻는 질문 (FAQ)</h2>
            <div class="faq">
                <div class="faq-question" onclick="toggleAnswer('answer1')">Q1. 회원가입은 어떻게 하나요?</div>
                <div class="faq-answer" id="answer1">회원가입은 상단의 로그인 버튼을 클릭한 후, 회원가입 링크를 통해 진행하실 수 있습니다.</div>
            </div>
            <div class="faq">
                <div class="faq-question" onclick="toggleAnswer('answer2')">Q2. 비밀번호를 잊어버렸어요.</div>
                <div class="faq-answer" id="answer2">비밀번호를 잊으셨다면 "비밀번호 찾기" 링크를 통해 재설정할 수 있습니다.</div>
            </div>
            <div class="faq">
                <div class="faq-question" onclick="toggleAnswer('answer3')">Q3. 고객센터 운영 시간은 언제인가요?</div>
                <div class="faq-answer" id="answer3">고객센터는 평일 오전 9시부터 오후 6시까지 운영됩니다. 주말 및 공휴일은 운영하지 않습니다.</div>
            </div>
        </div>
        <div class="inquiry-button">
            <button onclick="location.href='QnA_board.php'">Q&A 게시판</button>
        </div>
    </div>
     
</body>
</html>

