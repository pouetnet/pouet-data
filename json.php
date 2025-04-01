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
  preg_match("/pouetdatadump-(.*)-(.*).json/",$v,$m);
  if (strstr($v,".inprogress")) continue;
  $tables[$m[1]] = true;
  $dates[$m[2]] = true;
  $files[$m[1].$m[2]] = $v;
}

$output = array();
$latest = false;
foreach($dates as $date=>$_true)
{
  foreach($tables as $table=>$_true2)
  {
    $filename = @$files[ $table . $date ]; // "pouetdatadump-".$k2."-".$k.".json.gz";
    if ($filename && file_exists($filename))
    {
      $output["dumps"][$date][$table]["filename"] = basename($filename);
      $output["dumps"][$date][$table]["url"] = "https://data.pouet.net/".$filename;
      $output["dumps"][$date][$table]["size_in_bytes"] = filesize($filename);
    }
  }
}
arsort($output["dumps"]);
$output["latest"] = reset($output["dumps"]);

header("Content-type: application/json; charset=utf-8");
echo json_encode($output,JSON_PRETTY_PRINT);
