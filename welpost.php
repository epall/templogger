<?php
date_default_timezone_set("America/Los_Angeles");
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
}
else {
  echo "testing!";
}
?>

<?php
function sensor($sensorName){
  switch($sensorName){
    case "_Cc":
    return false;
    case "_Ll":
    return false;
    case "_Gg":
    return false;
    case "_Bb":
    return false;
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

$handle = fopen("tinidata.txt", "a");
fwrite($handle, 'DATE '.date("Y-m-d H:i:s")."\n");
foreach($values as $key){
  fwrite($handle, $key);
  fwrite($handle, ',');
  fwrite($handle, $_GET[$key]);
  fwrite($handle, "\n");
}
fclose($handle);
?>
