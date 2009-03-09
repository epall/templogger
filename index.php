<?php
require_once("mappings.php");
$start = null;
$end = null;

$start = @$_GET['start'];
$end = @$_GET['end'];
$days = @$_GET['days'];

$startdate = @$_GET['startdate'];
$enddate = @$_GET['enddate'];

if($days){
	$end = time();
	$start = $end-(60*60*24*$days);
}

// start/end date take precedence
if($startdate != "" && $enddate != ""){
	$start = strtotime($startdate);
	if($start == FALSE){
		$start = strtotime("yesterday");
		$startdate = "invalid";
	}
	$end = strtotime($enddate);
}

?>

<html>
<head>
	<title>Allen house temperature log</title>
	<script type="text/javascript">
	function cleardates(){
		document.forms[0].startdate.value = "";
		document.forms[0].enddate.value = "";
	}
	function cleardays(){
		document.forms[0].days.value = "";
	}
	</script>
</head>
<body>
<h1>Allen house temperature log</h1>
<img src="graph.php?<?php
if ($start){ echo 'start='.$start;}
if ($end){ echo '&end='.$end;}
if (@$_GET['addresses']){
    echo '&addresses=';
    foreach($_GET['addresses'] as $address){
        echo $address;
        echo ',';
    }
}
if(@$_GET['deriv']){
	echo "&deriv=";
	echo $_GET['deriv'];
}
?>"/>
<br/>
<form method="get">
Last <input name="days" type="text" onfocus="cleardates();" size="3" <?php if($days) {echo "value=\"".$days."\"";}?>> days.
<br/>
<b>OR: <a href="http://www.gnu.org/software/tar/manual/html_node/tar_109.html">format</a></b>
<br/>
Start date: <input name="startdate" onfocus="cleardays();" type="text" size="20" <?php if(@$startdate) {echo "value=\"".$startdate."\"";}?>>
<br/>
End date: <input name="enddate" onfocus="cleardays();" type="text" size="20" <?php if(@$enddate) {echo "value=\"".$enddate."\"";}?>>
<br/>
<table cellpadding="2">
<thead>
<tr><th>Show</th><th>Name (color)</th><th>Use for rate</th></tr>
</thead>
<tbody>
<?php
foreach($mappings as $mapping){
    ?><tr><td><input type="checkbox" name="addresses[]" value="<?= $mapping[1]?>" <?php if(@array_search($mapping[1], $_GET['addresses']) !== false) {echo "checked";} ?>/></td><td> <?= $mapping[0] ?> (<?=$mapping[2]?>)</td>
<td><input type="radio" name="deriv" value="<?=$mapping[1]?>" <?php if(@$_GET['deriv'] == $mapping[1]){echo "checked";}?>/></td>
</tr>
<?php
}?>
</tbody>
</table>
<input type="submit" value="go"/>
</form>
<br/>
<a href="datadump.php">Get all data (CSV)</a>
<a href="datadump.php?<?php if ($start){ echo 'start='.$start;} if ($end){ echo '&end='.$end;}?>">Get displayed data (CSV)</a>
</body>
</html>
