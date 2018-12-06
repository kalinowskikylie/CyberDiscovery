<?php
	session_start();
	$curPage = $_GET['curpage'];
	$curBlog = $_GET['curblog'];
	
	$myFile = "blog/cdblog.txt";
	$fh = fopen($myFile, 'r');
	$blogdb = fread($fh, filesize($myFile));
	fclose($fh);
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Blog | Cyber</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
	<script type="text/javascript" src="scripts/index.js"></script>
	<script type="text/javascript" src="/ecocar/redesign/blog_editor/tinymce/jscripts/tiny_mce/tiny_mce.js" ></script >
	<script type="text/javascript" src="/ecocar/redesign/blog_editor/tinymce/tinymce_filebrowser.js" ></script >
	<script type="text/javascript" src="/ecocar/redesign/blog_editor/ajax_uploader/ajaxupload.js" ></script >
	<script type="text/javascript" >
	tinyMCE.init({
			mode : "none",
			theme : "advanced",   //(n.b. no trailing comma, this will be critical as you experiment later)
			plugins : "autolink,lists,spellchecker,table,advimage,advlink,iespell,inlinepopups,preview,media,searchreplace,contextmenu,paste,noneditable,xhtmlxtras",
			
			// Theme options
			theme_advanced_buttons1 : "removeformat,|,undo,redo,|,bold,italic,underline,strikethrough,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor,|,bullist,numlist,outdent,indent,|,image,media,iespell,link,unlink,|,hr,charmap",
			theme_advanced_buttons2 : "spellchecker,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,tablecontrols,|,code,preview",
			theme_advanced_buttons3 : "",
			theme_advanced_buttons4 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : false,
			

			file_browser_callback : "myFileBrowser"
	});
	</script >
<?php include('pagesetup.php'); ?>
	<?php echo(stripslashes($blogdb)); ?>
<?php include('footer.php'); ?>