<?php
$handle = fopen('datalog.txt', 'r');
$graphs = array();
$dates = array();
while(!feof($handle)){
	$dataline = fgets($handle);
	if(ereg('^DATE ', $dataline)){
		$dates[] = strtotime(substr($dataline, 5));
	}
	else{
		$values = explode(',', $dataline);
		if($graphs[$values[0]]){
			$graphs[$values[0]][] = $values[1] + 0;
		}
		else{
			if(count($dates)>1){
				$graphs[$values[0]] = array_fill(0, count($dates)-1, null);
			}
			$graphs[$values[0]][] = $values[1] + 0;
		}
	}
}
fclose($handle);
?>