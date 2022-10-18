<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?php
\Bitrix\Main\Loader::includeModule('crm');

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

$admins = [158, 132, 92];

global $USER;
$userId = $USER->GetID();

$index = 0;
if (array_search($userId, $usersID, false)) {
	$index = array_search($userId, $usersID, false);
	$searchUser = $usersID[$index];
	if (in_array($usersID[$index], $admins, false)) {
		$searchUser = $usersID;
	}
} else {
	$searchUser = $usersID;
}

$firstDate = '';
$lastDate = '';
if (!empty($_POST['first_date']) && !empty($_POST['last_date'])) {
	$firstDate = date("d.m.Y", strtotime($_POST['first_date']));
	$lastDate = date("d.m.Y", strtotime($_POST['first_date']));
}

if ($firstDate !== '' && $lastDate !== '') {
	$Deal = CCrmDeal::GetListEx([],
		['CATEGORY_ID' => '16', 'ASSIGNED_BY_ID' => $searchUser, 'BEGINDATE' => $firstDate, 'CLOSEDATE' => $lastDate],
		false,
		false,
		['*', 'UF_CRM_1663748579248', 'UF_CRM_1663748459170', 'UF_CRM_1663748481446']);
} else {
	$Deal = CCrmDeal::GetListEx([],
		['CATEGORY_ID' => '16', 'ASSIGNED_BY_ID' => $searchUser],
		false,
		false,
		['*', 'UF_CRM_1663748579248', 'UF_CRM_1663748459170', 'UF_CRM_1663748481446']);
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
		'ID клиента' => $record['CONTACT_ID'],
		'Имя клиента' => $record['CONTACT_FULL_NAME'],
		'Тип клиента' => $record['UF_CRM_1663748579248'],
		'ID ответственного' => $record['ASSIGNED_BY_ID'],
		'Дата начала' => $record['BEGINDATE'],
		'Дата принятия в работу' => $record['UF_CRM_1663748459170'],
		'Дата оплаты' => $record['UF_CRM_1663748481446'],
		'Дата закрытия' => $record['CLOSEDATE'],
		'Сумма' => $record['OPPORTUNITY'],
		'Стадия' => $record['STAGE_SEMANTIC_ID']
	];
}
$chartStage = ['Новая' => 0, 'В работе' => 0, 'Оплачено' => 0, 'На складе' => 0];
$userStat = [];
$mask = ['Employee' => '', 'U_c' => 0, 'U_s' => 0, 'F_c' => 0, 'F_s' => 0, 'I_c' => 0, 'I_s' => 0, 'Stock' => 0, 'B_c' => 0, 'B_s' => 0];
foreach ($dealData as $user => $deals) {
	$U_c = 0;
	$F_c = 0;
	$I_c = 0;
	$Stock = 0;
	$B_c = 0;
	$statistics = $mask;
	foreach ($deals as $deal => $data) {
		$statistics['Employee'] = $user;
		switch ($data['Стадия']) {
			case 'Успешна':
				switch ($data['Тип клиента']) {
					case 'Юр.лицо':
						if ($statistics['U_c']) {
							$str = "&deals_id[]=" . $data['ID сделки'] . '&">' . ++$U_c . '</a>';
							$statistics['U_c'] = substr_replace($statistics['U_c'], $str, strrpos($statistics['U_c'], '&', -1));
						} else {
							$statistics['U_c'] = '<a href="detalization.php?deals_id[]=' . $data['ID сделки'] . '&">' . ++$U_c . '</a>';
						}
						$statistics['U_s'] += $data['Сумма'];
						break;
					case 'Физ.лицо':
						if ($statistics['F_c']) {
							$str = "&deals_id[]=" . $data['ID сделки'] . '&">' . ++$F_c . '</a>';
							$statistics['F_c'] = substr_replace($statistics['F_c'], $str, strrpos($statistics['F_c'], '&', -1));
						} else {
							$statistics['F_c'] = '<a href="detalization.php?deals_id[]=' . $data['ID сделки'] . '&">' . ++$F_c . '</a>';
						}
						$statistics['F_s'] += $data['Сумма'];
						break;
					case 'ИП':
						if ($statistics['I_c']) {
							$str = "&deals_id[]=" . $data['ID сделки'] . '&">' . ++$I_c . '</a>';
							$statistics['I_c'] = substr_replace($statistics['I_c'], $str, strrpos($statistics['I_c'], '&', -1));
						} else {
							$statistics['I_c'] = '<a href="detalization.php?deals_id[]=' . $data['ID сделки'] . '&">' . ++$I_c . '</a>';
						}
						$statistics['I_s'] += $data['Сумма'];
						break;
				}
				break;
			case 'Провалена':
				if ($statistics['B_c']) {
					$str = "&deals_id[]=" . $data['ID сделки'] . '&">' . ++$B_c . '</a>';
					$statistics['B_c'] = substr_replace($statistics['B_c'], $str, strrpos($statistics['B_c'], '&', -1));
				} else {
					$statistics['B_c'] = '<a href="detalization.php?deals_id[]=' . $data['ID сделки'] . '&">' . ++$B_c . '</a>';
				}
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
				if ($statistics['Stock']) {
					$str = "&deals_id[]=" . $data['ID сделки'] . '&">' . ++$Stock . '</a>';
					$statistics['Stock'] = substr_replace($statistics['Stock'], $str, strrpos($statistics['Stock'], '&', -1));
				} else {
					$statistics['Stock'] = '<a href="detalization.php?deals_id[]=' . $data['ID сделки'] . '&">' . ++$Stock . '</a>';
				}
				$chartStage['На складе'] += $data['Сумма'];
				break;
		}
	}
	$userStat[] = $statistics;
}

## ответ
$response = ["aaData" => $userStat, "chart" => $chartStage];

echo json_encode($response);
