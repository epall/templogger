<?php

$file = fopen('mappings.dat', 'r');
$text = fread($file, filesize('mappings.dat'));
fclose($file);
$sensors = unserialize($text);

if(!isset($sensors))
  $sensors = array();

if(isset($_POST['action'])){
  if($_POST['action'] == "Add"){
    $newsensor = array();
    $newsensor['sensorname'] = $_POST['sensorname'];
    $newsensor['displayname'] = $_POST['displayname'];
    $newsensor['color'] = "#".$_POST['color'];
    $sensors[$_POST['sensorname']] = $newsensor;
  }
  else if($_POST['action'] == "Update"){
    foreach($_POST['remove'] as $toRemove)
      unset($sensors[$toRemove]);
  }
  
  $file = fopen('mappings.dat', 'w');
  fwrite($file, serialize($sensors));
  fclose($file);
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>Edit sensor information</title>
    <script type="text/javascript" src="jscolor/jscolor.js"></script>
  </head>
  <body>
    <h1>Sensor information</h1>
    <table border="1" cellspacing="1" cellpadding="5">
      <form method="post">
      <tr><th>Sensor Name</th><th>Display name</th>
        <th>Color</th><th>Remove</th></tr>
      <?php
        foreach(array_filter($sensors, "is_array") as $sensor){
          ?><tr>
            <td><?= $sensor['sensorname'] ?></td>
            <td><?= $sensor['displayname'] ?></td>
            <td><?= $sensor['color'] ?></td>
            <td>
              <input type="checkbox" name="remove[]" value="<?= $sensor['sensorname'] ?>" />
            </td>
          </tr>
          <?php
        }
      ?>
      <tr>
        <td colspan="6" style="text-align:right"><input type="submit" name="action" value="Update" /></td>
      </tr>
      </form>
      <tr>
        <form method="post">
        <td><input type="text" size="15" name="sensorname" /></td>
        <td><input type="text" size="18" name="displayname" /></td>
        <td>Click on the field<br>to select color<br><input type="text" size="8" name="color" class="color" /></td>
        <td><input type="submit" name="action" value="Add" /></td>
      </form>
      </tr>
    </table>
  </body>
  <a href="index.php">Back to graph</a>
</html>
