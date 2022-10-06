<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?php

\Bitrix\Main\Loader::includeModule('crm');

/*use Bitrix\Crm\Category\DealCategory;
$StageList = DealCategory::getStageList('16');*/
//$typeClient = ['ИП' => '5922', 'Юр.лицо' => '5921', 'Физ.лицо' => '5920'];

$fd = fopen("../InstallingUsers/usersList/usersList.json", 'r') or die("не удалось открыть файл");
$usersList = null;
while (!feof($fd)) {
	$usersList = json_decode(fgets($fd), true);
}
fclose($fd);

$usersID = [];
foreach ($usersList as $key => $value) {
	$usersID[] = $value['id'];
}

$Deal = CCrmDeal::GetListEx([], ['CATEGORY_ID' => '16', 'ASSIGNED_BY_ID' => $usersID], false, false, ['*', 'UF_CRM_1663748579248']);
$dealData = [];
while ($row = $Deal->Fetch()) {
	if ($row['UF_CRM_1663748579248'] === '5922') {
		$row['UF_CRM_1663748579248'] = 'ИП';
	}
	if ($row['UF_CRM_1663748579248'] === '5921') {
		$row['UF_CRM_1663748579248'] = 'Юр.лицо';
	}
	if ($row['UF_CRM_1663748579248'] === '5920') {
		$row['UF_CRM_1663748579248'] = 'Физ.лицо';
	}

	if ($row['STAGE_SEMANTIC_ID'] === 'S') {
		$row['STAGE_SEMANTIC_ID'] = 'Успешна';
	} elseif ($row['STAGE_SEMANTIC_ID'] === 'F') {
		$row['STAGE_SEMANTIC_ID'] = 'Провалена';
	} elseif ($row['STAGE_SEMANTIC_ID'] === 'P' && $row['STAGE_ID'] === 'C16:1') {
		$row['STAGE_SEMANTIC_ID'] = 'На складе';
	} else {
		$row['STAGE_SEMANTIC_ID'] = 'В работе';
	}

	$dealData["{$row['ASSIGNED_BY_NAME']} {$row['ASSIGNED_BY_LAST_NAME']}"][] = ['ID сделки' => $row['ID'],
		'Название сделки' => $row['TITLE'],
		'Дата начала' => $row['BEGINDATE'],
		'Дата закрытия' => $row['CLOSEDATE'],
		'Сумма' => $row['OPPORTUNITY'],
		'ID клиента' => $row['CONTACT_ID'],
		'Тип клиента' => $row['UF_CRM_1663748579248'],
		'Стадия' => $row['STAGE_SEMANTIC_ID']];
}

$userStat = [];
$mask = ['Employee' => '', 'U-c' => 0, 'U-s' => 0, 'F-c' => 0, 'F-s' => 0, 'I-c' => 0, 'I-s' => 0, 'Stock' => 0, 'B-c' => 0, 'B-s' => 0];
/*$mask = ['Юр.лицо' => ['Кол-во' => 0, 'Сумма' => 0],
	'Физ.лицо' => ['Кол-во' => 0, 'Сумма' => 0],
	'ИП' => ['Кол-во' => 0, 'Сумма' => 0],
	'На складе' => 0,
	'Брак' => ['Кол-во' => 0, 'Сумма' => 0]];*/
foreach ($dealData as $user => $deals) {
	$statistics = $mask;
	foreach ($deals as $deal => $data) {
		if ($data['Стадия'] === 'Успешна') {
			switch ($data['Тип клиента']) {
				case 'Юр.лицо':
					$statistics['Юр.лицо']['Кол-во'] += 1;
					$statistics['Юр.лицо']['Сумма'] += $data['Сумма'];
					break;
				case 'Физ.лицо':
					$statistics['Физ.лицо']['Кол-во'] += 1;
					$statistics['Физ.лицо']['Сумма'] += $data['Сумма'];
					break;
				case 'ИП':
					$statistics['ИП']['Кол-во'] += 1;
					$statistics['ИП']['Сумма'] += $data['Сумма'];
					break;
			}
		}
		if ($data['Стадия'] === 'Провалена') {
			$statistics['Брак']['Кол-во'] += 1;
			$statistics['Брак']['Сумма'] += $data['Сумма'];
		}
		if ($data['Стадия'] === 'На складе') {
			++$statistics['На складе'];
		}
		$userStat[$user] = $statistics;
	}
}
//var_dump($userStat);

## значение параметров ajax запроса Datatable
$draw = $_POST['draw']; //счетчик для последовательных ajax-возвратов из запросов на обработку на стороне сервера
$row = $_POST['start']; //индикатор пейджинга первой записи (0)
$rowperpage = $_POST['length']; //количество записей
$columnIndex = $_POST['order'][0]['column']; //индекц столбца, к которому следует применить сортировку
$columnName = $_POST['columns'][$columnIndex]['data']; //источник данных столбца
$columnSortOrder = $_POST['order'][0]['dir']; //упорядочения по возрастанию или убывания для столбца
$searchValue = $_POST['search']['value']; //значение глобального поиска


//данные для таблицы
/*$tableData = [];
foreach ($empRecords as $xyi) {
	$tableData[] = array(
		"trip_no" => $xyi['trip_no'],
		"date" => date("d.m.Y", strtotime($xyi['date'])),
		"ID_psg" => $xyi['ID_psg'],
		"place" => $xyi['place']
	);
}*/



## ответ
$response = ["draw" => (int)$draw,
	"iTotalRecords" => 10,
	"iTotalDisplayRecords" => 10,
	"aaData" => $userStat];

echo json_encode($response);
