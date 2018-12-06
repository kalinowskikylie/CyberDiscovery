<?php
//script will get all variables by the team names. reorder them numerically.
$assignment = $_POST['assignmentnum'];
$assignmentnum = str_replace('assignment','',$assignment);
$assignnum = str_replace('/','',$assignmentnum);

$onecrypt=3568789*($assignnum+3);
$twocrypt=1235798*($assignnum+3);
$threecrypt=2340589*($assignnum+3);
$fourcrypt=2456787*($assignnum+3);
$fivecrypt = 5687432*($assignnum+3);
$sixcrypt = 4878895*($assignnum+3);
$sevencrypt = 1234790*($assignnum+3);
$eightcrypt = 9800567*($assignnum+3);
$ninecrypt = 7521446*($assignnum+3);
$tencrypt = 8797437*($assignnum+3);

$rankingdisplay = '<span>The results for Assignment '. $assignnum .' are as follows:</span><br />';
$rankings = array($_POST[$onecrypt]=>"Ruston",$_POST[$twocrypt]=>"Benton",$_POST[$threecrypt]=>"Parkway",$_POST[$fourcrypt]=>"Haughton",$_POST[$fivecrypt]=>"Minden",$_POST[$sixcrypt]=>"North Desoto",$_POST[$sevencrypt]=>"Evangel",$_POST[$eightcrypt]=>"El Dorado",$_POST[$ninecrypt]=>"Byrd",$_POST[$tencrypt]=>"Airline");
ksort($rankings);                   
foreach ($rankings as $key => $value) {
	if ($key !== '') {
   		$rankingdisplay.= $key.'. - '.$value.'<br/>';
	}
}
$rankingfilesdisplay = '<br />';
$rankingfiles = array($_POST[$onecrypt]=>$onecrypt,$_POST[$twocrypt]=>$twocrypt,$_POST[$threecrypt]=>$threecrypt,$_POST[$fourcrypt]=>$fourcrypt,$_POST[$fivecrypt]=>$fivecrypt,$_POST[$sixcrypt]=>$sixcrypt,$_POST[$sevencrypt]=>$sevencrypt,$_POST[$eightcrypt]=>$eightcrypt,$_POST[$ninecrypt]=>$ninecrypt,$_POST[$tencrypt]=>$tencrypt);
ksort($rankingfiles);                   
foreach ($rankingfiles as $key => $value) {
	if ($key !== '') {
   		$rankingfilesdisplay.= $key.'. - <a href="http://cyberdiscovery.latech.edu/submissions/assignment'.$assignnum.'/'.$value.'">'.$value.'</a><br/>';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>
Account | Cyber
</title>
<?php include('../pagesetup.php'); ?>

<?php echo ('<div style="float:left;">'.$rankingdisplay.'</div>'); ?>
<?php echo $rankingfilesdisplay; ?>
<form action="blog_upload.php" method="post">
<?php echo('<input type="hidden" name="newblogcontent" value="'. $rankingdisplay .'" />'); ?>
<input type="submit" value="Post to Blog" />
</form>
<p>Please make a copy of this for records. Please contact the webmaster immediately if any of the rankings are inaccurate. 
<br />David Richard
<br />(337) 303-5040
<br />richard.davidj@gmail.com</p>
<?php include('../footer.php'); ?>

//backup rankings. create a txt in the assignments folder. give it permissions to be editable by scripts. print what is about to be printed to the screen. close.

//print resulting actualy team names in order with their rankings.

//put a send to blog button. Remind to print for records

//if send to blog button is pressed, have entire page laid out the same way a blog input would be so that one can just call the blog script.

//redirect to blog once pressed. 
