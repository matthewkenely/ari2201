<?php
if (isset($_POST["location"])) {
    $text = $_POST["location"];
    $command = escapeshellcmd("python ./grid.py " . "location" . " " . escapeshellarg($text) . " " . "filter");
    $output = shell_exec($command);
    echo $output;
    return;
};
?>