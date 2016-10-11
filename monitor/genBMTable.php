<?php
header("Access-Control-Allow-Origin: *");
include("config/dbConfigBM.inc.php");
// read each BM's device config

// Shiming Yang, 2013,2014. University of Maryland, School of Medicine
date_default_timezone_set("New_York");
$curTime = time()*1000;

echo "<table id='bms' style='width:100%''><tr><td><div class='rcorners2'>";

$bm = "BM1";
include("config/devicesBM1.inc.php"); //BM1
$sql = "select device,time,ECG_1,ICP,status from vitalsignsbm where server='".$bm."'";
$queryResult = mysql_query($sql, $dbLinkBM3);
$BMstatus = genBM123Status($queryResult, $devicesBM1);
genOneBMGrid($bm, $BMstatus);
echo "</div></td><td><div class='rcorners2'>";

$bm = "BM2";
include("config/devicesBM2.inc.php"); //BM2
$sql = "select device,time,ECG_1,ICP,status from vitalsignsbm where server='".$bm."'";
$queryResult = mysql_query($sql, $dbLinkBM3);
$BMstatus = genBM123Status($queryResult, $devicesBM2);
genOneBMGrid($bm, $BMstatus);
echo "</div></td><td><div class='rcorners2'>";

$bm = "BM3";
include("config/devicesBM3.inc.php"); //BM3
$sql = "select device,time,ECG_1,ICP,status from vitalsignsbm where server='".$bm."'";
$queryResult = mysql_query($sql, $dbLinkBM3);
$BMstatus = genBM123Status($queryResult, $devicesBM3);
genOneBMGrid($bm, $BMstatus);
echo "</div></td></tr><tr><td><div class='rcorners2'>";


// parse the $queryResult and return
// parse BM1,2,3
function genBM123Status($qRes, $devices) {
    $curTime = time()*1000;
    $resultArray = array();
    $nrows = 1;
    foreach($devices as $key => $val) {
        $resultArray[$key] = array('class'=>"nulgrid",'info'=>"offline");
    }
    while($row = mysql_fetch_assoc($qRes)) {
        {
            $devTime = strtotime($row['time']) * 1000;
            $device = $row['device'];if ($device=='OR-12' || $device=="CCRU-15" || $device=="CCRU-16") continue;
            $status = $row['status'];
            $icp = $row['ICP'];
            $gap = abs($devTime - $curTime);
            if($gap<480000) {// within 8min gap: green/icp
                if($icp<0) {
                $resultArray[$row['device']]['class'] = 'ggrid';
                $resultArray[$row['device']]['info'] = "(".substr($status,0,1).") ".$row['ECG_1'];
            } else {
                $resultArray[$row['device']]['class'] = 'icpgrid';
                $resultArray[$row['device']]['info'] = "ICP ".$icp;
            }
            }
            elseif($gap>=480000 and $gap<3600000) {// 5min-1hr gap: yellow
                $resultArray[$row['device']]['class'] = 'ygrid';
                $resultArray[$row['device']]['info'] = formatMilliseconds($gap);
            }
            else{
                $resultArray[$row['device']]['class'] = 'rgrid';
                $resultArray[$row['device']]['info'] = formatMilliseconds($gap);
            }
        
        }
    }
    return $resultArray;
}


function formatMilliseconds($milliseconds) {
    $seconds = floor($milliseconds / 1000);
    $minutes = floor($seconds / 60);
    $hours = floor($minutes / 60);
    $days = floor($hours /24);
    //$milliseconds = $milliseconds % 1000;
    $seconds = $seconds % 60;
    $minutes = $minutes % 60;
    if ($days >0) {
        $format = '%ud:%uh';
        $hours = $hours - $days*24;
        $time = sprintf($format, $days, $hours);
    }
    elseif ($hours>0) {
    $format = '%uh:%um';
    $time = sprintf($format, $hours, $minutes);
    } else {
        $format = '%u min';
        $time = sprintf($format, $minutes);
    }
    return rtrim($time, '0');
}


function genOneBMGrid($bm, $status) {
    //echo var_dump($status);
    echo "<table class='bm' style='width:100%'>";
    $ngrid = count($status);
    $ncol = 11;
    $nrow = floor($ngrid/$ncol);
    $nremain = $ngrid - $ncol * $nrow;
    $devices = array_keys($status);//echo var_dump($devices);
    for ($i=0; $i<$nrow; $i++) {
        echo "<tr>";
        for ($j=0; $j<$ncol; $j++) {
            $idx = $i*$ncol+$j;
            $grid = $status[$devices[$idx]];
            $devID = $devices[$idx];//key($grid);
            $cellClass = substr($devID,0,2);
            $grid_class = $status[$devices[$idx]]['class'];
            $grid_info = $grid['info'];
            echo "<td><div class='".$grid_class."'>";
            echo "<font size='1px'>".$devID."<br>".$grid_info."</font></div></td>";
        }
        echo "</tr>";
    }
    if ($nremain>0) {
    for ($i=0;$i<$nremain;$i++) {
        $idx += 1;
        $grid = $status[$devices[$idx]];
            $devID = $devices[$idx];//key($grid);
            $grid_class = $status[$devices[$idx]]['class'];
            $grid_info = $grid['info'];
            echo "<td><div class='".$grid_class."'>";
            echo "<font size='1px'>".$devID."<br>".$grid_info."</font></div></td>";
    }
    for ($i=0; $i<($ncol-$nremain-1); $i++) {
        echo "<td></td>";
    }
    echo "<td title='This is a summary'><div class='igrid'>".$bm."<br><font size='1px'>".$ngrid."</font></div></td>";
    echo "</tr>";
}
    echo "</table>";
}

?>