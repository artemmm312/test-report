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

$firstDate = '';
$lastDate = '';
if (!empty($_POST['first_date']) && !empty($_POST['last_date'])) {
	$firstDate = date("d.m.Y", strtotime($_POST['first_date']));
	$lastDate = date("d.m.Y", strtotime($_POST['first_date']));
}

if ($firstDate !== '' && $lastDate !== '') {
	$Deal = CCrmDeal::GetListEx([],
		['CATEGORY_ID' => '16', 'ASSIGNED_BY_ID' => $usersID, 'BEGINDATE' => $firstDate, 'CLOSEDATE' => $lastDate],
		false,
		false,
		['*', 'UF_CRM_1663748579248']);
} else {
	$Deal = CCrmDeal::GetListEx([],
		['CATEGORY_ID' => '16', 'ASSIGNED_BY_ID' => $usersID],
		false,
		false,
		['*', 'UF_CRM_1663748579248']);
}

$dealData = [];
while ($record = $Deal->Fetch()) {
	if ($record['UF_CRM_1663748579248'] === '5922') {
		$record['UF_CRM_1663748579248'] = 'ИП';
	}
	if ($record['UF_CRM_1663748579248'] === '5921') {
		$record['UF_CRM_1663748579248'] = 'Юр.лицо';
	}
	if ($record['UF_CRM_1663748579248'] === '5920') {
		$record['UF_CRM_1663748579248'] = 'Физ.лицо';
	}

	if ($record['STAGE_SEMANTIC_ID'] === 'S') {
		$record['STAGE_SEMANTIC_ID'] = 'Успешна';
	} elseif ($record['STAGE_SEMANTIC_ID'] === 'F') {
		$record['STAGE_SEMANTIC_ID'] = 'Провалена';
	} elseif ($record['STAGE_SEMANTIC_ID'] === 'P') {
		switch ($record['STAGE_ID']) {
			case 'C16:NEW':
				$record['STAGE_SEMANTIC_ID'] = 'Новая';
				break;
			case 'C16:PREPARATION':
				$record['STAGE_SEMANTIC_ID'] = 'В работе';
				break;
			case 'C16:FINAL_INVOICE':
				$record['STAGE_SEMANTIC_ID'] = 'Оплачено';
				break;
			case 'C16:1':
				$record['STAGE_SEMANTIC_ID'] = 'На складе';
				break;
		}
	}
	$dealData["{$record['ASSIGNED_BY_NAME']} {$record['ASSIGNED_BY_LAST_NAME']}"][] = [
		'ID сделки' => $record['ID'],
		'Название сделки' => $record['TITLE'],
		'Дата начала' => $record['BEGINDATE'],
		'Дата закрытия' => $record['CLOSEDATE'],
		'Сумма' => $record['OPPORTUNITY'],
		'ID клиента' => $record['CONTACT_ID'],
		'Тип клиента' => $record['UF_CRM_1663748579248'],
		'Стадия' => $record['STAGE_SEMANTIC_ID']
	];
}
$chartStage = ['Новая' => 0, 'В работе' => 0, 'Оплачено' => 0, 'На складе' => 0];
$userStat = [];
$mask = ['Employee' => '', 'U_c' => 0, 'U_s' => 0, 'F_c' => 0, 'F_s' => 0, 'I_c' => 0, 'I_s' => 0, 'Stock' => 0, 'B_c' => 0, 'B_s' => 0];
foreach ($dealData as $user => $deals) {
	$statistics = $mask;
	foreach ($deals as $deal => $data) {
		$statistics['Employee'] = $user;
		switch ($data['Стадия']) {
			case 'Успешна':
				switch ($data['Тип клиента']) {
					case 'Юр.лицо':
						++$statistics['U_c'];
						$statistics['U_s'] += $data['Сумма'];
						break;
					case 'Физ.лицо':
						++$statistics['F_c'];
						$statistics['F_s'] += $data['Сумма'];
						break;
					case 'ИП':
						++$statistics['I_c'];
						$statistics['I_s'] += $data['Сумма'];
						break;
				}
				break;
			case 'Провалена':
				++$statistics['B_c'];
				$statistics['B_s'] += $data['Сумма'];
				break;
			case 'Новая':
				$chartStage['Новая'] += $data['Сумма'];
				break;
			case 'В работе':
				$chartStage['В работе'] += $data['Сумма'];
				break;
			case 'Оплачено':
				$chartStage['Оплачено'] += $data['Сумма'];
				break;
			case 'На складе':
				++$statistics['Stock'];
				$chartStage['На складе'] += $data['Сумма'];
				break;
		}
	}
	$userStat[] = $statistics;
}

## ответ
$response = ["aaData" => $userStat, "chart" => $chartStage];

echo json_encode($response);
