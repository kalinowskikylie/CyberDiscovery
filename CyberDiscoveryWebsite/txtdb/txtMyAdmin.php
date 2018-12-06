<?php
	$curTXTDB = $_GET['TXTDB'];
	if(isset($curTXTDB)){
		include('txtdb_commands.php');
		$selectedTXTDB = select_txtdb($curTXTDB);
		$selectedTXTDB = query_txtdb($selectedTXTDB,'*','','last_name');
		$table = build_txtdb($selectedTXTDB);
	}
?>

<!doctype html>
<html>
<head>
	<title>txtMyAdmin</title>
	<link rel="stylesheet" type="text/css" media="screen,print" href="ecocar.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
</head>
<body>
	<h1>txtMyAdmin</h1>
	<div id="tableManageButtons">
		<form action="txtMyAdmin_bin.php" method="post" onSubmit="return validatecreate()">
			<input name="createTXTDBname" type="test" />
			<input type="submit" onclick="return validatecreateclick()" value="Create Database" />
			<br />
		</form>
		<form action="txtMyAdmin_bin.php" method="post" onsubmit="return validateselect()">
			<input name="selectTXTDBname" type="test" />
			<input type="submit" value="Select Database" />
			<br />
		</form>
		<form action="txtMyAdmin_bin.php" method="post" onsubmit="return validatedelete()">
			<input name="deleteTXTDBname" type="test" value="<?php echo $curTXTDB ?>"/>
			<input type="submit" onclick="return validatedeleteclick()" value="Delete Database" />
		</form>
	</div>
	<div id="tablecontainer">
	<?php if(isset($table)){echo $table;} ?>
	</div>
	<script type="text/javascript">
	function validatedeleteclick(){
		var valdel = $('input[name="deleteTXTDBname"]').val();
		if(valdel == ''){
			return false;
		}
	}
	function validatecreateclick(){
		var valcre = $('input[name="createTXTDBname"]').val();
		if(valcre == ''){
			return false;
		}
	}
	function validateselect(){
		var selval = $('input[name="selectTXTDBname"]').val();
		if(selval == ''){
			return false;
		}
	}
	function validatecreate(){
		var answer = confirm("Are you sure you would like to create this database?")
		if (answer){
			return true;
		} else {
			return false;
		}
	}
	function validatedelete(){
		var answer = confirm("Are you sure you would like to delete this database?")
		if (answer){
			return true;
		} else {
			return false;
		}
	}
	</script>
</body>
</html>