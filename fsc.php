<?php
session_start();
//$get recoit les infos de qgis2trheejs  par ajax 
$orientation=$_GET['str'];

$orientation=urldecode($orientation);
//$objsond=json_decode($sond);
$split=explode("#",$orientation);
if(count($split)>1){
	$orientation="#".$split[1];
}


$file = fopen("fsc.txt","w");
fwrite($file,$orientation);
fclose($file);
$_SESSION['orientation']=$orientation;

echo ($orientation);

?>
