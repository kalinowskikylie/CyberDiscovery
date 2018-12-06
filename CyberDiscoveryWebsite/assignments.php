<?php
	session_start();
	include('pass_prot/pass_config.php');
	if($_SESSION[$session_prefix .'permissions'] < 10){
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'];
		header('Location: '. $path_to_pass_folder .'/pass_prot/login.php?error=perm');
		exit;
	} 
	include('txtdb/txtdb_commands.php');
	
	$blogdbobj = select_txtdb('camps/'. $_SESSION[$session_prefix .'curCamp'] .'/assignmentsdb.txt'); //Select and create blog database structure
	$blogdb = query_txtdb($blogdbobj,'*','','op_date,ascend');
	
	//assignment selection Prematerial
	$curAssign = $_GET['curAssign'];
	// End assignment selection prematerial
	
	$usersdbobj = select_txtdb('db/users.txt'); //Select and create user database structure
	$timestamp = time();
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Assignments | Cyber</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
	<link type="text/css" href="/cyberdiscovery/scripts/jqueryui.css" rel="stylesheet">
	<script type="text/javascript" src="/cyberdiscovery/scripts/jqueryui.js"></script>
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
<?php if((!isset($curAssign)) || ($curAssign == '')) { //If page is the landing page then do the following?>
	<script type="text/javascript" src="scripts/assignments.js"></script>
	<div style="display:none;" id="referer" name="assignments" camp="<?php if(isset($_SESSION[$session_prefix .'curCamp'])){ echo($_SESSION[$session_prefix .'curCamp']);} ?>"></div>
		<?php if(($_SESSION[$session_prefix .'permissions'] == '50') || ($_SESSION[$session_prefix .'permissions'] >= '70')){ ?>
			<div id="adminsection">
				<span class="adminbutton" id="addblog"><a href="#">Add assignment</a></span>
				<span class="adminbutton" id="editblog"><a href="#">Edit assignment</a></span>
				<span class="adminbutton" id="deleteblog"><a href="#">Delete assignment</a></span>
				<span class="fright">Welcome to Admin Control Deck, <?php echo($_SESSION[$session_prefix .'fName']); ?>. | <a href="pass_prot/logout.php">Logout</a></span>
			</div>
		<?php } ?>
	
		<div id="blogcolumn">
	<!-- ++++++++++++++++++++Editor for New Blog+++++++++++++++++++++++++++++++++++++ -->
			<div id="newblogeditor" class="blogpost" style="display:none;">
				<form action="bin/assignments_upload.php" method="post" enctype="multipart/form-data">
					Title:<input type="text" id="blog_title" name="blog_title" />
					<span id="timedatesel" style="float:right;">Due
						<select name="hour">
							<option value="1">01</option>
							<option value="2">02</option>
							<option value="3">03</option>
							<option value="4">04</option>
							<option selected="selected" value="5">05</option>
							<option value="6">06</option>
							<option value="7">07</option>
							<option value="8">08</option>
							<option value="9">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>:
						<select name="minute">
							<option selected="selected" value="0">00</option>
							<option value="1">01</option>
							<option value="2">02</option>
							<option value="3">03</option>
							<option value="4">04</option>
							<option value="5">05</option>
							<option value="6">06</option>
							<option value="7">07</option>
							<option value="8">08</option>
							<option value="9">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
							<option value="32">32</option>
							<option value="33">33</option>
							<option value="34">34</option>
							<option value="35">35</option>
							<option value="36">36</option>
							<option value="37">37</option>
							<option value="38">38</option>
							<option value="39">39</option>
							<option value="40">40</option>
							<option value="41">41</option>
							<option value="42">42</option>
							<option value="43">43</option>
							<option value="44">44</option>
							<option value="45">45</option>
							<option value="46">46</option>
							<option value="47">47</option>
							<option value="48">48</option>
							<option value="49">49</option>
							<option value="50">50</option>
							<option value="51">51</option>
							<option value="52">52</option>
							<option value="53">53</option>
							<option value="54">54</option>
							<option value="55">55</option>
							<option value="56">56</option>
							<option value="57">57</option>
							<option value="58">58</option>
							<option value="59">59</option>
						</select>
						<select name="period">
							<option value="AM">AM</option>
							<option selected="selected" value="PM">PM</option>
						</select>
						on <input name="date" type="text" class="datepicker" />
					</span>
					<br />
					<textarea name="newblogcontent" id="newblogcontent" style="width:676px;height:500px;"></textarea><br />
					<input type="submit" value="Submit">
				</form>
			</div>
	<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->	
			
			<?php		
			if(is_array($blogdb)){	
				echo('<div id="blogcontainer">');
				$submissionsdbobj = select_txtdb('camps/'. $_SESSION[$session_prefix .'curCamp'] .'/submissionsdb.txt');
				$userassignments = query_txtdb($submissionsdbobj,'title,UNID,assignment','team,'. $_SESSION[$session_prefix .'unid']);
				foreach($blogdb as $postnum => $row){
					echo('<div class="blogpost">');
					echo('<span name="blog_unid" style="display:none;">'. $row['UNID'] .'</span>');
					echo('<span class="blogpostTitle" name="blog_title">'. stripslashes($row['title']) .'</span>');
					echo('<span name="blog_due" style="float:right;">Due at <span name="oldhour">'. date('g',$row['exp_date']) .'</span>:<span name="oldminute">'. date('i',$row['exp_date']) .'</span><span name="oldperiod">'. date('A',$row['exp_date']) .'</span> on <span name="olddate">'. date('m/d/y',$row['exp_date']) .'</span></span><br />');
					echo('<span name="blog_content" style="display:none;">'. stripslashes($row['content']) .'</span>');
					echo('<a href="assignments.php?curAssign='. $postnum .'" style="font-size:.7em; color:#0092ce;">See assignment</a>');
					//UPLOAD BOX
					if($row['exp_date'] > $timestamp){
						if($_SESSION[$session_prefix .'permissions'] == 10){
							$assignname = '';
							if($userassignments){
								foreach($userassignments as $assignrow){
									if($assignrow['assignment'] == $row['UNID']){
										$assignname = $assignrow['title'];
										$assignunid = $assignrow['UNID'];
										$assignassign = $assignrow['assignment'];
									}
								}
							}
							if($assignname != ''){
								echo('<div class="upload_box" style="float:right;"><form name="'. $postnum .'" action="bin/submissions_upload.php" method="post" enctype="multipart/form-data"><a href="submissions.php?assignment='. $assignassign .'&submission='. $assignunid .'">'. $assignname .'</a><br /><input type="hidden" name="assignment" value="'. $row['UNID'] .'" /><input name="submission" type="file" /><input type="submit" value="Replace" /></form></div>');
							} else {
								echo('<div class="upload_box" style="float:right;border-color:red;"><form name="'. $postnum .'" action="bin/submissions_upload.php" method="post" enctype="multipart/form-data"><input type="hidden" name="assignment" value="'. $row['UNID'] .'" /><input name="submission" type="file" /><input type="submit" value="Upload" /></form></div>');
							}
							echo('<div style="clear:both;"></div>');
						}
					}
					//END UPLOAD BOX
					echo('</div>');
				}
				echo('</div>');
			} else {
				echo('<h2>No assignments yet.</h2>');
			}
			?>
		</div>
		<div id="infocolumn">
			<h2>Student Instructions</h2>
			<p>Assignments throughout the week will be posted here. Students can submit their assignments via submission forms located below each assignment post. </p>
			<p>Reminder - All submissions are blinded, so files should not include any identifying labels in the name. Make sure files do not have any special characters. For example, do not use: , ! @ # $ *</p>
		</div>
		<div style="clear:both;"></div>
		
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
			<input type="hidden" name="referringPage" value="/cyberdiscovery/assignments.php" />
			<input type="submit" value="Switch" />
		</form>
	</div>
	<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
	<?php } ?>
