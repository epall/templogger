<?php

include ("jpgraph-2.1.2/src/jpgraph.php");
include ("jpgraph-2.1.2/src/jpgraph_line.php");
include ("jpgraph-2.1.2/src/jpgraph_date.php");

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

function is_requested($mapping){
    $selected = false;
    
    if(@$_GET['addresses']){
        $addresses = $_GET['addresses'];
        foreach(explode(',', $addresses) as $address){
            if($mapping[1] == $address){
                $selected = true;
            }
        }
    }
    else{
        $selected = true;
    }
    
    return $selected;
}

$mappings = array_filter($mappings, 'is_requested');

$dates = array_slice($dates, $start_point, $end_point);

function deriv($f1, $f2, $d1, $d2){
	if($f1 == null || $f2 == null){
		return null;
	}
	return ($f2-$f1)/($d2-$d1)*3600;
}

// Create the graph. These two calls are always required
$graph = new Graph(900,480,"auto");
$graph->SetMargin(50, 40, 70, 90);
$graph->SetScale("datlin");

foreach($mappings as $mapping){
	if(@$graphs[$mapping[1]] != null){
		$ydata = array_slice($graphs[$mapping[1]], $start_point, $end_point);

		$lineplot = new LinePlot($ydata, $dates);
		$lineplot->SetColor(strtolower($mapping[2]));
		$lineplot->SetLegend($mapping[0]);
	
		$graph->Add($lineplot);
	}
}

if(@$_GET['deriv']){
	$dataset = array_slice($graphs[$_GET['deriv']], $start_point, $end_point);
	$data2 = $dataset;
	array_shift($data2);
	$dates2 = $dates;
	array_shift($dates2);
//	echo "dataset is ".count($dataset)." long<br/>";
//	echo "data2 is ".count($data2)." long<br/>";
	$deriv = array_map("deriv", $dataset, $data2, $dates, $dates2);
//	echo "deriv is ".count($deriv)." long<br/>";
	$graph->SetY2Scale('lin');
	$lineplot = new LinePlot($deriv, $dates);
	$lineplot->SetColor('black');
	$lineplot->SetLegend('rate of change');
	$graph->AddY2($lineplot);
}

//$graph->title->Set ('Temperatures ('.$start_point.'-'.$end_point.')');
$graph->title->Set ('Temperatures');
//$graph->xaxis->title->Set('Time');
$graph->yaxis->title->Set('Degrees Fahrenheit');
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
