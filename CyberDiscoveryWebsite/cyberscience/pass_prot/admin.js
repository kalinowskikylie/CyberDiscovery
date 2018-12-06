$(document).ready(function() {
	
	$('a[name="addentry"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>ADD MODE | </span><a href="admin.php">Nevermind.</a>');
		$('#userTable tr:first-child').after('<tr style="background-color:#DDD;"><td><input type="text" name="newfName" id="newfName" /></td><td><input type="text" name="newlName" id="newlName" /></td><td><input type="text" name="newemail" id="newemail" /></td><td><input type="text" name="newpassword" id="newpassword" /></td><td><input type="text" name="newpermissions" id="newpermissions" /><input type="submit" value="Add User" /></td></tr>');
		$('#newfName').focus();
		$('input[type="submit"]').click(function() {
			e.preventDefault();
			var newfName = $('#newfName').val();
			var newlName = $('#newlName').val();
			var newemail = $('#newemail').val();
			var newpassword = $('#newpassword').val();
			var newpermissions = $('#newpermissions').val();
			$.ajax({
				type: "POST",
				url: "process_CRUD.php",
				data: "newfName=" + newfName + "&newlName=" + newlName + "&newemail=" + newemail + "&newpassword=" + newpassword + "&newpermissions=" + newpermissions + "",
				success: function(data){
					window.location = "admin.php?add_success=1";
			   }
			});
		});
	});
	
	$('a[name="editentry"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>EDIT MODE | </span><a href="admin.php">Nevermind.</a>');	
		$('.dynamic').css('background-color','#AAF');
		$('.dynamic').hover(
			  function () {
				$(this).css('background-color','#77F').css("cursor","pointer");
			  }, 
			  function () {
				$(this).css('background-color','#AAF').css("cursor","default");
			  }
		);
		$('.dynamic').click(function() {
			$('.dynamic').unbind();
			$(this).css('background-color','#DDD').css("cursor","default");
			var editUser = $(this).attr('name');
			var oldfName = $(this).children('td').first().text();
			var oldlName = $(this).children('td').eq(1).text();
			var oldemail = $(this).children('td').eq(2).text();
			var oldpassword = $(this).children('td').eq(3).text();
			var oldhpassword = $(this).children('td').eq(3).attr('name');
			var oldpermissions = $(this).children('td').eq(4).text();
			$(this).html('<td><input type="text" name="newfName" id="newfName" value="'+ oldfName +'" /></td><td><input type="text" name="newlName" id="newlName" value="'+ oldlName +'" /></td><td><input type="text" name="newemail" id="newemail" value="'+ oldemail +'" /></td><td><input type="text" name="newpassword" id="newpassword" value="'+ oldpassword +'" /><input type="hidden" name="newhpassword" id="newhpassword" value="'+ oldhpassword +'" /></td><td><input type="text" name="newpermissions" id="newpermissions" value="'+ oldpermissions +'" /><input type="submit" value="Edit User" /></td>');
			$('#newfName').focus();
			$('input[type="submit"]').click(function() {
			e.preventDefault();
			var newfName = $('#newfName').val();
			var newlName = $('#newlName').val();
			var newemail = $('#newemail').val();
			var newpassword = $('#newpassword').val();
			var newhpassword = $('#newhpassword').val();
			var newpermissions = $('#newpermissions').val();
			$.ajax({
				type: "POST",
				url: "process_CRUD.php",
				data: "editUser=" + editUser + "&editfName=" + newfName + "&editlName=" + newlName + "&editemail=" + newemail + "&editpassword=" + newpassword + "&edithpassword=" + newhpassword + "&editpermissions=" + newpermissions + "",
				success: function(data){
					$('.dynamic').unbind();
					window.location = "admin.php?edit_success=1";
			   }
			});
		});						
		});		 
	});
	
	$('a[name="deleteentry"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>DELETE MODE | </span><a href="admin.php">Nevermind.</a>');	
		$('.dynamic').css('background-color','#FAA');
		$('.dynamic').hover(
			  function () {
				$(this).css('background-color','#F77').css("cursor","pointer");
			  }, 
			  function () {
				$(this).css('background-color','#FAA').css("cursor","default");
			  }
		);
		$('.dynamic').click(function() {
			var deleteUser = $(this).attr('name');
			var deletefName = $(this).children('td').first().text();
			var deletelName = $(this).children('td').eq(1).text();
			var answer = confirm('Delete ' + deletefName + ' ' + deletelName + '?');
			if (answer){
				$('.dynamic').unbind();
				$.ajax({
					type: "POST",
					url: "process_CRUD.php",
					data: "deleteUser=" + deleteUser + "",
					success: function(data){
						$('.dynamic').unbind();
						window.location = "admin.php?delete_success=1";
					}
				});
			}
			else{
				var deleteUser = '';
				var deletefName = '';
				var deletelName = '';
			}
		});
	});
});