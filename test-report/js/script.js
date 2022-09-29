var usersID = [];
var names = [];
var users = [];


function pushUsers () {  // запись в массив порльзовыателей из списка
	users.length = 0;
	let length = $('.user_list li').length;
	for ( let i = 0; i < length; i++) {
		users.push({id: $('.user_list li').eq(i).val(), name: $('.user_list li').eq(i).text()});
	}
}
pushUsers();

function disOption () { //отключение пунктов в селекте которые есть в списке
	$('#myS option').prop('disabled', false);
	for (let user of users) {
				$('#myS').find(`[value=${user['id']}]`).prop('disabled', true);
	}
	$('#myS').selectpicker('destroy');
	$('#myS').selectpicker('render');
}
disOption();

const deleteUser = (id) => { //удаление пользователй из списка по кнопке
	$('#' + id).remove();
	pushUsers();
	disOption();
	updateUsers();
}

function updateUsers () { //обнавление пользователей на сервере
	users = JSON.stringify(users);
	$.ajax({
		type: 'POST',
		url: 'handler.php',
		data: {'users': users},
		success: function (response) {
			console.log(response);
		},
	})
}

/*$(document).ready(function () {

});*/

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
		$('.user_list').append(`<li id="${usersID[i]}" value="${usersID[i]}">${names[i]}<button id="closeB" type="button" class="btn-close" aria-label="Close" onclick="deleteUser(${usersID[i]});"></button></li>`);
	}
	names.length = 0;
	$('#myS').selectpicker('deselectAll');

	pushUsers();
	disOption();
	updateUsers();
	/*	for (let i = 0; i <usersID.length; i++) {
			users.push({id: usersID[i], name: names[i]})
		}*/
	//console.log(users);
	//users = JSON.stringify(users);

	/*$.ajax({
		type: 'POST',
		url: 'handler.php',
		data: {'users': users},
		success: function (response) {
			console.log(response);
		},
	})*/
})


