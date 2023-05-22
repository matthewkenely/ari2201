<?php
if (isset($_POST["location"])) {
    $text = $_POST["location"];
    $command = escapeshellcmd("python ./grid.py " . "location" . " " . escapeshellarg($text));
    $output = shell_exec($command);
    echo $output;
    return;
};
?>