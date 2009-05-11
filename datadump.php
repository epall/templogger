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


$deriv = null;

function deriv($f1, $f2, $d1, $d2){
	if($f1 == null || $f2 == null){
		return null;
	}
	return ($f2-$f1); ///($d2-$d1)*3600*10;
}

if(@$_GET['deriv']){
	$dataset = array_slice($graphs[$_GET['deriv']], $start_point, $end_point);
  // $divisor = $sensors[$_GET['deriv']]['divisor'];
  //   if(isset($divisor)){
  //     array_walk($dataset, 'divide', $divisor);
  //   }
	$data2 = $dataset;
	array_shift($data2);
	$dates2 = $dates;
	array_shift($dates2);
//	echo "dataset is ".count($dataset)." long<br/>";
//	echo "data2 is ".count($data2)." long<br/>";
	$deriv = array_map("deriv", $dataset, $data2, $dates, $dates2);
//	echo "deriv is ".count($deriv)." long<br/>";
}

$maxval = 0;

echo "Date,";
foreach(array_keys($graphs) as $key){
	echo $key.",";
	if($maxval < count($graphs[$key])){
		$maxval = count($graphs[$key]);
	}
}

if(isset($_GET['deriv'])){
  echo "Derivative of ".$_GET['deriv'];
}

echo "\n";

$index = 0;
if ($end_point < $maxval){
	$maxval = $end_point;
}

$index2 = 0;

for($index = $start_point; $index < $maxval; $index++){
	echo strftime("%D %H:%M", $dates[$index]).",";
	foreach(array_keys($graphs) as $key){
		echo $graphs[$key][$index].",";
	}
  if(isset($_GET['deriv'])){
    echo $deriv[$index2];
  }
  $index2++;
	echo "\n";
}
?>
