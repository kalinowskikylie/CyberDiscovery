$(document).ready(function() {
	
	$('#addblog').click(function(event) {
		event.preventDefault();
		$('#adminsection').html('<a href="index.php">Nevermind.</a>');
		$('#newblogeditor').find('iframe').css('height','500px');
		$('#newblogeditor').show();
		$('#blog_title').focus();
	});
	
	$('#editblog').click(function(event) {
		event.preventDefault();
		$('#adminsection').html('<a href="index.php">Nevermind.</a>');
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
			
			$(this).prepend('<input type="hidden" name="blog_unid" id="blog_unid" value="'+ blog_unid +'">');
			$(this).children('span[name="blog_title"]').html('<input type="text" name="blog_title" id="blog_title" value="'+ blog_title +'" />').before('Title: ');
			$(this).children('span[name="blog_content"]').html('<textarea name="editblogcontent" id="editblogcontent" style="width:676px; height:500px;">'+ blog_content +'</textarea>');
			$(this).append('<input type="submit" value="Submit">');
			$(this).wrapInner('<form action="bin/index_upload.php" method="post" enctype="multipart/form-data"></form>');
			tinyMCE.execCommand('mceAddControl', false, 'editblogcontent');
			$('#blogcontainer').children('div').removeClass().addClass('blogpost');
			$('#editblogcontent').focus();
		});
	});
	
	$('#deleteblog').click(function(event) {
		event.preventDefault();
		$('#adminsection').html('<a href="index.php">Nevermind.</a>');
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
				   url: "bin/index_upload.php",
				   data: "deleteblogunid="+ blog_unid +"",
				   success: function(){
					 window.location = "http://cyberdiscovery.latech.edu/index.php";
				   }
				 });
			}
		});
	});
});