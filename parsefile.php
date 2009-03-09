<?
//include_once('./profiler.inc');
//$prof = new Profiler(true);
$startstamp = 0; // start at epoch
if($_GET['start']){
	// so not at epoch
	$startstamp = $_GET['start'];
}
$handle = fopen('tinidata.txt', 'r');
$graphs = array();
$counts = array();
$dates = array();
$datecount = 0;

$firstline = fgets($handle);
$firstlinedate = time(); // set to right now by default
if(ereg('^DATE ', $firstline)){ // not null or something
	$firstlinedate = strtotime(substr($firstline, 5));
	fseek($handle, 0); // reset back to beginning after peeking
}

if($firstlinedate > $startstamp){
	// we want data earlier than the unzipped file
	process_older_file($startstamp, 1);
}

while(!feof($handle)){ // okay, now process the current data
	$dataline = fgets($handle);
	process_line($dataline);
}
fclose($handle);

$graphcount = count($graphs);

foreach($counts as $key => $this_count){ // pad from end of data to now
	if($this_count < $datecount){
		$graphs[$key][] = null;
		$counts[$key]++;
//		echo "filling in record for $key; went from $gcountbefore to $gcountafter. Dates at $datesize. Thiscount = $this_count\n";
	}
}


function process_older_file($startstamp, $number){
	$file = gzopen('tinidata.txt.'.$number.'.gz', 'r');
//	echo "attempting to process".'tinidata.txt.'.$number.'.gz';
	if($file){
		$firstline = gzgets($file);
		if(ereg('^DATE ', $firstline)){ // not null or something
		//	echo "found first line <br/>...";
			$firstlinedate = strtotime(substr($firstline, 5));
			fseek($file, 0); // reset back to beginning after peeking
		}
		if($firstlinedate > $startstamp){
			process_older_file($startstamp, $number+1);
		}
		// okay, all the older stuff is parsed. Now do this file.
		while(!gzeof($file)){ // process it
			$dataline = gzgets($file);
			process_line($dataline);
		}
		gzclose($file);
	}
	// no file so end recursion
}

function process_line($dataline){
	global $counts, $graphs, $dates, $datecount;
	if(ereg('^DATE ', $dataline)){ // it's a date line, so pump it into dates
		// check to see if any arrays need to be padded
		foreach($counts as $key => $this_count){
			if($this_count < $datecount){
				$graphs[$key][] = null;
				$counts[$key]++;
			}
		}
		/*
		foreach($graphs as $ydataX){
			if(count($ydata) < count($dates)){
				$ydata[] = null;
			}
		}
		*/

		// okay, now push it onto dates
		$dates[] = strtotime(substr($dataline, 5));
		$datecount++;
	}
	else{
		if($dataline == ""){
			return;
		}
		$values = explode(',', $dataline); // split address and value
		if(@$graphs[$values[0]]){ // if we already have an array for it
			$graphs[$values[0]][] = $values[1] + 0; // pump it in as a number
		}
		else{ // time to create an array
			if($datecount>1){ // we already have some records; fill it up first
				$graphs[$values[0]] = array_fill(0, $datecount-1, null);
				$counts[$values[0]] = count($graphs[$values[0]]);
			}
			else{
				$counts[$values[0]] = 0;
			}
			$graphs[$values[0]][] = $values[1] + 0;// now append like we did up there
		}
		$counts[$values[0]]++; // increment our count
	}
	
}
?>
