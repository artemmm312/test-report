<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<?php
\Bitrix\Main\Loader::includeModule('crm');

$usersData = CUser::GetList($by, $order, ['ACTIVE' => 'Y']);
$fio = [];
while ($item = $usersData->Fetch()) {
	$fio[$item['ID']] = "{$item['NAME']} " . $item['LAST_NAME'];
}
$usersID = [];
foreach ($fio as $key => $value) {
	$usersID[] = $value['id'];
}

$admins = [158, 132, 92];
$usersID = array_merge($admins, $fio);

global $USER;

$userId = $USER->GetID();

if (in_array($userId, $usersID, false)) {
	echo '<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.rtl.min.css">
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<script type=" text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script type=" text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type=" text/javascript"
        src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
</head>
<body>
<div class="container-fluid h-100 bg-light">
    <div class="row">
        <div class="col-12 text-center border py-1">
            <h2>Выбор пользователя:</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center align-items-center justify-content-center" style="overflow: hidden;">
            <select id="myS" class="selectpicker"
                    data-width="fit"
                    data-live-search="true"
                    data-container="body"
                    data-size="7"
                    multiple
                    data-actions-box="true"
                    data-selected-text-format="count"
                    data-none-selected-text="Выбор пользователей"
                    data-deselect-all-text="Убрать всех"
                    data-select-all-text="Выбрать всех"
                    data-none-results-text="Ничего не найдено {0}"
                    data-count-selected-text="Выбрано {0} (из {1})">';

	foreach ($fio as $key => $value) {
		echo "<option value='$key'>" . $value . "</option>";
	}

	echo '</select>
        </div>
        <button id="myB" type="button" class="btn btn-info" disabled data-bs-toggle="button">Добавить выбранных
            пользователей
        </button>
    </div>
    <div class="row">
        <ul class="user_list">';

	$fd = fopen("usersList/usersList.json", 'r') or die("не удалось открыть файл");
	$usersList = null;
	while (!feof($fd)) {
		$usersList = json_decode(fgets($fd), true);
		//var_dump($usersList);
	}
	fclose($fd);
	$count = count($usersList);
	for ($i = 0; $i < $count; $i++) {
		$value = $usersList[$i]['id'];
		$text = $usersList[$i]['name'];
		echo "<li id='$value' value='$value'>" . $text . "<button id='closeB' type='button' class='btn-close' aria-label='Close'></button></li>";
	}

	echo '</ul>
    </div>
</div>
<script type="text/javascript" src="js/script.js"></script>
</body>
</html>';
} else {
	echo "К сожалению у вас нет доступа к этим данным (=";
}
?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>

//await BX.rest.callMethod