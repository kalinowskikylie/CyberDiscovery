<?php
	session_start();
	$curPage = $_GET['curpage'];
	$curBlog = $_GET['curblog'];
	
	
	$myFile = "./blog/cdblog.txt";
	$fh = fopen($myFile, 'r');
	$bloggity = fread($fh, filesize($myFile));
	fclose($fh);
	
	$bloggityExplosion = explode("*****END_OF_ENTRY*****",$bloggity);
	unset($bloggityExplosion[count($bloggityExplosion)-1]);
	unset($bloggity);
	unset($bpic);
	unset($bauthor);
	
        $count = 0;
	foreach ($bloggityExplosion as $b) {
		$bloggity[$count].='<div class="blogpost" name="' . $count . '">';
		$bexplosion = explode('(*)',$b);
		$bcontent = $bexplosion[0];
		$bauthor = $bexplosion[1];
		$bpic = $bexplosion[2];
		if (isset($bpic)) {
			if (isset($curBlog)) {
				$bloggity[$count].='<div name="bpic">';
				$bloggity[$count].='<center><img class="blogpic" src="http://cyberdiscovery.latech.edu/old/blog/' . $bpic . '" width="745" name="' . $bpic . '"></center>';
				$bloggity[$count].='</div>';
			} else {
				$bloggity[$count].='<div name="bpic" class="fright">';
				$bloggity[$count].='<img class="blogpic" src="http://cyberdiscovery.latech.edu/old/blog/thumbs/' . $bpic . '" width="200" name="' . $bpic . '">';
				$bloggity[$count].='</div>';
			}
		}
		$bloggity[$count].='<div class="content">';
		if (isset($curBlog)) {
			$bloggity[$count].=$bcontent;
		} else {
			$bloggity[$count].=$bcontent;
			$fixopentags = array('div','b','u','i','font','img','a','embed');
			
		}
		$bloggity[$count].='</div>';
		$bloggity[$count].='<div style="clear:both;"></div>';
		$bloggity[$count].='</div>';
		$bloggity[$count].='<span class="blogpostAuthor">';
		$bloggity[$count].=$bauthor;
		$bloggity[$count].='</span>';
		$bloggity[$count].='<br \>';
		$bloggity[$count].='<br \>';
		$count+=1;
	}
		
?>


<!DOCTYPE html>
<html>
<head>
<title>
Home | Cyber
</title>
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

<?php if(($_SESSION['cdpermissions']>50)){ ?>
    <div id="adminsection">
        <?php if (isset($curBlog)) { ?>
        	<span class="adminbutton" name="editblog"><a href="#">Edit existing blog</a></span>
        <?php } else {?>
        	<span class="adminbutton" name="addblog"><a href="#">Add blog</a></span>
        	<span class="adminbutton" name="deleteblog"><a href="#">Delete blog</a></span>
        <?php } ?>
        <span class="fright">Welcome to Admin Control Deck, <?php echo($_SESSION['cdname']); ?>. | <a href="logout.php">Logout</a></span>
    </div>
<?php } ?>
<?php 
			if(isset($curBlog)){
				$landingPage =  floor(($curBlog/5)+1);
				echo('<div id="newblogcontainer"></div>');
				echo('<div style="padding-left:7px;"><div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=227688337241596&amp;xfbml=1"></script><fb:like href="http://cyberdiscovery.latech.edu/index.php?curblog='. $curBlog .'" send="false" layout="button_count" width="450" show_faces="false" font="arial"></fb:like></div>');
				echo('<div id="blogcontainer">');
        		echo stripslashes($bloggity[$curBlog]);
				echo('</div>');
				
				echo('<div class="fright">');
				echo('<a href="http://cyberdiscovery.latech.edu/old/index.php?curpage='. $landingPage .'">Back</a>');
				echo('</div><div style="clear:both;"></div>');
         	} 
		?>
        
		<?php if(!isset($curBlog)){ ?>	
            <div id="newblogcontainer"></div>
            <div id="blogcolumn">
            <?php 			
				if ($_GET['login'] == 'success'){
					echo('<div id="login_success" class="blogpost" style="background-color:#C6F5C6;border-color:#00D600;">Login Successful...</div>');
				}
				if ($_GET['logout'] == 'success'){
					echo('<div id="login_success" class="blogpost" style="background-color:#C6F5C6;border-color:#00D600;">Logout Successful...</div>');
				}
			?>
            <div id="blogcontainer">
                       
            <?php
				$bloggitynum = count($bloggity);
				$bloggityPagenum = ceil($bloggitynum/5);
                                $bloggitydif = $bloggityPagenum*5 - $bloggitynum;
				
				if(!isset($curPage)){ $curPage = $bloggityPagenum; }
				
				$prevPage = $curPage+1;
				$nextPage = $curPage-1;
				
				$displayPage = ($curPage*5)-1-$bloggitydif;
				$displayPageStop = $displayPage-4;
				for($i=$displayPage; $i>=$displayPageStop; $i=$i-1){
					echo('<a href="http://cyberdiscovery.latech.edu/old/index.php?curblog='. $i .'">'. stripslashes($bloggity[$i]).'</a>');
				}
				echo('</div>');
				
				if ($bloggitynum > 5) {
					if ($curPage == $bloggityPagenum) {
						echo('<div class="fright">PREV&nbsp;&nbsp;&nbsp;');
					} else {
						echo('<div class="fright"><a href="http://cyberdiscovery.latech.edu/old/index.php?curpage='. $prevPage .'">PREV</a>&nbsp;&nbsp;&nbsp;');
					}
					
					for($j=$bloggityPagenum; $j>=1; $j=$j-1){
						$desiredPage = $j;
						echo('<a href="http://cyberdiscovery.latech.edu/old/index.php?curpage='. $desiredPage .'">'.$j.'</a>&nbsp;');
					}
					if ($curPage == 1) {
						echo('&nbsp;&nbsp;NEXT</div><div style="clear:both;"></div>');
					} else {
						echo('&nbsp;&nbsp;<a href="http://cyberdiscovery.latech.edu/old/index.php?curpage='. $nextPage .'">NEXT</a></div><div style="clear:both;"></div>');
					}
				}
                                echo('</div>');
			?>
                        <div id="infocolumn">
                             <h2>What is Cyber Discovery Camp?</h2>
                             <p>Cyber Discovery Camp is a week long experience for high school students. They learn applicable life skills through instruction, self-creativity, and teamwork. Louisiana Tech is proud to bring students this opportunity to enrich their academic background.</p>
                             <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/pages/Cyber-Discovery/213954368626255" width="250" show_faces="true" border_color="" stream="false" header="false"></fb:like-box>
                        </div>
                        <div style="clear:both;"></div>
            
        <?php } ?>

<?php include('footer.php'); ?>