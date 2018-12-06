<?php
	session_start();
	include('pass_prot/pass_config.php');
	if($_SESSION[$session_prefix .'permissions'] < 10){
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'];
		header('Location: '. $path_to_pass_folder .'/pass_prot/login.php?error=perm');
		exit;
	} 
	include('txtdb/txtdb_commands.php');		

	$timestamp = time();
	$curAssign = $_GET['assignment'];
	$curSub = $_GET['submission'];

	$submissionsdbobj = select_txtdb('camps/'. $_SESSION[$session_prefix .'curCamp'] .'/submissionsdb.txt');
	$assignmentsdbobj = select_txtdb('camps/'. $_SESSION[$session_prefix .'curCamp'] .'/assignmentsdb.txt');
	$exp_date = query_txtdb($assignmentsdbobj, 'exp_date', 'UNID,'. $curAssign);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Submissions | Cyber</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
<?php include('pagesetup.php'); ?>
<?php
//-------------SELECTOR BOXES---------------------------------------------------
	echo('<div id="selectorbox">');
	echo('<form style="display:inline;">');
	//Assignment selector
	$assignmentslist = query_txtdb($assignmentsdbobj,'UNID,title');
	if(is_array($assignmentslist)){
		echo('<select name="assignment">');
		foreach($assignmentslist as $row){
			if($curAssign == $row['UNID']){
				echo('<option selected="selected" value="'. $row['UNID'] .'">'. $row['title'] .'</option>');
			} else {
				echo('<option value="'. $row['UNID'] .'">'. $row['title'] .'</option>');
			}
		}
		echo('</select>');
		echo('<span id="submissionswitcher"></span><input type="submit" value="View" /></form><span id="submissionswitcheropt">');
		//End Assignment Selector
	
		//Submission Selector (not available to teams)
		foreach($assignmentslist as $key => $row){
			$submissionslist = query_txtdb($submissionsdbobj,'UNID,title,team,overall_ranking','assignment,'. $row['UNID']);
			echo('<select name="submission" assign="'. $row['UNID'] .'" style="display:none;">');
			if(is_array($submissionslist)){
				foreach($submissionslist as $subrow){
					if((($_SESSION[$session_prefix .'permissions'] == 10) && ($subrow['team'] == $_SESSION[$session_prefix .'unid'])) || ($_SESSION[$session_prefix .'permissions'] >= 60)){
						if($curSub == $subrow['UNID']){
							echo('<option selected="selected" value="'. $subrow['UNID'] .'">'. $subrow['title'] .'</option>');
							$overall_ranking = $subrow['overall_ranking'];
						} else {
							echo('<option value="'. $subrow['UNID'] .'">'. $subrow['title'] .'</option>');
						}
					}
				}
			}
			echo('</select>');
		}
	} else {
		echo('There are no assignments yet.');
	}
	echo('</span>');
	?>
	<script type="text/javascript">
		var chosenassign = $('select[name="assignment"]').val();
		$('#submissionswitcheropt').children('select[assign="'+ chosenassign +'"]').detach().appendTo('#submissionswitcher').show();
		$('select[name="assignment"]').change(function(){
			chosenassign = $(this).val();
			$('#submissionswitcher').children('select[assign!="'+ chosenassign +'"]').detach().appendTo('#submissionswitcheropt').hide();
			$('#submissionswitcheropt').children('select[assign="'+ chosenassign +'"]').detach().appendTo('#submissionswitcher').show();
		});
	</script>
	<?php if($_SESSION[$session_prefix .'permissions'] >= 70 && $curAssign != '' && $exp_date[0]['exp_date'] < $timestamp){ ?>
		<span style="float:right;" id="rankcalculatebox">
			<?php if($overall_ranking == ''){ ?>
			<form action="bin/rank_calculator.php" name="calc" method="post" id="rankcalcform">
				<input type="hidden" name="rankcalcAssign" value="<?php echo($curAssign) ?>" />
				<input type="submit" value="Calculate Ranks" />
			</form>
			<?php } else {?>
			<form action="bin/rank_calculator.php" style="display:inline;" name="recalc" method="post" id="rankrecalcform">
				<input type="hidden" name="rankrecalcAssign" value="<?php echo($curAssign) ?>" />
				<input type="submit" value="Show Rankings" />
			</form>
			<form action="bin/rank_calculator.php" style="display:inline;" name="uncalc" method="post" id="rankuncalcform">
				<input type="hidden" name="rankuncalcAssign" value="<?php echo($curAssign) ?>" />
				<input type="submit" value="Unlock Assignment" />
			</form>
			<?php } ?>
		</span>
		<script type="text/javascript">
			$('#rankcalcform').submit(function(){
				var answer = confirm('You are about to calculate the ranks for all submissions for this assignment. This will lock all submissions for this assignment from further review. Are you sure you want to continue?');
				if(answer){
					return true;
				} else {
					return false;
				}
			});
			$('#rankuncalcform').submit(function(){
				var answer = confirm('You are about to unlock this assignment, allowing judges to add or change their assigned ranks. This is HIGHLY UNADVISABLE if the ranks have already been reported. Further ranking of this assignment could affect final team rankings. USE WITH CAUTION.');
				if(answer){
					return true;
				} else {
					return false;
				}
			});
		</script>
	<?php } ?>
	<div style="clear:both;"></div>
	</div>
	<br />
	<?php
	//End Submission Selector
