<?php
header ("Content-Type: text/csv");
header ("Content-Disposition: inline; filename=\"temperatures.csv\"");
include ("parsefile.php");

$graph_values = array_values($graphs);
$start_point = 0;
$end_point = count($dates);

if($_GET['start']){
	$start = $_GET['start'];
	for($i = 0; $i < count($dates); $i++){
		if($start <= $dates[$i]){
			$start_point = $i;
			break;
		}
	}
}

if($_GET['end']){
	$end = $_GET['end'];
	for($i = 0; $i < count($dates); $i++){
		if($end <= $dates[$i]){
			$end_point = $i;
			break;
		}
	}
}
if($start_point == -1){
	$start_point = $end_point;
}


$maxval = 0;

echo "Date,";
foreach(array_keys($graphs) as $key){
	echo $key.",";
	if($maxval < count($graphs[$key])){
		$maxval = count($graphs[$key]);
	}
}
echo "\n";

$index = 0;
if ($end_point < $maxval){
	$maxval = $end_point;
}

for($index = $start_point; $index < $maxval; $index++){
	echo strftime("%D %H:%M", $dates[$index]).",";
	foreach(array_keys($graphs) as $key){
		echo$graphs[$key][$index].",";
	}
	echo "\n";
}

?>
