<?php
session_start();
//$_SESSION[orientation]="#cx=0&cy=-63.02&cz=63.02";
$_SESSION[orientation]="";
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Enneigement des Alpes par massifs</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="description" content="Limite d'enneigement des massifs alpins. Continuit� du recouvrement de la neige selon l'exposition.Evolution du manteau neigeux au cours de lka saison. Repr�sentation sur carte dynamique 3D" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<style>
		.cacher{
			display:none;
		
		}
		
		 button:disabled,
		button[disabled]{
		border: 1px solid #999999;
		font-weight: bold;
		color: #f0ad4e;
		
		}     
	</style>
   
</head>

<body>
	

<div id='iframe0' class="cacher">
<iframe id="ifrm0" src="" width="100%" height=750 onload="charge()">
 
  
</iframe>
</div>

<div id='iframeprec' class='cacher' >

<iframe id="ifrmprec" src="" width="100%" height=750 onload="charge()">
  
</iframe>
  


</div>
<div id='iframesuiv' class="cacher" >

<iframe id="ifrmsuiv" src="" width="100%" height=750 onload="charge()">
  
  
</iframe>
  
</div>


    <div style="float:left;width:30%">
		<div  id='infoTrans'>infotrans</div>
	</div>
	<div style="float:left;width:60%">
		<button id="precedent" class="couleur" onclick="precedent()">precedent</button>
		<button id="suivant"class="couleur" onclick="suivant()">suivant</button>
		<button onclick="orientation(massif,saison)"> fige l'orientation</button>


		<div id= "datediv">
			<select id="dateselect">
				<option value="0" onclick="madate()">20170101 </option>
			</select>

		</div>
	</div>


<script>

</script>





<script>
 var nbcartes = 7;	//doit correspondre au nbr dadresses
 var curseur  = 1 ;
 var compteur = 0 ; 
 var carte=0;
 var massif;
 var saison;
 
 var recharge=false;
 var orientationcourante;

function madate() {
    var listedates=document.getElementById("dateselect")
    var optioncarte = listedates.selectedIndex;
	iframecourant=ifrmdiv[curseur];
	$(iframecourant).hide();
	if (optioncarte==0){
		//le cas de carte=0
		affichepremier();
	}else{
		//dessin et affichage de liframe courant.
		carte=optioncarte
		curseur=(carte+1)%3;
		ifrmelem=document.getElementById(ifrm[curseur]);
		ifrmelem.src=adresses[carte];
		iframecourant=ifrmdiv[curseur];
		$(iframecourant).removeClass("cacher");		
		$(iframecourant).show();	
		//carteprec
		ifrmelem=document.getElementById(ifrm[(curseur+3-1) %3]);
		ifrmelem.src=adresses[carte-1];
		
		//affectation des cartes  suiv
		if (carte < nbcartes-2) {
			ifrmsuiv=document.getElementById(ifrm[(curseur+1)%3]);
			iframesuiv.src=adresses[(carte+1)];
			
		}	
		
		$("#precedent").prop('disabled', true);
		$("#suivant").prop('disabled', true);
		
		charge();
		
		
	}
	document.getElementById("infoTrans").innerHTML="<h2> Vallee: " + datemassif[carte]["massif"]+ "  " +datemassif[carte]["date"] +"</h2>" ;


}

function getHtmlListedate(){
	listediv= 'Choisir une carte dans la liste : <select id="dateselect">';
	for (i=0;i<nbcartes;i++){
		listediv += '<option value= "'+ i +'" onclick="madate()">'+ datemassif[i]['date'] + '</option>';
	}
	listediv+= '</select>';
	return listediv;
}


function charge(){
		
		setTimeout(function(){
			verifiecarte();
		},500);
		
	//	alert ("charge");
	    
}


 var ifrmdiv=["#iframeprec","#iframe0","#iframesuiv"];
 var ifrm=["ifrmprec","ifrm0","ifrmsuiv"];
 
 var iframecourant=ifrm[curseur];
 
 var datemassif = [];
 
