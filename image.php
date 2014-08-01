<?php

require_once("includes/jpgraph-3.5.0b1/jpgraph.php");
require_once("includes/jpgraph-3.5.0b1/jpgraph_line.php");
require_once("includes/jpgraph-3.5.0b1/jpgraph_date.php");

function TimeCallback($input)
{
    return Date("H:i", $input);
}

if(isset($_GET["x"]))
    $image_x = (int) $_GET["x"];
else
    $image_x = 848;

if(isset($_GET["y"]))
    $image_y = (int) $_GET["y"];
else
    $image_y = 480;

if($image_x < 320)
    $image_x = 320;

if($image_x > 1920)
    $image_x = 1920;

if($image_y < 240)
    $image_y = 240;

if($image_y > 1080)
    $image_y = 1080;

if(isset($_GET["start"]))
    $interval_start = (int) $_GET["start"];
else
    $interval_start = 1376863200; // 2013-08-19 00:00:00

if(isset($_GET["stop"]))
    $interval_stop = (int) $_GET["stop"];
else
    $interval_stop = $interval_start + ((60*60*24) - 1);

if($interval_stop > ($interval_start + ((60*60*24) - 1)) || $interval_stop < $interval_start)
    $interval_stop = $interval_start + ((60*60*24) - 1);

$sql_query = "SELECT UNIX_TIMESTAMP(whenquery) AS whenquery, PAC FROM invdata WHERE PAC > 0 AND whenquery BETWEEN FROM_UNIXTIME(" . $interval_start . ") AND FROM_UNIXTIME(" . $interval_stop . ")";

$sql_connection = mysql_connect("127.0.0.1", "smlgr", "smlgr");
mysql_select_db("smlgr", $sql_connection);

$sql_result = mysql_query($sql_query, $sql_connection);

$data_y = array();
$data_x = array();

while($sql_row = mysql_fetch_assoc($sql_result)) {
    $data_y[] = (int) $sql_row["PAC"] / 2;
    $data_x[] = (int) $sql_row["whenquery"];
}

mysql_free_result($sql_result);
mysql_close($sql_connection);

if(count($data_y) > 0) {
    $graph = new Graph($image_x, $image_y);
    $graph->title->SetFont(FF_FONT2, FS_BOLD, 12);
    $graph->title->Set("Energy Production");
    $graph->subtitle->SetFont(FF_FONT1, FS_BOLD, 10);
    $graph->subtitle->Set("From " . strftime("%Y-%m-%d %H:%M:%S", $interval_start) . " to " . strftime("%Y-%m-%d %H:%M:%S", $interval_stop));
    $graph->subsubtitle->SetFont(FF_FONT1, FS_ITALIC, 9);
    $graph->subsubtitle->Set("Data only from " . strftime("%Y-%m-%d %H:%M:%S", $data_x[0]) . " to " . strftime("%Y-%m-%d %H:%M:%S", $data_x[count($data_x)-1]));
    $graph->SetScale("datint");
    $graph->SetFrame(true, "black", 1);
    $graph->SetTickDensity(TICKD_SPARSE);
    $graph->xaxis->scale->SetTimeAlign(HOURADJ_1);
    $graph->xaxis->SetLabelFormatCallback("TimeCallback");
    $graph->xaxis->SetLabelAngle(90);
    $graph->xaxis->SetFont(FF_FONT1, FS_ITALIC, 9);

    $lineplot = new LinePlot($data_y, $data_x);
    $lineplot->SetColor("blue");
    $lineplot->SetFillColor("lightblue@0.5");

    $graph->Add($lineplot);

    $graph->Stroke();
}

?>