<?php
$file = fopen('mappings.dat', 'r');
$text = fread($file, filesize('mappings.dat'));
fclose($file);
$sensors = unserialize($text);

function sensors_to_mappings($sensor){
  return array($sensor['displayname'], $sensor['sensorname'], $sensor['color']);
}

$mappings = array_map('sensors_to_mappings', array_filter($sensors, "is_array"));
?>
