<?php
$file = fopen('mappings.dat', 'r');
$text = fread($file, filesize('mappings.dat'));
fclose($file);
$sensors = unserialize($text);

$inside_sensor = null;
$outside_sensor = null;

if(isset($sensors['__INSIDE']))
  $inside_sensor = $sensors[$sensors['__INSIDE']];
if(isset($sensors['__OUTSIDE']))
  $outside_sensor = $sensors[$sensors['__OUTSIDE']];

$sensors = array_filter($sensors, "is_array");
?>