<?php } else { //If page is a specific assignment do the following
	$submissionsdbobj = select_txtdb('camps/'. $_SESSION[$session_prefix .'curCamp'] .'/submissionsdb.txt');
	$userassignments = query_txtdb($submissionsdbobj,'title,url,assignment','team,'. $_SESSION[$session_prefix .'unid']);
	echo('<a href="assignments.php">Back</a>');
	echo('<div class="blogpost">');
	echo('<span name="blog_unid" style="display:none;">'. $blogdb[$curAssign]['UNID'] .'</span>');
	echo('<span class="blogpostTitle" name="blog_title">'. stripslashes($blogdb[$curAssign]['title']) .'</span>');
	echo('<span name="blog_due" style="float:right;">Due at <span name="oldhour">'. date('g',$blogdb[$curAssign]['exp_date']) .'</span>:<span name="oldminute">'. date('i',$blogdb[$curAssign]['exp_date']) .'</span><span name="oldperiod">'. date('A',$blogdb[$curAssign]['exp_date']) .'</span> on <span name="olddate">'. date('m/d/y',$blogdb[$curAssign]['exp_date']) .'</span></span><br />');
	if(!($blogdb[$curAssign]['edit_date'] == '')){
		$userDNS = query_txtdb($usersdbobj,'UNID,first_name,last_name','UNID,'. $blogdb[$curAssign]['edit_author']);
		if (!$userDNS){$userDNS[0]['first_name'] = 'Unknown User';}
		echo('<span class="blogpostUpdate">Last edited by '. stripslashes($userDNS[0]['first_name'] .' '. $userDNS[0]['last_name']) .' on '. date('F jS\, Y',$blogdb[$curAssign]['edit_date']) .'.</span><br />');
	}
	$userDNS = query_txtdb($usersdbobj,'UNID,first_name,last_name','UNID,'. $blogdb[$curAssign]['op']);
	if (!$userDNS){$userDNS[0]['first_name'] = 'Unknown User';}
	echo('<span class="blogpostAuthor">Posted by '. stripslashes($userDNS[0]['first_name'] .' '. $userDNS[0]['last_name']) .' on '. date('F jS\, Y',$blogdb[$curAssign]['op_date']) .'.</span><br />');
	echo('<span name="blog_content">'. stripslashes($blogdb[$curAssign]['content']) .'</span>');
	echo('</div>');
	//UPLOAD BOX
	if($blogdb[$curAssign]['exp_date'] > $timestamp){
		if($_SESSION[$session_prefix .'permissions'] == 10){
			$assignname = '';
			if($userassignments){
				foreach($userassignments as $assignrow){
					if($assignrow['assignment'] == $blogdb[$curAssign]['UNID']){
						$assignname = $assignrow['title'];
						$assignurl = $assignrow['url'];
					}
				}
			}
			if($assignname != ''){
				echo('<div class="upload_box" style="float:right;"><form action="bin/submissions_upload.php" method="post" enctype="multipart/form-data"><a href="'. $assignurl .'" style="color:#0092ce;">'. $assignname .'</a><br /><input type="hidden" name="assignment" value="'. $blogdb[$curAssign]['UNID'] .'" /><input name="submission" type="file" /><input type="submit" value="Replace" /></form></div>');
			} else {
				echo('<div class="upload_box" style="float:right;border-color:red;"><form action="bin/submissions_upload.php" method="post" enctype="multipart/form-data"><input type="hidden" name="assignment" value="'. $blogdb[$curAssign]['UNID'] .'" /><input name="submission" type="file" /><input type="submit" value="Upload" /></form></div>');
			}
			echo('<div style="clear:both;"></div>');
		}
	}
	//END UPLOAD BOX
} ?>
<?php include('footer.php'); ?>