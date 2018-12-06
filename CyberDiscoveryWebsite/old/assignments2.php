<?php
	$curPage = $_GET['curpage'];
	$curBlog = $_GET['curblog'];
	
	
	$myFile = "./assignments/cdassignments.txt";
	$fh = fopen($myFile, 'r');
	$bloggity = fread($fh, filesize($myFile));
	fclose($fh);
	
	$bloggityExplosion = explode("*****END_OF_ENTRY*****",$bloggity);
	unset($bloggityExplosion[count($bloggityExplosion)-1]);
	unset($bloggity);
	unset($bpic);
	unset($bauthor);
        unset($bfile);
	
        $count = 0;
	foreach ($bloggityExplosion as $b) {
		$bloggity[$count].='<div class="blogpost" name="' . $count . '">';
		$bexplosion = explode('(*)',$b);
		$bcontent = $bexplosion[0];
		$bauthor = $bexplosion[1];
		$bpic = $bexplosion[2];
                $bfile1 = $bexplosion[3];
                $bfile2 = $bexplosion[4];
                $bfile3 = $bexplosion[5];
		if ($bpic !== 'undefined') {
			if (isset($curBlog)) {
				$bloggity[$count].='<div name="bpic">';
				$bloggity[$count].='<center><img class="blogpic" src="http://cyberdiscovery.latech.edu/assignments/' . $bpic . '" width="745" name="' . $bpic . '"></center>';
				$bloggity[$count].='</div>';
			} else {
				$bloggity[$count].='<div name="bpic" class="fright">';
				$bloggity[$count].='<img class="blogpic" src="http://cyberdiscovery.latech.edu/assignments/thumbs/' . $bpic . '" width="200" name="' . $bpic . '">';
				$bloggity[$count].='</div>';
			}
		}
		$bloggity[$count].='<div class="content">';
		if (isset($curBlog)) {
			$bloggity[$count].=$bcontent;
                        if(isset($bfile1)){
                             $bfile[$count].='<strong>Attachments:</strong>';
                             $bfile[$count].='<br /><a style="text-decoration:underline;" name="bfile1" href="http://cyberdiscovery.latech.edu/assignments/' . $bfile1 . '">' . $bfile1 . '</a>';
                        } else {
                             $bfile[$count].='<a name="bfile1"></a>';
                        }
                        if(isset($bfile2)){
                             $bfile[$count].='<br /><a style="text-decoration:underline;" name="bfile2" href="http://cyberdiscovery.latech.edu/assignments/' . $bfile2 . '">' . $bfile2 . '</a>';
                        } else {
                             $bfile[$count].='<a name="bfile2"></a>';
                        }
                        if(isset($bfile3)){
                             $bfile[$count].='<br /><a style="text-decoration:underline;" name="bfile3" href="http://cyberdiscovery.latech.edu/assignments/' . $bfile3 . '">' . $bfile3 . '</a>';
                        } else {
                             $bfile[$count].='<a name="bfile3"></a>';
                        }
		} else {
			$text = $bcontent;
			$chars = 1000;
			if (strlen($text) > $chars) {
				$text = $text." "; 
				$text = substr($text,0,$chars); 
				$text = substr($text,0,strrpos($text,' ')); 
				$text = $text."...";
			}
			$bloggity[$count].=$text;
			$fixopentags = array('div','b','u','i','font','img','a','embed');
			foreach($fixopentags as $f){
				$opens = substr_count($text, '<'.$f);
				$closes = substr_count($text, '</'.$f);
				$correct = $opens - $closes;
				while($correct !== 0) {
					$bloggity[$count].='</'.$f.'>';
					$correct -= 1;
				}
			}
			
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
Assignments | Cyber
</title>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
<script type="text/javascript" src="scripts/assignments.js"></script>
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<?php include('pagesetup.php'); ?>
<?php if((true)){ ?>
<?php if((true)){ ?>
    <div id="adminsection">
        <?php if (isset($curBlog)) { ?>
        	<span class="adminbutton" name="editblog"><a href="#">Edit existing blog</a></span>
        <?php } else {?>
        	<span class="adminbutton" name="addblog"><a href="#">Add blog</a></span>
                <?php if((false)){ ?>
        	     <span class="adminbutton" name="deleteblog"><a href="#">Delete blog</a></span>
                <?php } ?>
        <?php } ?>
        <span class="fright">Welcome to Admin Control Deck, <?php echo('sir'); ?>. | <a href="logout.php">Logout</a></span>
    </div>
<?php } ?>

<?php 
			if(isset($curBlog)){
				$landingPage =  floor(($curBlog/5)+1);
				echo('<div id="newblogcontainer"></div>');
				echo('<div id="blogcontainer">');
        		echo stripslashes($bloggity[$curBlog]);
        		echo $bfile[$curBlog];
                        if(($_SESSION['cdpermissions'] == 10)){
				echo('<form action="bin/student_submissions_upload.php" method="POST" style="background-color:#DDD;" enctype="multipart/form-data">');
				echo('<span>Submit assignment with the box below. Once submitted, new uploads will be rejected! Submissions are final!</span>');
				echo('<input type="hidden" id="assignnum" name="assignnum" value="' . $curBlog . '">');
				echo('<br />File:<input type="file" id="studentsubmission" name="studentsubmission">');
				echo('<input type="submit">');
				echo('</form>');
                        }
				echo('</div>');
				echo('<div class="fright">');
				echo('<a href="http://cyberdiscovery.latech.edu/assignments.php?curpage='. $landingPage .'">Back</a>');
				echo('</div><div style="clear:both;"></div>');
         	} 
		?>
        
		<?php if(!isset($curBlog)){ ?>
            <div id="newblogcontainer"></div>
            <div id="blogcolumn">
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
					echo('<a href="http://cyberdiscovery.latech.edu/assignments.php?curblog='. $i .'">'. stripslashes($bloggity[$i]).'</a>');
				}
				echo('</div>');
				
				if ($bloggitynum > 5) {
					if ($curPage == $bloggityPagenum) {
						echo('<div class="fright">PREV&nbsp;&nbsp;&nbsp;');
					} else {
						echo('<div class="fright"><a href="http://cyberdiscovery.latech.edu/assignments.php?curpage='. $prevPage .'">PREV</a>&nbsp;&nbsp;&nbsp;');
					}
					
					for($j=$bloggityPagenum; $j>=1; $j=$j-1){
						$desiredPage = $j;
						echo('<a href="http://cyberdiscovery.latech.edu/assignments.php?curpage='. $desiredPage .'">'.$j.'</a>&nbsp;');
					}
					if ($curPage == 1) {
						echo('&nbsp;&nbsp;NEXT</div><div style="clear:both;"></div>');
					} else {
						echo('&nbsp;&nbsp;<a href="http://cyberdiscovery.latech.edu/assignments.php?curpage='. $nextPage .'">NEXT</a></div><div style="clear:both;"></div>');
					}
				}
                                echo('</div>');
			?>
                        <div id="infocolumn">
                             <h2>Student Instructions</h2>
                             <p>Assignments throughout the week will be posted here. Students can submit their assignments via submission forms located below each assignment post.</p>
                             <p>To submit assignments, first login then, click the assignment. The submission form can be found below the assignment.</p>
                        </div>
                        <div style="clear:both;"></div>
            
        <?php } ?>
<?php }  else {?>
You must login to view this page.
<?php } ?>
<?php include('footer.php'); ?>