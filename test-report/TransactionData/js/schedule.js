var chartStage;

$(document).ready(function () {
	$.ajax({
		type: 'POST',
		url: 'handler.php',
		//contentType: false,
		//cache: false,
		//processData: false,
		success: function (response) {
			let Data = JSON.parse(response);

			let chartData = Data.chart;
			let xData = [];
			let yData = [];
			for (let key in chartData) {
				xData.push(key);
				yData.push(chartData[key]);
			}

			if (chartStage) {
				chartStage.destroy();
			}

			chartStage = new Chart($("#chartStage"), {
				type: 'bar',
				data: {
					labels: xData, //ось x
					datasets: [{
						categoryPercentage: 0.95,
						barPercentage: 1.0,
						data: yData, //ось y данные в виде массива с числами, количество должно совпадать с количеством меток по оси X
						borderColor: [
							'rgb(248,220,86)',
							'rgb(86,132,248)',
							'rgb(111,111,111)',
							'rgb(114,248,111)',
						], //цвета линий обводки
						backgroundColor: [
							'rgb(248,220,86)',
							'rgb(86,132,248)',
							'rgb(111,111,111)',
							'rgb(114,248,111)',
						], //цвета заливки под линиями
						borderWidth: 2, // назначаем ширину линий
						hoverBackgroundColor: [
							'rgba(248,220,86,0.7)',
							'rgb(86,132,248,0.7)',
							'rgb(111,111,111,0.7)',
							'rgb(114,248,111,0.7)',
						],
					}],
				},
				options: {
					scales: {
						xAxes: {
							type: 'category',
							title: {
								display: true,
								text: "Стадии",
								color: 'rgba(51, 51, 51, 0.8)',
								font: {
									size: 20,
								}
							},
							grid: {
								borderColor: 'black',
							},
							ticks: {
								color: 'rgba(250,42,42,0.8)',
							},
						},
						yAxes: {
							type: 'linear',
							title: {
								display: true,
								text: "Сумма",
								color: 'rgba(51, 51, 51, 0.8)',
								font: {
									size: 20,
								},
							},
							grid: {
								borderColor: 'black',
							},
							ticks: {
								beginAtZero: true,
								stepSize: 500
							},
						},
					},
					plugins: {
						title: {
							display: true,
							text: 'Общие показатели сумм по стадиям сделок за установленный перод времени'
						},
						legend: {
							display: false,
						},
						tooltip: {
							callbacks: {
								label: function (context) {
									let label = 'Сумма : ' + context.parsed.y + ' руб.';
									return label;
								}
							}
						}
					},
				},
				plugins: [],
			});
		},
	})
})