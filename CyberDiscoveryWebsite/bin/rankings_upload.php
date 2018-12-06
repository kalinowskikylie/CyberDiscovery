<?php
session_start();
include('../pass_prot/pass_config.php');
include('../txtdb/txtdb_commands.php');


	if($_SESSION[$session_prefix .'permissions'] >= '60') {
		$subunid = $_POST['subunid'];
		$subranking = $_POST['subranking'];
		$judge = $_SESSION[$session_prefix .'unid'];
		$curCampdb = '../camps/'. $_SESSION[$session_prefix .'curCamp'] .'/submissionsdb.txt';
		$submissionsdbobj = select_txtdb($curCampdb);
		$chosenSub = query_txtdb($submissionsdbobj,'judge_rankings,overall_ranking','UNID,'. $subunid);
		if($chosenSub[0]['overall_ranking'] == ''){ //Check that submission hasnt been ranked already, which is the equivalent of locking the submission.
			$rankingpieces = explode(',', $chosenSub[0]['judge_rankings']);
			if($rankingpieces[count($rankingpieces)-1] == ''){
				unset($rankingpieces[count($rankingpieces)-1]);
			}
			if(in_array($judge,$rankingpieces)){ // If the judge has already ranked this assignment as is merely updating his ranking.
				$tempkey = array_search($judge, $rankingpieces);
				$tempkey++;
				$rankingpieces[$tempkey] = $subranking;
			} else { // If this is a new ranking for that judge
				$rankingpieces[count($rankingpieces)] = $judge;
				$rankingpieces[count($rankingpieces)] = $subranking;
			}
			$update_submission = '';
			foreach($rankingpieces as $v){
				$update_submission .= $v .',';
			}
			update_txtdb($curCampdb,'UNID,'. $subunid,'judge_rankings', clean_txtdb($update_submission));
		} else { //Submission has alraedy been ranked and locked
			echo('This submission has alraedy been locked from ranking. Please contact an adminstrator if you feel this message has been reached in error.');
			exit;
		}
		
		header('location: '. $_SERVER['HTTP_REFERER']);
	} else {
		header('location: ../index.php');
	}
?>	