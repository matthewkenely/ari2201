<?php
if (isset($_POST["good"])) {
    $link = $_POST["good"];
    $correct = $_POST["correct"];
    $command = escapeshellcmd("python ./feedback.py " . "good" . " " . escapeshellarg($link) . " " . escapeshellarg($correct));
    $output = shell_exec($command);
    echo $output;
    return;
};

if (isset($_POST["bad"])) {
    $link = $_POST["bad"];
    $correct = $_POST["correct"];
    $command = escapeshellcmd("python ./feedback.py " . "bad" . " " . escapeshellarg($link) . " " . escapeshellarg($correct));
    $output = shell_exec($command);
    echo $output;
    return;
};
?>