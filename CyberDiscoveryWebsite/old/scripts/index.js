$(document).ready(function() {
	
	$('span[name="addblog"]').click(function() {
		$('#adminsection').hide();
		var blogosphere = $('#newblogcontainer').html();
		$('#newblogcontainer').html('<div style="background-color:#EEE;"><form name="newblog" action="bin/blog_upload.php" method="post" enctype="multipart/form-data">Picture:(must be less than 8Mb)<input type="file" name="newpic" id="newpic" ><textarea name="newblogcontent" id="newblogcontent" onFocus="rte(newblogcontent)" cols="95" rows="25">' +blogosphere + '</textarea><br /><input type="submit" value="Submit"></form></div>');
		$('#newblogcontainer').children('div').children('form').children('textarea').focus();
	});
	
	$('span[name="editblog"]').click(function() {
		$('#adminsection').hide();
		$('#blogcontainer').children('div').removeClass('blogpost').addClass('editpost');
		$('#blogcontainer').children('div').hover(
			  function () {
				$(this).removeClass('editpost').addClass('hoverpost');
			  }, 
			  function () {
				$(this).removeClass('hoverpost').addClass('editpost');
			  }
		);
		$('#blogcontainer').children('div').click(function() {
			var editblogosphere = $(this).children('.content').html();
			var editblognumber = $(this).attr("name");
			var editpicnumber = $(".blogpic").attr("name");
			$(this).before('<div class="blogpost"><form name="editblog" action="bin/blog_upload.php" method="post" enctype="multipart/form-data">Picture:(must be less than 8Mb)<input type="file" name="editpic" id="editpic" ><input type="hidden" name="editpicnumber" id="editpicnumber" value="'+ editpicnumber +'"><input type="hidden" name="editblognumber" id="editblognumber" value="'+ editblognumber +'"><textarea name="editblogcontent" id="editblogcontent" style="width:100%;" rows="25">' +editblogosphere +'</textarea><br /><input type="submit" value="Submit"></form></div>');
			tinyMCE.execCommand('mceAddControl', false, 'editblogcontent');
			$(this).remove();
			$('#blogcontainer').children('div').removeClass('editpost').addClass('blogpost');
			$('#blogcontainer').children('div').children('form').children('textarea').focus();
		});
	});
	
	$('span[name="deleteblog"]').click(function() {
		$('#adminsection').hide();
		$('#blogcontainer').children('a').children('div').removeClass('blogpost').addClass('editpost').prepend('<div class="deleteblogbutton">X</div>').unwrap('a');
		$('.deleteblogbutton').click(function() {
			var deletepicnumber = $(this).parent().children('[name="bpic"]').children('img').attr("name");
			var deleteblognumber = $(this).parent().attr("name");
			var answer = confirm("Are you sure you would like to delete this entry?")
			if (answer){
				$(this).parent().hide();
				$('.deleteblogbutton').hide();
				$('#blogcontainer').children('a').children('div').removeClass('editpost').addClass('blogpost');
				$.ajax({
				   type: "POST",
				   url: "bin/blog_upload.php",
				   data: "deleteblognumber="+ deleteblognumber + "&deletepicnumber="+ deletepicnumber +"",
				   success: function(){
					 window.location = "http://cyberdiscovery.latech.edu/index.php";
				   }
				 });
			}
		});
	});
	$('#login_success').delay(1500).slideUp();
});