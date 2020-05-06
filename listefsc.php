<?php
session_start();
//require_once('basefsc.php');
//$_SESSION['kno']="knobuntu";
$req=$_POST['str'];

//$sond='{"unmassif":["massif","saison"]}';

$result=json_decode($req);
//var_dump($result);
$massif=$result->{"unmassif"}[0];
$saison=$result->{"unmassif"}[1];
//$saison="2017-2018";
//$massif="ecrins";


$repertoire="../../images/cryoland/cartes3D/".$saison;

$fichiers=scandir($repertoire);
//print_r( $fichiers);
$fichiersmassifshtml=[];
foreach($fichiers as $fichier){
	
	if(( strpos($fichier,'FSC')===false ) or(strpos($fichier,$massif)===false) or (strpos($fichier,'html')===false) ){
		//nest pas le fichier
	} else{		
		$fichiersmassifshtml[]=$repertoire.'/'.$fichier;
	}
		
	
}
//encode le tableau
$sortie=json_encode($fichiersmassifshtml);
 
echo ($sortie);
//$fsc=new fsc();
//$fichiers=$fsc->listefichiers('tinee','2017-2018');
//echo($fichiers);
?>
