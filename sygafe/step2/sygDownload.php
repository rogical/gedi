<?php

/****************************************************************************************/
/********************************* sygDownload.php **************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: fichier permettant le t�l�chargement du cahier de recette customis� et 	*/
/* du PV - fichier sp�cifique pour �viter les blancs parasites d�es aux require		*/
/* fonctions:										*/
/*  											*/
/****************************************************************************************/
	
	//bug IE pour le download d'un fichier : correctif
	//IE veut enregistrer la page html courante et non le fichier
	if (strstr($HTTP_USER_AGENT, "MSIE")) {
		$attachment = "";
		$mimeType = "application/ms-x-download";
	}else {
		$attachment = " attachment;";
		$mimeType = "application/octet-stream";
	}
		
	header("Content-type: $mimeType");
	header("Content-Disposition: $attachment filename=$nameFile");
		
	header("Content-Description: Fichier XZIP"); 
			
	header("Content-Transfer-Encoding: binary");
		
	$ctg = fopen("$rep/step2/$nameFile","r");
			
	$contents = fread($ctg,filesize("$rep/step2/$nameFile"));
	echo($contents);
	fclose($ctg);
	
	exit();
?>
