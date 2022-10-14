<?php

$data = $_POST['users'];

$filename = "usersList/usersList.json";

$fd = fopen($filename, 'w') or die("не удалось открыть файл");
fwrite($fd, $data);
fclose($fd);
