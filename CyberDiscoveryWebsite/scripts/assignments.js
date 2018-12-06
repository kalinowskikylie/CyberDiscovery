$(document).ready(function() {

	$('#addblog').click(function(event) {
		event.preventDefault();
		$('#adminsection').html('<a href="assignments.php">Nevermind.</a>');
		$('#newblogeditor').find('iframe').css('height','500px');
		$('#newblogeditor').show();
		$('#blog_title').focus();
		$( ".datepicker" ).datepicker();
	});
	
	$('#editblog').click(function(event) {
		event.preventDefault();
		$('#adminsection').html('<a href="assignments.php">Nevermind.</a>');
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
			$('#blogcontainer').children('div').unbind();
			var blog_unid = $(this).children('span[name="blog_unid"]').html();
			var blog_title = $(this).children('span[name="blog_title"]').html();
			var blog_content = $(this).children('span[name="blog_content"]').html();
			var blog_duepicker = $('#timedatesel').html();
			var oldhour = $(this).find('span[name="oldhour"]').html();
			var oldminute = $(this).find('span[name="oldminute"]').html();
			var oldperiod = $(this).find('span[name="oldperiod"]').html();
			var olddate = $(this).find('span[name="olddate"]').html();
			$('#timedatesel').remove();
			$(this).prepend('<input type="hidden" name="blog_unid" id="blog_unid" value="'+ blog_unid +'">');
			$(this).children('span[name="blog_title"]').html('<input type="text" name="blog_title" id="blog_title" value="'+ blog_title +'" />').before('Title: ');
			$(this).children('span[name="blog_due"]').html(blog_duepicker);
			$(this).find('select[name="hour"]').val(oldhour);
			$(this).find('select[name="minute"]').val(oldminute);
			$(this).find('select[name="period"]').val(oldperiod);
			$(this).find('input[name="date"]').val(olddate);
			$( ".datepicker" ).datepicker();
			$(this).children('span[name="blog_content"]').html('<textarea name="editblogcontent" id="editblogcontent" style="width:676px; height:500px;">'+ blog_content +'</textarea>').show();
			$(this).append('<input type="submit" value="Submit">');
			$(this).wrapInner('<form action="bin/assignments_upload.php" method="post" enctype="multipart/form-data"></form>');
			tinyMCE.execCommand('mceAddControl', false, 'editblogcontent');
			$('#blogcontainer').children('div').removeClass().addClass('blogpost');
			$('#editblogcontent').focus();
		});
	});
	
	$('#deleteblog').click(function(event) {
		event.preventDefault();
		$('#adminsection').html('<a href="assignments.php">Nevermind.</a>');
		$('#blogcontainer').children('div').removeClass('blogpost').addClass('editpost').prepend('<div class="deleteblogbutton">X</div>');
		$('.deleteblogbutton').hover(
			  function () {
				$(this).parent('div').removeClass('editpost').addClass('hoverpost');
			  }, 
			  function () {
				$(this).parent('div').removeClass('hoverpost').addClass('editpost');
			  }
		);
		$('.deleteblogbutton').click(function() {
			var blog_unid = $(this).siblings('span[name="blog_unid"]').html();;
			var blog_title = $(this).siblings('span[name="blog_title"]').html();;
			var answer = confirm("Are you sure you would like to delete the entry titled \""+ blog_title +"\"?")
			if (answer){
				$(this).parent().hide();
				$('.deleteblogbutton').hide();
				$('#blogcontainer').children('div').removeClass().addClass('blogpost');
				$.ajax({
				   type: "POST",
				   url: "bin/assignments_upload.php",
				   data: "deleteblogunid="+ blog_unid +"",
				   success: function(){
					 window.location = "http://cyberdiscovery.latech.edu/assignments.php";
				   }
				});
			}
		});
	});
});