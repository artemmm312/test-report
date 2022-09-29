$(document).ready(function () {

	var usersID = [];
	var names = [];
	var users = [];

	function pushUsers() {  // запись в массив порльзовыателей из списка
		users.length = 0;
		let length = $('.user_list li').length;
		for (let i = 0; i < length; i++) {
			users.push({id: $('.user_list li').eq(i).val(), name: $('.user_list li').eq(i).text()});
		}
	}
	pushUsers();

	function disOption() { //отключение пунктов в селекте которые есть в списке
		$('#myS option').prop('disabled', false);
		for (let user of users) {
			$('#myS').find(`[value=${user['id']}]`).prop('disabled', true);
		}
		$('#myS').selectpicker('destroy');
		$('#myS').selectpicker('render');
	}
	disOption();

	function updateUsers() { //обнавление пользователей на сервере
		console.log(users);
		$.ajax({
			type: 'POST',
			url: 'handler.php',
			data: {'users': JSON.stringify(users)},
			success: function (response) {
				console.log(response);
			},
		})
	}

	$('#myS').change(function () { //выбор из селекта
		usersID = $('#myS').val(); //массив id выбранных пользователей
		if (usersID !== undefined) {
			usersID.length > 0 ? $('#myB').prop('disabled', false) : $('#myB').prop('disabled', true);
		}
	});

	$('#myB').on('click', function () {  //добавление в список
		for (let i = 0; i < usersID.length; i++) {
			names.push($(`#myS option[value=${usersID[i]}]`).text()) //массив фио выбранных пользователей
		}
		for (let i = 0; i < names.length; i++) {
			$('.user_list').append(`<li id="${usersID[i]}" value="${usersID[i]}">${names[i]}<button id="closeB" type="button" class="btn-close" aria-label="Close"></button></li>`);
		}
		names.length = 0;
		$('#myS').selectpicker('deselectAll');
		pushUsers();
		console.log(users);
		disOption();
		updateUsers();
	})

	$('.user_list').on('click', 'li #closeB', function () { //удаление пользователей
		$(this).parent().remove();
		pushUsers();
		disOption();
		updateUsers();
	})

});