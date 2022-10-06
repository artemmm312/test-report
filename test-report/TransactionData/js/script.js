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
		data = {"first_date": first_date, "last_date": last_date};
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
		//'processing': true, //индикатор загрузки
		//'serverSide': true, //обработка на стороне сервера
		//'serverMethod': 'post',
		//'ajax': {
		//'url': ' ', //источник данных ajax для таблицы
		//'data': { 'first_date': first_date, 'last_date': last_date },
		//},
		'data': [{
			'Сотрудник': 'Никитин Артём',
			'Ю-к': '2',
			'Ю-с': '2500',
			'Ф-к': '3',
			'Ф-с': '3500',
			'И-к': '4',
			'И-с': '4500',
			'На складе': '5',
			'Б-к': '6',
			'Б-с': '6000'
		},
			{
				'Сотрудник': 'Нестеренко Артём',
				'Ю-к': '2',
				'Ю-с': '2500',
				'Ф-к': '3',
				'Ф-с': '3500',
				'И-к': '4',
				'И-с': '4500',
				'На складе': '5',
				'Б-к': '6',
				'Б-с': '6000'
			},
		],
		'columns': [
			{data: 'Сотрудник'},
			{data: 'Ю-к'},
			{data: 'Ю-с'},
			{data: 'Ф-к'},
			{data: 'Ф-с'},
			{data: 'И-к'},
			{data: 'И-с'},
			{data: 'На складе'},
			{data: 'Б-к'},
			{data: 'Б-с'},

		],
		"drawCallback": function (settings) {
		}
	})
}

/*$(document).ready(function () {
	table();
});*/
function test() {
	$('#myTable').DataTable({
		"language": {
			"url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json"
		}, //язык интерфейса самой таблицы
		//'processing': true, //индикатор загрузки
		//'serverSide': true, //обработка на стороне сервера
		//'serverMethod': 'post',
		//'ajax': {
			//'url': 'handler.php ', //источник данных ajax для таблицы
		//},
		//'data': { 'first_date': first_date, 'last_date': last_date },
		//},

		'columns': [
			{data: 'employee'},
			{data: 'U_c'},
			{data: 'U_s'},
			{data: 'F_c'},
			{data: 'F_s'},
			{data: 'I_c'},
			{data: 'I_s'},
			{data: 'stock'},
			{data: 'B_c'},
			{data: 'B_s'},
		],
		"drawCallback": function (settings) {
			let tt = settings.json.aaData;
			console.log(tt);
		}
	});
}

test();