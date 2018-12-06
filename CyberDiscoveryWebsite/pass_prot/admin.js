$(document).ready(function() {
	var perm = $('#foobar').attr('name');
//-------------------USERS-------------------------------------	
	$('a[name="addentry"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>ADD MODE | </span><a href="admin.php">Nevermind.</a>');
		var selectrole = $('#roles').html();
		if(perm == '1'){
			var selectcamp = $('#campnames').html();
			$('#userTable tr:first-child').after('<tr style="background-color:#DDD; vertical-align:top;"><td><input style="width:95%;" type="text" name="newfName" id="newfName" /></td><td><input style="width:95%;" type="text" name="newlName" id="newlName" /></td><td><span id="newteam">'+ selectcamp +'</span></td><td><input style="width:95%;" type="text" name="newemail" id="newemail" /></td><td><input style="width:95%;" type="text" name="newpassword" id="newpassword" /></td><td><span id="newpermissions">'+ selectrole +'</span><input style="width:95%;" type="submit" value="Add User" /></td></tr>');
		} else {
			$('#userTable tr:first-child').after('<tr style="background-color:#DDD; vertical-align:top;"><td><input style="width:95%;" type="text" name="newfName" id="newfName" /></td><td><input style="width:95%;" type="text" name="newlName" id="newlName" /></td><td><input style="width:95%;" type="text" name="newemail" id="newemail" /></td><td><input style="width:95%;" type="text" name="newpassword" id="newpassword" /></td><td><span id="newpermissions">'+ selectrole +'</span><input style="width:95%;" type="submit" value="Add User" /></td></tr>');
		}
		$('#newfName').focus();
		$('input[type="submit"]').click(function() {
			e.preventDefault();
			var newfName = $('#newfName').val();
			var newlName = $('#newlName').val();
			//get check box values and store them as CSV
			var newteam = '';
			if(perm == '1'){
			$('#newteam').find('input:checked').each(function(){
				newteam += $(this).val() + ',';
			});
			//End Checkboxes
			}
			var newemail = $('#newemail').val();
			var newpassword = $('#newpassword').val();
			var newpermissions = $('#newpermissions').find('select').val();
			$.ajax({
				type: "POST",
				url: "process_CRUD.php",
				data: "newfName=" + newfName + "&newlName=" + newlName + "&camp=" + newteam + "&newemail=" + newemail + "&newpassword=" + newpassword + "&newpermissions=" + newpermissions + "",
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
			$(this).css('background-color','#DDD').css("cursor","default").css("vertical-align","top");
			if(perm == '1'){
				var selectcamp = $('#campnames').html();
			}
			var selectrole = $('#roles').html();
			var editUser = $(this).attr('name');
			var oldfName = $(this).children('td').first().text();
			var oldlName = $(this).children('td').eq(1).text();
			if(perm == '1'){
				var oldteam = $(this).children('td').eq(2).text();
				var oldemail = $(this).children('td').eq(3).text();
				var oldpassword = $(this).children('td').eq(4).text();
				var oldhpassword = $(this).children('td').eq(4).attr('name');
				var oldpermissions = $(this).children('td').eq(5).attr('name');
			} else {
				var oldemail = $(this).children('td').eq(2).text();
				var oldpassword = $(this).children('td').eq(3).text();
				var oldhpassword = $(this).children('td').eq(3).attr('name');
				var oldpermissions = $(this).children('td').eq(4).attr('name');
			}
			var unid = $(this).attr('unid');
			if(perm == '1'){
				$(this).html('<td name='+ oldteam +'><input style="width:95%;" type="text" name="newfName" id="newfName" value="'+ oldfName +'" /></td><td><input style="width:95%;" type="text" name="newlName" id="newlName" value="'+ oldlName +'" /></td><td><span id="newteam">'+ selectcamp +'</span></td><td><input style="width:95%;" type="text" name="newemail" id="newemail" value="'+ oldemail +'" /></td><td><input style="width:95%;" type="text" name="newpassword" id="newpassword" value="'+ oldpassword +'" /><input style="width:95%;" type="hidden" name="newhpassword" id="newhpassword" value="'+ oldhpassword +'" /></td><td><span id="newpermissions">'+ selectrole +'</span><input style="width:95%;" type="submit" value="Edit User" /></td>');
				if(oldteam.search(/[A-z]/) != -1){
					var oldteamArray = oldteam.split(', ');
					for(var i=0;i<oldteamArray.length;i++){
						spacestripped = oldteamArray[i].replace(/[ ]/g,'');
						$('.'+spacestripped).prop('checked',true);
					}
				}
			} else {
				$(this).html('<td><input style="width:95%;" type="text" name="newfName" id="newfName" value="'+ oldfName +'" /></td><td><input style="width:95%;" type="text" name="newlName" id="newlName" value="'+ oldlName +'" /></td><td><input style="width:95%;" type="text" name="newemail" id="newemail" value="'+ oldemail +'" /></td><td><input style="width:95%;" type="text" name="newpassword" id="newpassword" value="'+ oldpassword +'" /><input style="width:95%;" type="hidden" name="newhpassword" id="newhpassword" value="'+ oldhpassword +'" /></td><td><span id="newpermissions">'+ selectrole +'</span><input style="width:95%;" type="submit" value="Edit User" /></td>');
			}
			$('#newpermissions').find('select').val(oldpermissions);
			$('#newfName').focus();
			$('input[type="submit"]').click(function() {
			e.preventDefault();
			var newfName = $('#newfName').val();
			var newlName = $('#newlName').val();
			//get check box values and store them as CSV
			var newteam = '';
			if(perm == '1'){
			$('#newteam').find('input:checked').each(function(){
				newteam += $(this).val() + ',';
			});
			}
			//End Checkboxes
			var newemail = $('#newemail').val();
			var newpassword = $('#newpassword').val();
			var newhpassword = $('#newhpassword').val();
			var newpermissions = $('#newpermissions').find('select').val();
			$.ajax({
				type: "POST",
				url: "process_CRUD.php",
				data: "editUser=" + editUser + "&editfName=" + newfName + "&editlName=" + newlName + "&camp=" + newteam + "&editemail=" + newemail + "&editpassword=" + newpassword + "&edithpassword=" + newhpassword + "&editpermissions=" + newpermissions + "&unid=" + unid + "",
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
			var deleteUser = $(this).attr('unid');
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
//-------------------TEAMS-------------------------------------
	$('a[name="addteam"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>ADD MODE | </span><a href="admin.php?curPage=teams">Nevermind.</a>');
		if(perm == '1'){
			var selectcamp = $('#campnames').html();
			$('#userTable tr:first-child').after('<tr style="background-color:#DDD; vertical-align:top;"><td><input style="width:95%;" type="text" name="newteam_name" id="newteam_name" /></td><td><span id="newteam_camp">'+ selectcamp +'</span></td><td><input style="width:95%;" type="text" name="newteam_email" id="newteam_email" /></td><td><input style="width:95%;" type="text" name="newteam_password" id="newteam_password" /><input style="width:95%;" type="submit" value="Add Team" /></td></tr>');

		} else {
			$('#userTable tr:first-child').after('<tr style="background-color:#DDD; vertical-align:top;"><td><input style="width:95%;" type="text" name="newteam_name" id="newteam_name" /></td><td><input style="width:95%;" type="text" name="newteam_email" id="newteam_email" /></td><td><input style="width:95%;" type="text" name="newteam_password" id="newteam_password" /><input style="width:95%;" type="submit" value="Add Team" /></td></tr>');
		}
		$('#newteam_name').focus();
		$('input[type="submit"]').click(function() {
			e.preventDefault();
			var newteam_name = $('#newteam_name').val();
			if(perm == '1'){
				var newteam_camp = $('#newteam_camp').find('select').val();
			} else {
				var newteam_camp = '';
			}
			var newteam_email = $('#newteam_email').val();
			var newteam_password = $('#newteam_password').val();
			$.ajax({
				type: "POST",
				url: "process_CRUD.php",
				data: "newteam_name=" + newteam_name + "&camp=" + newteam_camp + "&newteam_email=" + newteam_email + "&newteam_password=" + newteam_password + "",
				success: function(data){
					window.location = "admin.php?add_success=1&curPage=teams";
			   }
			});
		});
	});
	
	$('a[name="editteam"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>EDIT MODE | </span><a href="admin.php?curPage=teams">Nevermind.</a>');	
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
			$(this).css('background-color','#DDD').css("cursor","default").css("vertical-align","top");
			var selectcamp = $('#campnames').html();
			var oldteam_name = $(this).children('td').first().text();
			if(perm == '1'){
				var oldteam_camp = $(this).children('td').eq(1).attr('name'); 
				var oldteam_email = $(this).children('td').eq(2).text();
				var oldteam_password = $(this).children('td').eq(3).text();
				var oldteam_hpassword = $(this).children('td').eq(3).attr('name');
			} else {
				var oldteam_email = $(this).children('td').eq(1).text();
				var oldteam_password = $(this).children('td').eq(2).text();
				var oldteam_hpassword = $(this).children('td').eq(2).attr('name');
			}
			var unid = $(this).attr('unid');
			if(perm == '1'){
				$(this).html('<td><input style="width:95%;" type="text" name="newteam_name" id="newteam_name" value="'+ oldteam_name +'" /></td><td><span id="newteam_camp">'+ selectcamp +'</span></td><td><input style="width:95%;" type="text" name="newteam_email" id="newteam_email" value="'+ oldteam_email +'" /></td><td><input style="width:95%;" type="text" name="newpassword" id="newpassword" value="'+ oldteam_password +'" /><input style="width:95%;" type="hidden" name="newhpassword" id="newhpassword" value="'+ oldteam_hpassword +'" /><input style="width:95%;" type="submit" value="Edit Team" /></td>');
				oldteam_camp = oldteam_camp.replace(/[ ]/g,'');
				$('#newteam_camp').find('select').val(oldteam_camp);
			} else {
				$(this).html('<td><input style="width:95%;" type="text" name="newteam_name" id="newteam_name" value="'+ oldteam_name +'" /></td><td><input style="width:95%;" type="text" name="newteam_email" id="newteam_email" value="'+ oldteam_email +'" /></td><td><input style="width:95%;" type="text" name="newpassword" id="newpassword" value="'+ oldteam_password +'" /><input style="width:95%;" type="hidden" name="newhpassword" id="newhpassword" value="'+ oldteam_hpassword +'" /><input style="width:95%;" type="submit" value="Edit Team" /></td>');
			}
			$('#newteam_name').focus();
			$('input[type="submit"]').click(function() {
				e.preventDefault();
				var newteam_name = $('#newteam_name').val();
				if(perm == '1'){
					var newteam_camp = $('#newteam_camp').find('select').val();
				} else {
					var newteam_camp = '';
				}
				var newteam_email = $('#newteam_email').val();
				var newpassword = $('#newpassword').val();
				var newhpassword = $('#newhpassword').val();
				
				$.ajax({
					type: "POST",
					url: "process_CRUD.php",
					data: "editteam_name=" + newteam_name + "&camp=" + newteam_camp + "&editteam_email=" + newteam_email + "&editteam_password=" + newpassword + "&editteam_hpassword=" + newhpassword + "&unid=" + unid + "",
					success: function(data){
						$('.dynamic').unbind();
						window.location = "admin.php?edit_success=1&curPage=teams";
					}
				});
			});						
		});		 
	});
	
	$('a[name="deleteteam"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>DELETE MODE | </span><a href="admin.php?curPage=teams">Nevermind.</a>');	
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
			var deleteteam_unid = $(this).attr('unid');
			var deleteteam_name = $(this).children('td').first().text();
			var answer = confirm('Delete ' + deleteteam_name + '?');
			if (answer){
				$('.dynamic').unbind();
				$.ajax({
					type: "POST",
					url: "process_CRUD.php",
					data: "deleteteam_unid=" + deleteteam_unid + "",
					success: function(data){
						$('.dynamic').unbind();
						window.location = "admin.php?delete_success=1&curPage=teams";
					}
				});
			}
			else{
				var deleteUNID = '';
				var deleteteam_name = '';
			}
		});
	});
