(function( $ ) {
	'use strict';

	function create_taqsk_tr(obj, name, description, dat){
		let tr = `<tr id="tr${obj['last_id']}">`;
		tr += `<td><input type='datetime-local'  id='task_dat${obj['last_id']}' value='${dat}' 
		</td>
		<td><input type='text' id='task_name${obj['last_id']}' value='${name}' /></td>
		<td><input type='text' id='task_description${obj['last_id']}' value='${description}' /></td>
		<td><input type='checkbox' id='task_is_done${obj['last_id']}' /></td>
		<td>
			<button class='edit_task' data-task_id='${obj['last_id']}'>Сохранить изменения</button>
			<button class='del_task' data-task_id='${obj['last_id']}'>Удалить</button>
		</td>`;
		tr += `</tr>`;
		return tr;
	}

	$(function() {
		$("#add_task").on('click', function() {
			let name = $("#new_task_name").val();	
			let description = $("#new_task_description").val();
			let dat = $("#new_task_dat").val();
			
			$.post("/wp-admin/admin-ajax.php", {
				"action": 'my_action_add_task',
				"name": name,
				"description": description,
				"dat": dat
			}).done(function(data) {
				let obj = JSON.parse(data);
				alert (obj["mess"]);
				if (obj["success"]){
					let tr = create_taqsk_tr(obj, name, description, dat);
					$('#tasks_table tr:first').after(tr);
				}
			});
		});

		$("#tasks_table").on('click', '.del_task', function(e){
			let task_id = $(this).data("task_id");
			$.post("/wp-admin/admin-ajax.php", {
				"action": 'my_action_del_task',
				"task_id": task_id
			}).done(function(data) {
				let obj = JSON.parse(data);
				if (obj["success"]){
					$("#tr"+task_id).remove();
				}
			});		
		});

		$("#tasks_table").on('click', '.edit_task', function(e){
			let task_id = $(this).data("task_id");
			let name = $("#task_name"+task_id).val();
			let description = $("#task_description"+task_id).val();
			let dat = $("#task_dat"+task_id).val();
			let is_done = $("#task_is_done"+task_id).is(':checked')

			$.post("/wp-admin/admin-ajax.php", {
				"action": 'my_action_edit_task',
				"task_id": task_id,
				"name": name,
				"description": description,
				"dat": dat,
				"is_done": is_done
			}).done(function(data) {
				let obj = JSON.parse(data);
				alert(obj["mess"]);
			});	
		});


	});

	 
})( jQuery );
