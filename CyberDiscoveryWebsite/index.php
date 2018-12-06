<?php
	session_start();
	include('pass_prot/pass_config.php');
	include('txtdb/txtdb_commands.php');
	
	$blogdbobj = select_txtdb('db/home.txt'); //Select and create blog database structure
	$blogdb = query_txtdb($blogdbobj,'*','','op_date,ascend');
	
	//Pagination Prematerial
	$curPage = $_GET['curPage'];
	$maxPage = ceil(count($blogdb)/5);
	if (($curPage == '') || ($curPage < 1)){ //If current page is not set or if it is less than one
		$curPage = '1';
	}
	if ($curPage > ($maxPage)){ // If current page is greater than the amountof pages
		$curPage = $maxPage;
	}
	$prevPage = $curPage-1;
	$nextPage = $curPage+1;
	// End Page prematerial
	
	$usersdbobj = select_txtdb('db/users.txt'); //Select and create user database structure
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Home | Cyber</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
	<script type="text/javascript" src="scripts/index.js"></script>
	<script type="text/javascript" src="/cyberdiscovery/blog_editor/tinymce/jscripts/tiny_mce/tiny_mce.js" ></script >
	<script type="text/javascript" src="/cyberdiscovery/blog_editor/tinymce/tinymce_filebrowser.js" ></script >
	<script type="text/javascript" src="/cyberdiscovery/blog_editor/ajax_uploader/ajaxupload.js" ></script >
	<script type="text/javascript" >
	tinyMCE.init({
			mode : "none",
			theme : "advanced",   //(n.b. no trailing comma, this will be critical as you experiment later)
			plugins : "autolink,lists,spellchecker,table,advimage,advlink,iespell,inlinepopups,preview,media,searchreplace,contextmenu,paste,noneditable,xhtmlxtras",
			
			// Theme options
			theme_advanced_buttons1 : "removeformat,|,undo,redo,|,bold,italic,underline,strikethrough,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "forecolor,backcolor,|,bullist,numlist,outdent,indent,|,image,media,iespell,link,unlink,|,hr,charmap",
			theme_advanced_buttons3 : "spellchecker,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,tablecontrols,|,code,preview",
			theme_advanced_buttons4 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : false,
			

			file_browser_callback : "myFileBrowser"
	});
	tinyMCE.execCommand('mceAddControl', false, 'newblogcontent');
	</script >
<?php include('pagesetup.php'); ?>
<div style="display:none;" id="referer" name="index" camp="<?php if(isset($_SESSION[$session_prefix .'curCamp'])){ echo($_SESSION[$session_prefix .'curCamp']);} ?>"></div>
	<?php if($_SESSION[$session_prefix .'permissions'] >= '70'){ ?>
		<div id="adminsection">
			<span class="adminbutton" id="addblog"><a href="#">Add blog</a></span>
			<span class="adminbutton" id="editblog"><a href="#">Edit blog</a></span>
			<span class="adminbutton" id="deleteblog"><a href="#">Delete blog</a></span>
			<span class="fright">Welcome to Admin Control Deck, <?php echo($_SESSION[$session_prefix .'fName']); ?>. | <a href="pass_prot/logout.php">Logout</a></span>
		</div>
	<?php } ?>

	<div id="blogcolumn">
