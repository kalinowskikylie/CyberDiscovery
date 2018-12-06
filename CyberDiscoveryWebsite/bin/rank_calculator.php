<?php
session_start();
include('../pass_prot/pass_config.php');
include('../txtdb/txtdb_commands.php');


	if($_SESSION[$session_prefix .'permissions'] >= '70') {
		$curCampdb = '../camps/'. $_SESSION[$session_prefix .'curCamp'] .'/submissionsdb.txt';

//---------------------------------------------------Reset Ranks--------------------------------------------------------------		
		if (isset($_POST['rankuncalcAssign'])) {
			update_txtdb($curCampdb,'assignment,'. $_POST['rankuncalcAssign'],'overall_ranking','');
			header('location: '. $_SERVER['HTTP_REFERER']);
		}		

//---------------------------------------------------Calculate Ranks------------------------------------------------------------------		
		if (isset($_POST['rankcalcAssign'])) {
			$submissionsdbobj = select_txtdb($curCampdb);
			$scores = query_txtdb($submissionsdbobj,'UNID,judge_rankings','assignment,'. $_POST['rankcalcAssign']);
			foreach($scores as $key => $row){
				$temppieces = explode(',', $row['judge_rankings']);
				if($temppieces[count($temppieces)-1] == ''){
					unset($temppieces[count($temppieces)-1]);
				}
				$tempsum = 0;
				foreach($temppieces as $tempkey => $temprow){
					if($tempkey%2 == 1){
						$tempsum = $tempsum + $temprow;
					}
				}
				$sumArray[$row['UNID']] = $tempsum;
			}
			asort($sumArray,SORT_NUMERIC);
			$counter = 1;
			foreach($sumArray as $subunid => $subscore){
				if($subscore == $tempsubscore){
					$tempcounter = $counter-1;
					update_txtdb($curCampdb,'UNID,'. $subunid,'overall_ranking',$tempcounter);
				} else {
					update_txtdb($curCampdb,'UNID,'. $subunid,'overall_ranking',$counter);
				}
				$counter++;
				$tempsubscore = $subscore;
			}
		}
		
//--------------------------------------------------Show Rankings-------------------------------------------------------------

		if ((isset($_POST['rankcalcAssign'])) || (isset($_POST['rankrecalcAssign']))) {
			$newsubmissionsdbobj = select_txtdb($curCampdb);
			$teamsdbobj = select_txtdb('../db/teams.txt');
			$assignmentsdbobj = select_txtdb('../camps/'. $_SESSION[$session_prefix .'curCamp'] .'/assignmentsdb.txt');
			
			if (isset($_POST['rankcalcAssign'])){
				$newscores = query_txtdb($newsubmissionsdbobj,'team,title,overall_ranking','assignment,'. $_POST['rankcalcAssign'],'overall_ranking');
				$rankAssigntitle = query_txtdb($assignmentsdbobj,'title','UNID,'. $_POST['rankcalcAssign']);
			}
			if (isset($_POST['rankrecalcAssign'])){
				$newscores = query_txtdb($newsubmissionsdbobj,'team,title,overall_ranking','assignment,'. $_POST['rankrecalcAssign'],'overall_ranking');
				$rankAssigntitle = query_txtdb($assignmentsdbobj,'title','UNID,'. $_POST['rankrecalcAssign']);
			}
			?>
			<!DOCTYPE html>
			<html>
			<head>
			<title>
			Rankings | Cyber
			</title>
			<?php include('../pagesetup.php');
			echo('<a href="'. $_SERVER['HTTP_REFERER'] .'">Back</a>');
			echo('<h2>Rankings for: '. $rankAssigntitle[0]['title'] .'</h2>');
			echo('<table border="1">');
			echo('<tr>');
			echo('<th>Team</th>');
			echo('<th>Submission</th>');
			echo('<th>Ranking</th>');
			echo('</tr>');
			foreach($newscores as $row){
				$teamsDNS = query_txtdb($teamsdbobj,'team_name','UNID,'. $row['team']);
				echo('<tr>');
				echo('<td>');
				echo($teamsDNS[0]['team_name']);
				echo('</td>');
				echo('<td>');
				echo($row['title']);
				echo('</td>');
				echo('<td>');
				echo($row['overall_ranking']);
				echo('</td>');
				echo('</tr>');
			}
			echo('</table>');
			include('../footer.php'); ?>
			<?php
		}
		

	} else {
		header('location: ../submissions.php');
	}
?>