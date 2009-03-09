<?php
// 080118 commented out unplugged sensors 8e 92 bc 3a 03 99
$mappings = array();
//
//
//      FO is built ino the humidity module but is not identical so sw does not poll it

$mappings[] = array("67 hot Rt", "6700000105615828", "red");
//$mappings[] = array("8E hot Left", "8E000000FC9FF328", "purple");      // activated 071127  ~ 3:00 am
//$mappings[] = array("92 cold Left", "9200000109D4E428", "blue");  // activated 071127 20:24
//$mappings[] = array("BC cold Rt", "BC0000010507A728", "lime");      // activated 071127 20:24

$mappings[] = array("F8 MB mid", "F8000001096CA628", "blue");
//$mappings[] = array("3A MB bot", "3A000001095EF328", "violet");
$mappings[] = array("36 PB mid", "3600000109546328", "green");


// foo


//$mappings[] = array("03 into MB", "0300000109DBDA28", "brown");
//$mappings[] = array("BB outof MB", "BB00000109E0E228", "pink");
//$mappings[] = array("3F out of PB", "3F0000010A0D0C28", "purple");

$mappings[] = array("E7 into HV", "E700000109D91128", "orange");
$mappings[] = array("B6 outof HV", "B600000109729A28", "cyan");


$mappings[] = array("E5 humidity", "E5000000A93A1426", "blue");
$mappings[] = array("29 Attic", "2900000089A13228", "firebrick");
$mappings[] = array("18 Deck", "18000000FCA1E628", "orange");
$mappings[] = array("2D Bath", "2D000000FBF82B28", "black");
//$mappings[] = array("4C Cellar", "4C000000FBE5E928", "purple");
//below is for the 4x10 Alten 110GS collextor);

// below added Jan 18 2007
// new as of jan 21 2007
$mappings[] = array("A0 HWH flue", "A000000109912D28", "purple");
//$mappings[] = array("CD supply", "CD00000109B0AB28", "lime");
$mappings[] = array("F2 into GHWH", "F200000104E86228", "yellowgreen");
//$mappings[] = array("A1 GHW out", "A1000001097A7A28", "red");
$mappings[] = array("D1 2ft", "D100000109525A28", "blue");
$mappings[] = array("C0 gwh exit", "C0000001096D4228", "red");
//$mappings[] = array("9D 12ft", "9D00000109505028", "black");
//$mappings[] = array("CF", "CF00000109B5C528", "cyan");
?>
