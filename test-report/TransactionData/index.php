<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<?php
/*\Bitrix\Main\Loader::includeModule('crm');

use Bitrix\Crm\Category\DealCategory;

$typeClient = ['ИП' => '5922', 'Юр.лицо' => '5921', 'Физ.лицо' => '5920'];
$StageList = DealCategory::getStageList('16');

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
var_dump($dealData);
$userStat = [];
$mask = ['Employee' => '', 'U_c' => 0, 'U_s' => 0, 'F_c' => 0, 'F_s' => 0, 'I_c' => 0, 'I_s' => 0, 'Stock' => 0, 'B_c' => 0, 'B_s' => 0];
foreach ($dealData as $user => $deals) {
	$statistics = $mask;
	foreach ($deals as $deal => $data) {
        $statistics['Employee'] = $user;
		if ($data['Стадия'] === 'Успешна') {
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
		}
		if ($data['Стадия'] === 'Провалена') {
			++$statistics['B_c'];
			$statistics['B_s'] += $data['Сумма'];
		}
		if ($data['Стадия'] === 'На складе') {
			++$statistics['Stock'];
		}
	}
	$userStat[] = $statistics;
}
var_dump($userStat);*/

//'UF_CRM_1663748579248' поля тип клиента
?>

    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.rtl.min.css">-->

        <link rel="stylesheet" type="text/css"
              href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css"
              href="https://cdn.datatables.net/v/bs5/dt-1.12.1/date-1.1.2/sb-1.3.4/sp-2.0.2/datatables.min.css"/>

        <script type=" text/javascript"
                src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!--<script type=" text/javascript"
                src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>-->

        <script type="text/javascript"
                src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript"
                src="https://cdn.datatables.net/v/bs5/dt-1.12.1/date-1.1.2/sb-1.3.4/sp-2.0.2/datatables.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"
                integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.2.1/chartjs-plugin-zoom.min.js"
                integrity="sha512-klQv6lz2YR+MecyFYMFRuU2eAl8IPRo6zHnsc9n142TJuJHS8CG0ix4Oq9na9ceeg1u5EkBfZsFcV3U7J51iew=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <body>
    <div class="container">
        <form class="variableDate" id="Date" method="post">
            <div class="fromDate">
                <label>Введите начальную дату:</label><br/>
                <input type="date" id="first_date" name="first_date"><br>
            </div>
            <div class="toDate">
                <label>Введите конечную дату:</label><br/>
                <input type="date" id="last_date" name="last_date"><br>
            </div>
            <input class="chekDate" type="submit" name="done" id="done" value="Показать диапазон"><br>
        </form>
    </div>
    <div class="container">
        <table id="myTable" class="table table-hover table-bordered">
            <thead class="align-middle">
            <tr>
                <th class="text-center" rowspan="3">Сотрудники</th>
                <th class="text-center" colspan="6">Тип клиента</th>
                <th class="text-center" rowspan="3">На складе</th>
                <th class="text-center" rowspan="2" colspan="2">Брак</th>
            </tr>
            <tr class="text-center">
                <th class="text-center" colspan="2">Юр.лицо</th>
                <th class="text-center" colspan="2">Физ.дицо</th>
                <th class="text-center" colspan="2">ИП</th>
            </tr>
            <tr class="text-center">
                <th>Кол-во</th>
                <th>Сумма</th>
                <th>Кол-во</th>
                <th>Сумма</th>
                <th>Кол-во</th>
                <th>Сумма</th>
                <th>Кол-во</th>
                <th>Сумма</th>
            </tr>
            </thead>
            <tbody class="table-group-divider">
            </tbody>
            <tfoot>
                <tr>
                    <td>Всего закрыто:</td>
                    <td class="Total text-center" colspan="9"></td>
                </tr>
                <tr>
                    <td>Закрыто на сумму:</td>
                    <td class="Sum text-center" colspan="9"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="container charts">
        <div class="chart1">
            <canvas id="testChart1"></canvas>
        </div>
    </div>
    <script type="text/javascript" src="js/script.js"></script>
    </body>
    </html>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>