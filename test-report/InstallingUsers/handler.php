<?php

$data = $_POST['users'];
var_dump(count(json_decode($data, true)));
$filename = "usersList/usersList.json";

$fd = fopen($filename, 'w') or die("не удалось открыть файл");
fwrite($fd, $data);
fclose($fd);