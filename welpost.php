<?php
// forward data to Phil's welserver.com
$testing = @$_GET['test'] == 1; // check for testing mode
if(!$testing){   // IF not testing, forward GET parameters to OCH and send the response back to the WEL
  $ochRequest = curl_init();
  curl_setopt($ochRequest, CURLOPT_URL, "http://www.welserver.com/cgi-bin/WEL_post.cgi?".$_SERVER["QUERY_STRING"]);
  curl_setopt($ochRequest, CURLOPT_HEADER, false);
  $header = array("Connection" => "Close", "host" => "www.welserver.com");
  curl_setopt($ochRequest, CURLOPT_HTTPHEADER, $header);
  curl_exec($ochRequest);
  curl_close($ochRequest);
} else { // testing mode
  $ochRequest = curl_init();
  curl_setopt($ochRequest, CURLOPT_URL, "http://sundialtelemetrics.com/templogger/test.php?".$_SERVER["QUERY_STRING"]);
  curl_setopt($ochRequest, CURLOPT_HEADER, false);
  $header = array("Connection" => "Close", "host" => "sundialtelemetrics.com");
  curl_setopt($ochRequest, CURLOPT_HTTPHEADER, $header);
  curl_exec($ochRequest);
  curl_close($ochRequest);
}
?>

<?php
function sensor($sensorName){
  switch($sensorName){
    case "Uu":
    return false;
    case "Ii":
    return false;
    case "Vv":
    return false;
    case "Ee":
    return false;
    case "Pp":
    return false;
    case "test":
    return false;
    case "Date":
    return false;
    case "Time":
    return false;
    default:
    return true;
  }
}
$values = array_filter(array_keys($_GET), sensor);

$handle = fopen("datalog.txt", "a");
fwrite($handle, 'DATE '.$_GET['Date'].' '.$_GET['Time']."\n");
foreach($values as $key){
  fwrite($handle, $_GET['Uu'].':'.$key);
  fwrite($handle, ',');

  // divide by 1000 due to WEL stupidity
  if($key[0] == 'T')
    fwrite($handle, $_GET[$key]/1000);
  else
    fwrite($handle, $_GET[$key]);
  fwrite($handle, "\n");
}
fclose($handle);
?>