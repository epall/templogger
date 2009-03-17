<?php

DEFINE ("TTF_DIR","/users/home/epall/domains/sundialtelemetrics.com/web/public/templogger/" );  

include ("jpgraph-2.3.4/src/jpgraph.php");
include ("jpgraph-2.3.4/src/jpgraph_line.php");
include ("jpgraph-2.3.4/src/jpgraph_date.php");

// load data from file
require_once ("parsefile.php");
require_once ("mappings.php");

// Some data
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
$end_length = $end_point-$start_point;
/*
echo "<br/>start at $start_point, end at $end_point, length $end_length";
$total = count($dates);
$theoretical = $total-$start_point;
echo "<br/>theoretical len is $theoretical";
$last = dates[1];
echo "<br/>End at $last";
*/
$end_point = $end_length;

function is_requested($sensor){
    $selected = false;
    
    if(@$_GET['addresses']){
        $addresses = $_GET['addresses'];
        foreach(explode(',', $addresses) as $address){
            if($sensor['sensorname'] == $address){
                $selected = true;
            }
        }
    }
    else{
        $selected = true;
    }
    
    return $selected;
}

$sensors = array_filter($sensors, 'is_requested');

$dates = array_slice($dates, $start_point, $end_point);

function deriv($f1, $f2, $d1, $d2){
	if($f1 == null || $f2 == null){
		return null;
	}
	return ($f2-$f1)/($d2-$d1)*3600*10;
}

function cooling($v1, $v2, $derivative){
  return $derivative/($v1-$v2);
}

function divide(&$value, $key, $divisor){
  $value = $value/$divisor;
}

// Create the graph. These two calls are always required
$graph = new Graph(950,480,"auto");
$graph->img->SetMargin(60, 55, 70, 70);
$graph->SetScale("datlin");

foreach($sensors as $sensor){
  $sensorname = trim($sensor['sensorname']);
	if(isset($graphs[$sensorname])){
    $divisor = $sensor['divisor'];
		$ydata = array_slice($graphs[$sensorname], $start_point, $end_point);
    if(isset($divisor)){
      array_walk($ydata, 'divide', $divisor);
    }

		$lineplot = new LinePlot($ydata, $dates);
		$lineplot->SetColor(strtolower($sensor['color']));
		$lineplot->SetLegend($sensor['displayname']);
	
		$graph->Add($lineplot);
	}
}

$deriv = null;

if(@$_GET['deriv']){
	$dataset = array_slice($graphs[$_GET['deriv']], $start_point, $end_point);
	$divisor = $sensors[$_GET['deriv']]['divisor'];
  if(isset($divisor)){
    array_walk($dataset, 'divide', $divisor);
  }
	$data2 = $dataset;
	array_shift($data2);
	$dates2 = $dates;
	array_shift($dates2);
//	echo "dataset is ".count($dataset)." long<br/>";
//	echo "data2 is ".count($data2)." long<br/>";
	$deriv = array_map("deriv", $dataset, $data2, $dates, $dates2);
//	echo "deriv is ".count($deriv)." long<br/>";
	$lineplot = new LinePlot($deriv, $dates);
	$lineplot->SetColor('black');
	$lineplot->SetLegend('rate of change (times 10)');
	$graph->Add($lineplot);
}

if(isset($inside_sensor) && isset($outside_sensor) && isset($deriv)){
	$inside = array_slice($graphs[$inside_sensor['sensorname']], $start_point, $end_point);
	$outside = array_slice($graphs[$outside_sensor['sensorname']], $start_point, $end_point);
  $divisor = $inside_sensor['divisor'];
  if(isset($divisor)){
    array_walk($inside, 'divide', $divisor);
  }
  $divisor2 = $outside_sensor['divisor'];
  if(isset($divisor2)){
    array_walk($outside, 'divide', $divisor2);
  }
	$diff = array_map("cooling", $inside, $outside, $deriv);
  array_pop($diff);
  array_pop($dates);
	$lineplot = new LinePlot($diff, $dates);
	$lineplot->SetColor('darkgray');
	$lineplot->SetLegend('Deriv/Diff');
	$graph->SetY2Scale('lin');
	$graph->AddY2($lineplot);
}

//$graph->title->Set ('Temperatures ('.$start_point.'-'.$end_point.')');
$graph->title->Set ('Temperatures');
//$graph->xaxis->title->Set('Time');
$graph->yaxis->title->Set('Degrees Celsius');
$graph->xaxis->scale->SetDateFormat('m/d h:i a');
$graph->xaxis->SetLabelAngle(30);
$graph->xaxis->SetFont(FF_VERA);

$graph->legend->Pos(0.05,0.05,'left','top');
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->SetColumns(8);

$graph->SetTickDensity(TICKD_NORMAL,TICKD_SPARSE);

// Display the graph
$graph->Stroke();
?>
