<?php

//$result = json_decode($_POST['x1'], true);
var_dump($_POST['users']);
//echo json_encode($result);
$data = $_POST['users'];

$fd = fopen("usersList/usersList.json", 'r+') or die("не удалось открыть файл");
fwrite($fd, $data);
fclose($fd);
