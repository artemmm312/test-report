var first_date = '';
var last_date = '';

$('#Date').submit(function (e) {
	e.preventDefault();
	first_date = $("#first_date").val();
	last_date = $("#last_date").val();
	if (first_date != '' && last_date != '') {
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
		//'serverSide': true, //обработка на стороне сервера
		'serverMethod': 'post',
		'ajax': {
			'url': 'handler.php ', //источник данных ajax для таблицы
			'data': {'first_date': first_date, 'last_date': last_date},
		},
		'columns': [
			{data: 'Employee'},
			{data: 'U_c'},
			{data: 'U_s'},
			{data: 'F_c'},
			{data: 'F_s'},
			{data: 'I_c'},
			{data: 'I_s'},
			{data: 'Stock'},
			{data: 'B_c'},
			{data: 'B_s'},
		],
		"drawCallback": function (settings) {
		},
		"initComplete": function (settings, json) {
		},
		"footerCallback": function (tfoot, data, start, end, display) {
			var api = $('#myTable').dataTable().api();
			let Dtotal = 0;
			api.columns([1, 3, 5], {order: 'current', search: 'applied', page: 'current'}).every(function () {
				if (this.data().length) {
					let sum = this.data().reduce(function (a, b) {
						return a + b;
					});
					Dtotal += sum;
				}
			});
			$('.Total').html(Dtotal);
			let Dsum = 0;
			api.columns([2, 4, 6], {order: 'current', search: 'applied', page: 'current'}).every(function () {
				if (this.data().length) {
					let sum = this.data().reduce(function (a, b) {
						return a + b;
					});
					Dsum += sum;
				}
			});
			$('.Sum').html(Dsum);
		},
	});
}

$(document).ready(function () {
	table();
});


/*$.fn.dataTable.Api.register('sum()', function () {
		let sum = 0;
		for (let i = 0, ien = this.length; i < ien; i++) {
			sum += this[i];
		}
		return sum;
	});
	let api = $('#myTable').dataTable().api();
	let U_total = api.column(1, {order:'current', search:'applied', page:'current'}).data().sum();
				let F_total = api.column(3, {order:'current', search:'applied', page:'current'}).data().sum();
				let I_total = api.column(5, {order:'current', search:'applied', page:'current'}).data().sum();
				$('.Total').html(U_total + F_total + I_total)
				let U_sum = api.column(2, {order:'current', search:'applied', page:'current'}).data().sum();
				let F_sum = api.column(4, {order:'current', search:'applied', page:'current'}).data().sum();
				let I_sum = api.column(6, {order:'current', search:'applied', page:'current'}).data().sum();
				$('.Sum').html(U_sum + F_sum + I_sum)*/
