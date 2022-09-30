var first_date;
var last_date;

$('#Date').submit(function (e) {
	e.preventDefault();
	first_date = $("#first_date").val();
	last_date = $("#last_date").val();
	//console.log(first_date);
	//console.log(last_date);
	//console.log(flightChart);
	if (first_date != '' && last_date != '') {
		data = { "first_date": first_date, "last_date": last_date };
		$('#myTable').DataTable().destroy();
		table(first_date, last_date);
	} else {
		$('#myTable').DataTable().destroy();
		table();
	}
});



function table(first_date = '', last_date = '') {
	$('#myTable').DataTable({
		"language": {
			"url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json"
		}, //язык интерфейса самой таблицы
		'processing': true, //индикатор загрузки
		'serverSide': true, //обработка на стороне сервера
		'serverMethod': 'post',
		'ajax': {
			'url': ' ', //источник данных ajax для таблицы
			'data': { 'first_date': first_date, 'last_date': last_date },
		},
		'columns': [
			{ data: 'trip_no', title: 'Номер рейса' },
			{ data: 'date', title: 'Дата' },
			{ data: 'ID_psg', title: 'ID пассажира' },
			{ data: 'place', title: 'Место' }
		],
		"drawCallback": function (settings) {}
	})
}

/*$(document).ready(function () {
	table();
});*/