//-------------------CAMPS-------------------------------------
	$('a[name="addcamp"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>ADD MODE | </span><a href="admin.php?curPage=camps">Nevermind.</a>');
		$('#userTable tr:first-child').after('<tr style="background-color:#DDD; vertical-align:top;"><td><input style="width:95%;" type="text" name="newcamp_name" id="newcamp_name" /></td><td><input style="width:95%;" type="text" name="newcamp_start" id="newcamp_start" class="datepicker" /></td><td><input style="width:95%;" type="text" name="newcamp_end" id="newcamp_end" class="datepicker" /><input style="width:95%;" type="submit" value="Add Camp" /></td></tr>');
		$( ".datepicker" ).datepicker();
		$('#newcamp_name').focus();
		$('input[type="submit"]').click(function() {
			e.preventDefault();
			var newcamp_name = $('#newcamp_name').val();
			var newcamp_start = $('#newcamp_start').val();
			var newcamp_end = $('#newcamp_end').val();
			$.ajax({
				type: "POST",
				url: "process_CRUD.php",
				data: "newcamp_name=" + newcamp_name + '&camp' + newcamp_name + "&newcamp_start=" + newcamp_start + "&newcamp_end=" + newcamp_end + "",
				success: function(data){
					window.location = "admin.php?add_success=1&curPage=camps";
			   }
			});
		});
	});
	
	$('a[name="editcamp"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>EDIT MODE | </span><a href="admin.php?curPage=camps">Nevermind.</a>');	
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
			$(this).css('background-color','#DDD').css("cursor","default").css("vertical-align","top");
			var oldcamp_name = $(this).children('td').first().text();
			var oldcamp_start = $(this).children('td').eq(1).text();
			var oldcamp_end = $(this).children('td').eq(2).text();
			var unid = $(this).attr('unid');
			$(this).html('<td><input style="width:95%;" type="text" name="newcamp_name" id="newcamp_name" value="'+ oldcamp_name +'" /></td><td><input style="width:95%;" type="text" name="newcamp_start" id="newcamp_start" value="'+ oldcamp_start +'" class="datepicker" /></td><td><input style="width:95%;" type="text" name="newcamp_end" id="newcamp_end" value="'+ oldcamp_end +'" class="datepicker" /><input style="width:95%;" type="submit" value="Edit Camp" /></td>');
			$( ".datepicker" ).datepicker();
			$('#newcamp_name').focus();
			$('input[type="submit"]').click(function() {
				e.preventDefault();
				var newcamp_name = $('#newcamp_name').val();
				var newcamp_start = $('#newcamp_start').val();
				var newcamp_end = $('#newcamp_end').val();
				
				$.ajax({
					type: "POST",
					url: "process_CRUD.php",
					data: "editcamp_name=" + newcamp_name + '&camp' + newcamp_name + "&editcamp_start=" + newcamp_start + "&editcamp_end=" + newcamp_end + "&unid=" + unid + "",
					success: function(data){
						$('.dynamic').unbind();
						window.location = "admin.php?edit_success=1&curPage=camps";
					}
				});
			});						
		});		 
	});
	
	$('a[name="deletecamp"]').click(function(e) {
		e.preventDefault();
		$('#navAdmin').html('<span>DELETE MODE | </span><a href="admin.php?curPage=camps">Nevermind.</a>');	
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
			var deletecamp_unid = $(this).attr('unid');
			var deletecamp_name = $(this).children('td').first().text();
			var answer = confirm('Delete ' + deletecamp_name + '?');
			if (answer){
				$('.dynamic').unbind();
				$.ajax({
					type: "POST",
					url: "process_CRUD.php",
					data: "deletecamp_unid=" + deletecamp_unid + "",
					success: function(data){
						$('.dynamic').unbind();
						window.location = "admin.php?delete_success=1&curPage=camps";
					}
				});
			}
			else{
				var deleteUNID = '';
				var deletecamp_name = '';
			}
		});
	});
});