<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<?php
$deals_id = [];
$dealData = [];
if (!empty($_GET["deals_id"])) {
	$deals_id = $_GET["deals_id"];

	$Deal = CCrmDeal::GetListEx([],
		['CATEGORY_ID' => '16', 'ID' => $deals_id],
		false,
		false,
		['*', 'UF_CRM_1663748459170', 'UF_CRM_1663748481446']);

	while ($record = $Deal->Fetch()) {
		$dealData[] = [
			'Id' => '<a href="https://crm.25s.by/crm/deal/details/' . $record['ID'] . '/">' . $record['ID']. '</a>',
			'Название' => $record['TITLE'],
			'Контакт' => $record['CONTACT_FULL_NAME'],
			'Дата принятия в работу' => $record['UF_CRM_1663748459170'],
			'Дата оплаты' => $record['UF_CRM_1663748481446'],
			'Дата завершения' => $record['CLOSEDATE']
		];
	}
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs5/dt-1.12.1/date-1.1.2/sb-1.3.4/sp-2.0.2/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="styles/style.css">

    <script type=" text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript"
            src="https://cdn.datatables.net/v/bs5/dt-1.12.1/date-1.1.2/sb-1.3.4/sp-2.0.2/datatables.min.js"></script>
</head>
<body>
<div class="container">
    <a href="index.php">Назад</a>
    <table id="dealsTable" class="table-hover table-bordered border border-dark"></table>
</div>
<script type="text/javascript">
	$(document).ready(function () {
      let dataSet = <?php echo json_encode($dealData) ?>;
			console.log(dataSet);
		$('#dealsTable').DataTable({
			language: {
				url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json"
			}, //язык интерфейса самой таблицы
			processing: true, //индикатор загрузки
			data: dataSet,
			columns: [
				{data: 'Id', title: 'Id'},
				{data: 'Название', title: 'Название'},
				{data: 'Контакт', title: 'Контакт'},
				{data: 'Дата принятия в работу', title: 'Дата принятия в работу'},
				{data: 'Дата оплаты', title: 'Дата оплаты'},
				{data: 'Дата завершения', title: 'Дата завершения'},
			],
		});
	});
</script>
</body>
</html>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