<!-- ++++++++++++++++++++Editor for New Blog+++++++++++++++++++++++++++++++++++++ -->
		<div id="newblogeditor" class="blogpost" style="display:none;">
			<form action="bin/index_upload.php" method="post" enctype="multipart/form-data">
				Title:<input type="text" id="blog_title" name="blog_title" /><br />
				<textarea name="newblogcontent" id="newblogcontent" style="width:676px;height:500px;"></textarea><br />
				<input type="submit" value="Submit">
			</form>
		</div>
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->	
		
		<?php 
		if(is_array($blogdb)){
			//Pagination Navigation
			echo('<div class="pagenav">');
			if($curPage != 1){
				echo('<a href="?curPage=1">First</a> | ');
				echo('<a href="?curPage='. $prevPage .'">Previous</a> | ');
			} else {
				echo('First | ');
				echo('Previous | ');
			}
			for($i = 1; $i <=$maxPage; $i++){
				if($curPage != $i){
					echo('<a href="?curPage='. $i .'">'. $i .'</a> | ');
				} else {
					echo($i .' | ');
				}
			}
			if($curPage != $maxPage){
				echo('<a href="?curPage='. $nextPage .'">Next</a>');
				echo(' | <a href="?curPage='. $maxPage .'">Last</a>');
			} else {
				echo('Next');
				echo(' | Last');
			}
			echo('</div><div style="clear:both;"></div>');
			// End Page Navigation
			
			echo('<div id="blogcontainer">');
			foreach($blogdb as $postnum => $row){
				if((($curPage-1)*5 <= $postnum) && ($postnum < $curPage*5)){ //Paginate pagenumber * 5 articles
					echo('<div class="blogpost">');
					echo('<span name="blog_unid" style="display:none;">'. $row['UNID'] .'</span>');
					echo('<span class="blogpostTitle" name="blog_title">'. stripslashes($row['title']) .'</span><br />');
					if(!($row['edit_date'] == '')){
						$userDNS = query_txtdb($usersdbobj,'UNID,first_name,last_name','UNID,'. $row['edit_author']);
						if (!$userDNS){$userDNS[0]['first_name'] = 'Unknown User';}
						echo('<span class="blogpostUpdate">Last edited by '. stripslashes($userDNS[0]['first_name'] .' '. $userDNS[0]['last_name']) .' on '. date('F jS\, Y',$row['edit_date']) .'.</span><br />');
					}
					$userDNS = query_txtdb($usersdbobj,'UNID,first_name,last_name','UNID,'. $row['op']);
					if (!$userDNS){$userDNS[0]['first_name'] = 'Unknown User';}
					echo('<span class="blogpostAuthor">Posted by '. stripslashes($userDNS[0]['first_name'] .' '. $userDNS[0]['last_name']) .' on '. date('F jS\, Y',$row['op_date']) .'.</span><br />');
					echo('<span name="blog_content">'. stripslashes($row['content']) .'</span>');
					echo('</div>');
				}
			}
			echo('</div>');
			//Pagination Navigation
			echo('<div class="pagenav">');
			if($curPage != 1){
				echo('<a href="?curPage=1">First</a> | ');
				echo('<a href="?curPage='. $prevPage .'">Previous</a> | ');
			} else {
				echo('First | ');
				echo('Previous | ');
			}
			for($i = 1; $i <=$maxPage; $i++){
				if($curPage != $i){
					echo('<a href="?curPage='. $i .'">'. $i .'</a> | ');
				} else {
					echo($i .' | ');
				}
			}
			if($curPage != $maxPage){
				echo('<a href="?curPage='. $nextPage .'">Next</a>');
				echo(' | <a href="?curPage='. $maxPage .'">Last</a>');
			} else {
				echo('Next');
				echo(' | Last');
			}
			echo('</div><div style="clear:both;"></div>');
			// End Page Navigation
		} else {
			echo('<h2>No Recent News</h2>');
		}
		?>
	</div>
	<div id="infocolumn">
		<h2>What is Cyber Discovery Camp?</h2>
		<p>Cyber Discovery Camp is a week long experience for high school students. They learn applicable life skills through instruction, self-creativity, and teamwork. Originally created at Louisiana Tech University, and now being deployed throughout the country, we are proud to bring students this opportunity to enrich their academic background.	</p>
		<p>For the 2018 year, Cyber Discovery is being funded through the GenCyber program.</p>
		<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/pages/Cyber-Discovery/213954368626255" width="250" show_faces="true" border_color="" stream="false" header="false"></fb:like-box>
	</div>
	<div style="clear:both;"></div>

<?php include('footer.php'); ?>