var adresses=[
"https://nivo06.knobuntu.fr/images/cryoland/cartes3D/2017-2018/vesubieFSC20171203.html#cx=0.2&cy=-28&cz=62",
"https://nivo06.knobuntu.fr/images/cryoland/cartes3D/2017-2018/vesubieFSC20171207.html#cx=0.2&cy=-28&cz=62",
"https://nivo06.knobuntu.fr/images/cryoland/cartes3D/2017-2018/vesubieFSC20171213.html#cx=0.2&cy=-28&cz=62",
"https://nivo06.knobuntu.fr/images/cryoland/cartes3D/vesubie20170220.html#cx=0.2&cy=-28&cz=62",
"https://nivo06.knobuntu.fr/images/cryoland/cartes3D/vesubie20170310.html#cx=0.2&cy=-28&cz=62",
"https://nivo06.knobuntu.fr/images/cryoland/cartes3D/vesubie20170317.html#cx=0.2&cy=-28&cz=62",
"https://nivo06.knobuntu.fr/images/cryoland/cartes3D/2017-2018/vesubieFSC20171203.html#cx=0.2&cy=-28&cz=62"
];



function extraitcartemassif(){
	//extrait les datezs et les massifs de l'adresse
	//forme https://nivo06.knobuntu.fr/images/cryoland/cartes3D/2017-2018/vesubieFSC20171203.html#cx=0.2&cy=-28&cz=62
	
	
	//n=adresses.length;
	//alert(n);
	for (i=0;i<adresses.length;i++){
		 var urlargument=[];
		// alert (adresses[i]);
		 var res =adresses[i].split("/");
		 var nbr=res.length;
		 var arg=res[nbr-1];    
		 var argu=arg.split("#");   
		
		 var eclat= argu[0].split("FSC");
		 var unjour=eclat[1].split('.');
		urlargument["massif"]=eclat[0];
		urlargument["date"]=unjour[0];
		datemassif.push(urlargument);
		
    }
  
  //  alert (datemassif[0]["date"] + datemassif[1]["date"]);
   
}



	
function urlparams(){
	//forme nivo06?massif=ecrin&saison=2017-2018
	var urlargument=[];
	uri=window.location.toString();
    var res = uri.split("?");
    
    var arg=res[1].split("&");
    var massif=arg[0].split("=");
    var saison=arg[1].split("=");
    urlargument['massif']=massif[1];
    urlargument['saison']=saison[1];
   
   return urlargument;
}


$( document ).ready(function() {

	urlargument=urlparams();
	massif= urlargument["massif"];
	saison= urlargument["saison"];
	
	lecturefichiers(massif,saison) ;
	
});





function precedent(){	
	$(ifrmdiv[curseur]).hide();
    carte=carte - 1 ;
    curseur=(curseur+3-1)%3;
    ifrmelem=document.getElementById(ifrm[curseur]);	
	$(ifrmdiv[curseur]).removeClass("cacher");	
	$(ifrmdiv[curseur]).show();
	$('#dateselect').val(carte);
	if ((carte > 1) || (carte <(nbcartes-1))){
		$("#precedent").prop('disabled', true);
		$("#suivant").prop('disabled', true);	
		ifrmprec=document.getElementById(ifrm[(curseur+3-1)%3]);	
		ifrmprec.src=adresses[carte-1];			
	}else{	
		verifiecarte();
	}
    document.getElementById("infoTrans").innerHTML="<h2> Vallee: " + datemassif[carte]["massif"]+ "  " +datemassif[carte]["date"] +"</h2>" ;
}



function suivant(){
	
	$(ifrmdiv[curseur]).hide();
    carte=carte+1;
    curseur=(curseur+1)%3;
    ifrmelem=document.getElementById(ifrm[curseur]);
	
	$(ifrmdiv[curseur]).removeClass("cacher");	
	$(ifrmdiv[curseur]).show();
	$('#dateselect').val(carte);
	if ((carte > 1) || (carte <(nbcartes-1))){
		
		$("#precedent").prop('disabled', true);
		$("#suivant").prop('disabled', true);		
		ifrmsuiv=document.getElementById(ifrm[(curseur+1)%3]);
		ifrmsuiv.src=adresses[carte+1];			
	}else{	
		verifiecarte();
	}
    document.getElementById("infoTrans").innerHTML="<h2> Vallee: " + datemassif[carte]["massif"]+ " " +datemassif[carte]["date"] +"</h2>" ;

}

function prechargecartes(){


	if (carte <(nbcartes-1-1)){
		var ifrmsuiv=document.getElementById(ifrm[(curseur+1)%3]);
		txt="curseur"+(curseur+1)%3 +" carte "+carte +adresses[(carte+1)];
		
		//alert (txt);
		ifrmsuiv.src=adresses[(carte+1)];
		ifrmsuiv.contentWindow.location.reload();
	}			
	if (carte >0){
		ifrmprec=document.getElementById(ifrm[(curseur+3-1)%3]);
		ifrmprec.src=adresses[(carte-1)];
		ifrmprec.contentWindow.location.reload();
	}					
		
	recharge=true;
	$("#precedent").prop('disabled', true);
	$("#suivant").prop('disabled', true);	



}





