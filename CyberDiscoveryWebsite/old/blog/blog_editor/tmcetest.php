<!DOCTYPE html>
<html>
<head>
	<title>MCE test</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
	<script type="text/javascript" src="/ecocar/redesign/blog_editor/tinymce/jscripts/tiny_mce/tiny_mce.js" ></script >
	<script type="text/javascript" src="/ecocar/redesign/blog_editor/tinymce/tinymce_filebrowser.js" ></script >
	<script type="text/javascript" src="/ecocar/redesign/blog_editor/ajax_uploader/ajaxupload.js" ></script >
	<script type="text/javascript" >
	tinyMCE.init({
			mode : "textareas",
			theme : "advanced",   //(n.b. no trailing comma, this will be critical as you experiment later)
			plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
			
			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			

			file_browser_callback : "myFileBrowser"
	});
	</script >
</head>

<body>
	<form>  
		<textarea name="content" cols="50" rows="15" > 
		This is some content that will be editable with TinyMCE.
		</textarea>
    </form>
</body>
</html>