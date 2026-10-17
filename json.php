<?php

$dirs = glob("dumps/*");
$a = [];
foreach ($dirs as $dir) {
    $a = array_merge($a, glob($dir . "/*"));
}
rsort($a);

$dates = [];
$tables = [];
$files = [];
foreach ($a as $v) {
    if (strstr($v, ".inprogress")) {
        continue;
    }
    if (!preg_match("/pouetdatadump-(.*)-(.*).json/", $v, $m)) {
        continue;
    }
    $tables[$m[1]] = true;
    $dates[$m[2]] = true;
    $files[$m[1] . $m[2]] = $v;
}

$output = ["dumps" => [], "latest" => null];
foreach ($dates as $date => $_true) {
    foreach ($tables as $table => $_true2) {
        $filename = @$files[ $table . $date ]; // "pouetdatadump-".$k2."-".$k.".json.gz";
        if ($filename && file_exists($filename)) {
            $output["dumps"][$date][$table]["filename"] = basename($filename);
            $output["dumps"][$date][$table]["url"] = "https://data.pouet.net/" . $filename;
            $output["dumps"][$date][$table]["size_in_bytes"] = filesize($filename);
        }
    }
}
if ($output["dumps"]) {
    arsort($output["dumps"]);
    $output["latest"] = reset($output["dumps"]);
} else {
    $output["dumps"] = new stdClass(); // {} like a non-empty list, not []
}

header("Content-type: application/json; charset=utf-8");
echo json_encode($output, JSON_PRETTY_PRINT);
