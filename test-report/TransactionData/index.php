<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<?php
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
$usersID = array_merge($admins, $usersID);

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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"
                integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.2.1/chartjs-plugin-zoom.min.js"
                integrity="sha512-klQv6lz2YR+MecyFYMFRuU2eAl8IPRo6zHnsc9n142TJuJHS8CG0ix4Oq9na9ceeg1u5EkBfZsFcV3U7J51iew=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <body>
    <script type="text/javascript" src="js/table.js"></script>
    <script type="text/javascript" src="js/schedule.js"></script>
    <div class="container">
        <form class="variableDate" id="Date" method="post">
            <div class="DateSet">
                <div class="fromDate">
                    <label>Введите начальную дату:</label>
                    <input type="date" class="form-control" id="first_date" name="first_date">
                </div>
                <div class="toDate">
                    <label>Введите конечную дату:</label>
                    <input type="date" class="form-control" id="last_date" name="last_date">
                </div>
            </div>
            <input class="btn btn-primary" type="submit" name="done" id="done" value="Показать диапазон по выбранным датам">
        </form>
    </div>

    <div class="container">
        <p class="banner">Статистика пользователей за весь период</p>
        <table id="myTable" class="table table-hover table-bordered border border-dark">
            <thead class="align-middle">
            <tr>
                <th class="text-center" rowspan="3">Сотрудники</th>
                <th class="text-center" colspan="6">Тип клиента</th>
                <th class="text-center" rowspan="3">На складе</th>
                <th class="text-center" rowspan="2" colspan="2">Брак</th>
            </tr>
            <tr class="text-center">
                <th class="text-center" colspan="2" style="background-color: rgb(220,233,245)">Юр.лицо</th>
                <th class="text-center" colspan="2" style="background-color: rgb(250,226,213)">Физ.дицо</th>
                <th class="text-center" colspan="2" style="background-color: rgb(253,240,203)">ИП</th>
            </tr>
            <tr class="text-center">
                <th style="background-color: rgb(220,233,245)">Кол-во</th>
                <th style="background-color: rgb(220,233,245)">Сумма</th>
                <th style="background-color: rgb(250,226,213)">Кол-во</th>
                <th style="background-color: rgb(250,226,213)">Сумма</th>
                <th style="background-color: rgb(253,240,203)">Кол-во</th>
                <th style="background-color: rgb(253,240,203)">Сумма</th>
                <th>Кол-во</th>
                <th>Сумма</th>
            </tr>
            </thead>
            <tbody class="table-group-divider">
            </tbody>
            <tfoot>
            <tr style="background-color: rgba(189,250,228,0.7)">
                <td>Всего закрыто:</td>
                <td class="Total text-center" colspan="9"></td>
            </tr>
            <tr style="background-color: rgba(101,192,103,0.7)">
                <td>Закрыто на сумму:</td>
                <td class="Sum text-center" colspan="9"></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="container">
        <canvas id="chartStage"></canvas>
    </div>
    </body>
    </html>';
} else {
	echo "К сожалению у вас нет доступа к этим данным (=";
}
?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>