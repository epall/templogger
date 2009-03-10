<?php
$file = fopen('mappings.dat', 'r');
$text = fread($file, filesize('mappings.dat'));
fclose($file);
$sensors = unserialize($text);
$sensors = array_filter($sensors, "is_array");
?>
