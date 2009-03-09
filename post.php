<?php
date_default_timezone_set("America/Los_Angeles");
$values = file_get_contents("php://input");
if($values == ""){
}
else{
  $handle = fopen("tinidata.txt", "a");
  $dateText = "DATE ".date("Y-m-d H:i:s")."\n";
  fwrite($handle, $dateText);
  fwrite($handle, $values);
  fclose($handle);
}
?>
