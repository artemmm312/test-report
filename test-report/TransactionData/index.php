<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.rtl.min.css">
        <link rel="stylesheet" type="text/css"
              href="https://cdn.datatables.net/v/dt/dt-1.12.1/date-1.1.2/sb-1.3.4/datatables.min.css"/>
        <script type=" text/javascript"
                src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript"
                src="https://cdn.datatables.net/v/dt/dt-1.12.1/date-1.1.2/sb-1.3.4/datatables.min.js"></script>

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
		<?php
		\Bitrix\Main\Loader::includeModule('crm');


		use Bitrix\Crm\Category\DealCategory;
		use CCrmDeal;
        use CCrmContact;

		//use CUser;
        $contact = CCrmContact::GetList([], ['ID' => 517], [], []);
        $contactData = [];
        $contactData[] = $contact->Fetch();
        //var_dump($contactData);
		//$category = DealCategory::getStageList('16');
		//var_dump($category);
		//$deal = CCrmDeal::GetListEx($arOrder = array(), $arFilter = array(['CATEGORY_ID' => '16']), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array(), $arOptions = array());
		$deal = CCrmDeal::GetListEx([], ['CATEGORY_ID' => '16'], false, false, ['*', 'UF_CRM_1663748579248']);
		$dealData = [];
		while ($row = $deal->Fetch()) {
					/*$dealData[$row['ID']] =
						['Название сделки' => $row['TITLE'],
							'Ответственный' => $row['ASSIGNED_BY_ID'],
							'Дата создания' => $row['DATE_CREATE'],
							'Сумма' => $row['OPPORTUNITY'],
							'Стадия' => $row['STAGE_ID'],
							'ID контакта' => $row['CONTACT_ID']];*/
          $dealData[$row['ID']] = $row;
		}
		echo "<div>";
		var_dump($dealData);
		echo "</div>";

		?>
    <div class="container">
        <div class="table_and_chart">
            <table id="myTable" class="display dataTable">
            </table>
            <canvas id="myChart"></canvas>
        </div>
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