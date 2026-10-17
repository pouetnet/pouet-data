<!DOCTYPE html>
<html>
<head>
  <title>pou&euml;t.net data dumps</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">table{border-collapse:collapse;float:left;margin-right:20px;}td,th{border:1px solid #ccc;padding:3px 10px;overflow:hidden;}th{background:#ccc}tr:hover{background:#eee}*{font-family:segoe ui,sans-serif;}label,input[type=submit]{display:block;}input[type=text]{width:300px;padding:3px 6px;}</style>
</head>
<body>

<h1>Fresh dumps of (part of) the Pou&euml;t.net database</h1>
<p>New dump every wednesday morning. Also available as <a href="json.php">a JSON feed</a>.</p>
<p>Looking for live data? Check our <a href='https://api.pouet.net/'>API</a>.</p>

<?php
$dirs = glob("dumps/*");
$a = array();
foreach($dirs as $dir)
{
  $a = array_merge($a,glob($dir."/*"));
}
rsort($a);

$dates = array();
$tables = array();
$files = array();
foreach($a as $v)
{
  if (strstr($v,".inprogress")) continue;
  if (!preg_match("/pouetdatadump-(.*)-(.*).json/",$v,$m)) continue;
  $tables[$m[1]] = true;
  $dates[$m[2]] = true;
  $files[$m[1].$m[2]] = $v;
}

echo "<table>\n";

$lastYear = "";
foreach($dates as $date=>$_true)
{
  $year = substr($date,0,4);
  if ($year != $lastYear)
  {
    echo "<tr>\n";
    echo "  <th>".$year."</th>\n";
    foreach($tables as $k=>$v)
      echo "  <th>".$k."</th>\n";
    echo "</tr>\n";
    $lastYear = $year;
  }
  echo "<tr>\n";
  echo "  <th>".$date."</th>\n";
  foreach($tables as $table=>$_true2)
  {
    $filename = @$files[ $table . $date ]; // "pouetdatadump-".$k2."-".$k.".json.gz";
    if ($filename && file_exists($filename))
    {
      $nfo = pathinfo($filename);
      echo "  <td><a href='".$filename."'>download .".$nfo["extension"]."</a> (".(int)(filesize($filename)/1024)."KB)</td>\n";
    }
    else
    {
      echo "  <td>&nbsp;</td>\n";
    }
  }
  echo "</tr>\n";
}
 
echo "</table>\n";
?>
</body>
</html>