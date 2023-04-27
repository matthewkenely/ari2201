<?php
if (isset($_POST["url"])) {
    $url = $_POST["url"];
    $command = escapeshellcmd("python ./ner.py " . "url" . " " . escapeshellarg($url));
    $output = shell_exec($command);
    echo "Detected location: <b>";
    echo $output;
    echo "<b>";
    return;
};

if (isset($_POST["text"])) {
    $text = $_POST["text"];
    $command = escapeshellcmd("python ./ner.py " . "text" . " " . escapeshellarg($text));
    $output = shell_exec($command);
    echo "Detected location: <b>";
    echo $output;
    echo "<b>";
    return;
};
?>