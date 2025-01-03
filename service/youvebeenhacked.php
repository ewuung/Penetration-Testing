<?php
// 이미지 파일의 경로를 설정
$imagePath = __DIR__ . '/youvebeenhacked.jpeg';

// 파일이 존재하는지 확인
if (file_exists($imagePath)) {
    // 브라우저에 이미지 출력
    header('Content-Type: image/jpeg');
    readfile($imagePath);
} else {
    // 파일이 없을 경우 메시지 출력
    echo 'Image file not found.';
}
?>
