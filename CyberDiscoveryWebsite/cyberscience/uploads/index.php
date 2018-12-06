<?php
	session_start();	
	if(!($_SESSION['permissions'] > 1)){
		$_SESSION['referingPage'] = $_SERVER['SCRIPT_NAME'];
		header('Location: pass_prot/login.php?error=login');
		exit;
	}
	
	$myFile = "tableheaders.txt";
	$fh = fopen($myFile, 'r');
	$headers = fread($fh, filesize($myFile));
	fclose($fh);
	$headArray = explode('(*)',$headers);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<meta http-equiv="Content-Type" content="charset=utf-8" />
                <script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
                <script type="text/javascript" src="header.js"></script>
		<title>Cyber Curriculum</title>
    	<?php $webPage='index'; include('pagesetup.php');?>
		<?php   
		$path = 'uploads/';
		$counter = 1;
		$revArray = array( 0 => 0 );
		$dateArray = array( 0 => 0 );
		$initArray = array( 0 => 0 );
		$nameArray = array( 0 => 0 );
		$numArray = array( 0 => 0 );
		$fileArray = array( 0 => 0 );
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if($file!="." && $file!="..") {
					
					$numExplosion = explode(_,$file);
					$numSelector = $numExplosion[0];
					$num = str_replace('CS','',$numSelector);
					
					$nameExplosionWInit = explode('.',$numExplosion[1]);
					$nameExplosion = explode('-', $nameExplosionWInit[0]);
					$name = strtoupper($nameExplosion[0]);
					if($name == 'MN'){$name = str_replace('MN','Master Notes',$name);}
					if($name == 'HW'){$name = str_replace('HW','Homework',$name);}
					if($name == 'LP'){$name = str_replace('LP','Lesson Plan',$name);}
					if($name == 'PP'){$name = str_replace('PP','PowerPoint',$name);}
					if($name == 'WS'){$name = str_replace('WS','Work Sheet',$name);}
					
					$InitSelector = $nameExplosion[1];
					$initSelector2 = explode('**' , $InitSelector);
					$init = $initSelector2[0];
					
					$date = filemtime('uploads/' . $file);
					
					$rev = $num . $name;
					
					if(in_array($rev , $revArray)){
						$revKey = array_search($rev, $revArray);
						if($dateArray[$revKey] < $date){ 
							$revArray[$revKey] = $rev;
							$dateArray[$revKey] = $date;
							$initArray[$revKey] = $init;
							$nameArray[$revKey] = $name;
							$numArray[$revKey] = $num;
							$fileArray[$revKey] = $file;
						}
					} else {
						$tempArray = array( $counter => $rev );
						$revArray = array_merge((array)$revArray,(array)$tempArray);
						$tempArray = array( $counter => $date );
						$dateArray = array_merge((array)$dateArray,(array)$tempArray);
						$tempArray = array( $counter => $init );
						$initArray = array_merge((array)$initArray,(array)$tempArray);
						$tempArray = array( $counter => $name );
						$nameArray = array_merge((array)$nameArray,(array)$tempArray);
						$tempArray = array( $counter => $num );
						$numArray = array_merge((array)$numArray,(array)$tempArray);
						$tempArray = array( $counter => $file );
						$fileArray = array_merge((array)$fileArray,(array)$tempArray);
						$counter += 1;
					}
				}
			}
			closedir($handle);
		}
		$count=1;
		echo('<div style="padding:10px;">');
        echo('<table style="border-spacing:0px;">');
        echo('<tr>');
		echo('<td class="table_head"></td>');
        echo('<td class="table_head">Monday</td>');
        echo('<td class="table_head">Tuesday</td>');
        echo('<td class="table_head">Wednesday</td>');
        echo('<td class="table_head">Thursday</td>');
        echo('<td class="table_head">Friday</td>');
        echo('</tr>');
        
        for ($i=1; $i<=16; $i++) {
            echo('<tr>');
			if ($i%2 == 0) {
					echo('<td class="table_left" style="background-color:#DDDDDD;">Week ' . $i . '</td>');
				}
				else {
                	echo('<td class="table_left">Week ' . $i . '</td>');
				}

            for ($j=1; $j<=5; $j++) {
				$head=$headArray[$count];
				$temp='';
				if (in_array($count,$numArray)) {
					$where = array_keys($numArray,$count);
					foreach ($where as $w) {
						$temp.='<a href="' . $path . $fileArray[$w] . '">';
						$temp.=$nameArray[$w];
						$temp.='</a> by ';
						$temp.=$initArray[$w];
						$temp.=' on ' . date('n/d/y', $dateArray[$w]);
						$temp.='<br>';
					}				
				}
				else {
					$temp="";
				}
				if ($i%2 == 0) {
					echo('<td class="table_cell"  style="background-color:#DDDDDD;" valign="top"><div style="min-height:100px;padding:2px;background-color:#DDDDDD;"><div name="cell' . $count . '" class="editcell" style="background-color:#999;">' . $count . ' <span name="edittext' . $count . '">' . $head . '</span></div>' . $temp . '</div></td>');
				}
				else {
                	echo('<td class="table_cell"  valign="top"><div style="min-height:100px;padding:2px;"><div name="cell' . $count . '" class="editcell" style="background-color:#999;">' . $count . ' <span name="edittext' . $count . '">' . $head . '</span></div>' . $temp . '</div></td>');
				}
				$count+=1;
            }
            echo('</tr>');
        }
        echo('</table>');
		echo('</div>');
        ?>
        
        <?php if ($_SESSION['permissions'] >= 90){?>
        	<div id="adminedit" style="position:fixed; bottom:0px; right:0px; background-color:#CCC;border-style:solid; border-width:1px; border-color:#000; border-bottom-style:none;">
            	<a style="display:block; padding:3px; font-size:18px; text-decoration:none;color:#000;">Edit Table Headings</a>
            </div>
		<?php } ?>
	</body>
</html>	