//--------------------------------------------------------------------------------

	if($curSub == ''){ //No assignment selected. Landing page presented.
		echo('No submission selected.');
	}else{ //Display submissions page for chosen assignment
		$chosenSubmission = query_txtdb($submissionsdbobj,'UNID,title,url','UNID,'. $curSub);
		echo('<span class="blogpost">DOWNLOAD: <a href="'. $chosenSubmission[0]['url'] .'">'. $chosenSubmission[0]['title'] .'</a></span>');
		if($overall_ranking != ''){
			echo('<span> Ranking: '. $overall_ranking .'</span>');
		}
		if($_SESSION[$session_prefix .'permissions'] >= 60){ //Ranking Panel
			$submissions2rank = query_txtdb($submissionsdbobj,'UNID,title,team,judge_rankings,overall_ranking','assignment,'. $curAssign);
			echo('<div id="rankpanel">');
			if($_SESSION[$session_prefix .'permissions'] >= 70){ // Toggle assignments and team names.
				echo('<a style="float:right;" id="nametoggler" href="#">Reveal/Hide Team Names</a><br />');
				?>
				<script type="text/javascript">
					$('#nametoggler').click(function(e){
						e.preventDefault();
						$('.nametoggle').toggle();
					});
				</script>
				<?php
			}
			echo('<form id="rankingssubmitform" action="bin/rankings_upload.php" method="post">');
			echo('<table border="1">');
			echo('<tr>');
			echo('<th class="nametoggle" style="display:none;">Team</th>');
			echo('<th>Submission</th>');
			echo('<th>Rank</th>');
			echo('</tr>');
			
			$teamsdbobj = select_txtdb('db/teams.txt');
			
			foreach($submissions2rank as $rownum => $row){
				$teamsDNS = query_txtdb($teamsdbobj,'team_name','UNID,'. $row['team']);
				if($row['UNID'] == $curSub){
					echo('<tr style="background-color:#FAA634;">');
				} else {
					echo('<tr>');
				}
				echo('<td class="nametoggle" style="display:none;">'. $teamsDNS[0]['team_name'] .'</td><td unid="'. $row['UNID'] .'">'. $row['title'] .'</td>');
				$rankingpieces = explode(',', $row['judge_rankings']);
				if(in_array($_SESSION[$session_prefix .'unid'],$rankingpieces)){
					$tempkey = array_search($_SESSION[$session_prefix .'unid'],$rankingpieces);
					$tempkey++;
					$tempRanking = $rankingpieces[$tempkey];
				} else {
					$tempRanking = '';
				}
				if($row['UNID'] == $curSub && $row['overall_ranking'] == '' && $exp_date[0]['exp_date'] < $timestamp){
					echo('<td><input type="hidden" name="subunid" value="'. $row['UNID'] .'" /><input type="text" name="subranking" value="'. $tempRanking .'" style="width:3.5em;" /></td>');
				} else {
					echo('<td>'. $tempRanking .'</td>');
				}
				echo('</tr>');
			}
			echo('</table>');
			if($exp_date[0]['exp_date'] < $timestamp){
				if($row['overall_ranking'] == ''){
					echo('<input style="float:right;" type="submit" value="Update Rankings" />');
				} else {
					echo('<span style="float:right;text-align:right;">This assignment has been locked.<br />Please contact an Admin for assistance.</span>');
				}
			} else {
				echo('<span style="float:right;text-align:right;">This assignment\'s due date has not passed.<br />Please contact an Admin for assistance.</span>');
			}
			echo('</form>');
			echo('</div><div style="clear:both;"></div>');
			?>
			<script type="text/javascript">
				$('#rankingssubmitform').submit(function(){
					$(this).children('input[type="submit"]').replaceWith('<span style="float:right;">Loading...</span>');
					return true;
				});
			</script>
			<?php
		}
		if($_SESSION[$session_prefix .'permissions'] >= 80){ // This Panel Shows all of the submitted scores so far and the judges who submitted them.
			echo('<br /><div id ="scoringbox">');
			echo('</div>');
		}
	}
?>
<?php if(isset($_SESSION[$session_prefix .'campunid'][1])){ ?>
<!-- ++++++++++++++++++++++++++++++++ CAMP SWITCHER +++++++++++++++++++++++++++++++++ -->
<div style="position:fixed; bottom:0px; right:20px; background-color:#CCC; padding:3px;" id="campswitcher">
	<form action="bin/process_campswitcher.php" method="post">
		<select name="switchcamp">
			<?php
				$campdbobj = select_txtdb('db/sessions.txt');
				foreach($_SESSION[$session_prefix .'campunid'] as $campunid){
					$campDNS = query_txtdb($campdbobj,'camp_name','UNID,'. $campunid);
					if($campunid == $_SESSION[$session_prefix .'curCamp']){
						echo('<option selected="selected" value="'. $campunid .'">'. $campDNS[0]['camp_name'] .'</option>');
					} else {
						echo('<option value="'. $campunid .'">'. $campDNS[0]['camp_name'] .'</option>');
					}
				}
			?>
		</select>
		<input type="hidden" name="referringPage" value="/cyberdiscovery/submissions.php" />
		<input type="submit" value="Switch" />
	</form>
</div>
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<?php } ?>
<?php include('footer.php'); ?>