function verifiecarte(){
	
	$("#precedent").prop('disabled', false);	
	$("#suivant").prop('disabled', false);
	
	if (carte == 0 ){		
		$("#precedent").prop('disabled', true);
	}
	if (carte==nbcartes-1){	
		$("#suivant").prop('disabled', true);
	}	
	recharge=false;
}

function affichepremier()
{
	carte =0;
	curseur = 1;
	verifiecarte();
	ifrmelem=document.getElementById("ifrm0");
	ifrmelem.src=adresses[0];
	ifrmsuiv=document.getElementById("ifrmsuiv");
	ifrmsuiv.src=adresses[1];
	iframecourant=ifrmdiv[curseur];
 	$(iframecourant).removeClass("cacher");		
	$(iframecourant).show();	
       reponse="curseur:"+ curseur +" - carte : "+carte;
    document.getElementById("infoTrans").innerHTML="<h2> Vallee: " + datemassif[carte]["massif"]+ " " +datemassif[carte]["date"] +"</h2>" ;
}

function affichedernier()
{
	carte=nbcartes-1;
	curseur=(nbcartes)%3;
	verifiecarte();
    ifrmelem=document.getElementById(ifrm[curseur]);	
    ifrmelem.src=adresses[carte];		
	$(ifrmdiv[curseur]).removeClass("cacher");	
	$(ifrmdiv[curseur]).show();
    reponse="curseur:"+ curseur +" - carte : "+carte;
    document.getElementById("infoTrans").innerHTML="<h2> Vallee: " + datemassif[carte]["massif"]+ " " +datemassif[carte]["date"] +"</h2>" ;
    ifrmprec=document.getElementById(ifrm[(curseur+3-1)%3]);	
	ifrmprec.src=adresses[carte-1];		
	$('#dateselect').val(carte);
    if ((carte > 1) || (carte <(nbcartes-1))){
	}else{	
		verifiecarte();
	}

}



function lecturefichiers(unmassif,unesaison) {
           
         
      text='{"unmassif":["'+unmassif+'","'+unesaison+'"]}';
        //alert (text);
		
		var req= $.ajax({
		  url:"listefsc.php",
		  data:{'str':text},
		  type:"POST",
		  dataType:'json',
		  });
		  req.done( function (result) {	

				unfic=result[1];	
				nbcartes=result.length;
			//	$('#infoTrans').html(unfic);	
				adresses=result;
				extraitcartemassif();
				
				lectureorientation(unmassif,unesaison);
				document.getElementById('datediv').innerHTML=getHtmlListedate();
				affichedernier();
		});
		req.fail(function(jqXHR, textStatus) {
		 $('#infoTrans').html( "fail" );
		});



}






function orientation(unmassif,unesaison) {
	
    text='{"unmassif":["'+unmassif+'","'+unesaison+'"]}';

	var req= $.ajax({
		url:"lectureorientation.php",
		data:{'str':text},

		type:"POST",
		  dataType:'json',
		  });
		req.done( function (result) {	
				  
			oriente=result.orientation;	
		//	alert (result.orientation);
			//if (orientationcourante != oriente){
				for(i=0;i<adresses.length;i++){
					var str = adresses[i];
					n=str.search("#");
					if(n>-1){
						str= str.substring(0,n);
					}
					str+=oriente;	
					adresses[i]=str;	
					   
				}
				orientationcourante=oriente;
		//	}
		
			//$('#infoTrans').html(adresses[1]);	
			prechargecartes();

		});
		req.fail(function(jqXHR, textStatus) {
		$('#infoTrans').html( textStatus );
		});
		

		
		
}		


function lectureorientation(unmassif,unesaison){
	   text='{"unmassif":["'+unmassif+'","'+unesaison+'"]}';

	var req= $.ajax({
	url:"lectureorientation.php",
	data:{'str':text},

	type:"POST",
	 dataType:'json',
	 });
	req.done( function (result) {	
				  
		oriente=result.orientation;	
		//adresse doit deja etre calcul�
	//	if (orientationcourante!=oriente){
		orientationcourante= oriente;
			for(i=0;i<adresses.length;i++){
					var str = adresses[i];
					n=str.search("#");
					if(n>-1){
						str= str.substring(0,n);
					}
					str+=oriente;	
					adresses[i]=str;	
					   
			}
           affichedernier()
//		}
	});
	req.fail(function(jqXHR, textStatus) {
	$('#infoTrans').html( textStatus );
	});
		

	
}
</script>	
</body>

</html>
