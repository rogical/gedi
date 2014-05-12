<?php

/****************************************************************************************/
/********************************* sygMain.php ******************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: fichier principal de l'application - il repr�sente un fichier d'index 	*/
/*  											*/
/* fonctions:										*/
/*  											*/
/****************************************************************************************/


// identification CIL deja effectuee ??
	if ($HTTP_COOKIE_VARS[USERPASS] == "OK")
    {

	if (($op == INIT) || ($op == RAS) || ($op == RS) || ($op == RP) || ($op == EF) || ($op == CR) || ($op == DLT) || ($op == LIR) || ($op == DCX) || ($op == RCNX) || ($op == NCNX)) {
		
		//Enclenche la bufferisation de sortie
		ob_start();  


		/* sygConfig.php : Intialisation des variables de configuration	*/
		require("common/sygConfig.php");
		
		/* sygCommon.php : d�finition de constantes et de variables communes aux fonctions de 	*/
		/* l'application.		 							*/
		require("common/sygCommon.php");
		
//	jpg/20100108	add a special call to delete the liste_cr.xml
		if ( $op == RAS and isset($HTTP_GET_VARS[raz]) and $HTTP_GET_VARS[raz] == yes )
		{	echo "rm $racineSygTemp$nameSygTemp/Correspondances_FR-3DR.xml<br>";
			echo "rm $racineSygTemp$nameSygTemp/liste_cr.xml<br>";
			system ("rm $racineSygTemp$nameSygTemp/Correspondances_FR-3DR.xml");
			system ("rm $racineSygTemp$nameSygTemp/liste_cr.xml");
			echo date('h:i:s') . " " . __FILE__ . __LINE__ . " liste_cr.xml file is deleted<br>";
			die;
		}

		/* sygListProClasses.php : ce fichier d�finit l'ensemble des classes con�ues pour structurer et	*/
		/* d�finir les comportements des objets �labor�s � partir des donn�es du fichier	*/
		/* liste_cr.xml 									*/
		require("classes/sygListProClasses.php");
		
		/* fonctionsStep1.php : ce fichier fournit les fonctions permettant la cr�ation des composants	*/
		/* de la page principale								*/
		require("step1/fonctionsStep1.php");
		
		/* sygReadFileP.php : lecture du fichier en gestion sous GEDI qui liste les projets et leurs 	*/
		/* cahiers de recette.								 	*/
		require("step1/sygReadFileP.php");
		
		/* sygMainStep1.php : construction du formulaire et mise � jour dynamique des listes de	*/
		/* valeur pour l'obtention d'un cahier de recette g�n�rique d'un projet.		*/
		require("step1/sygMainStep1.php");
		
		if ($op != CR)
			ob_end_flush(); //Envoie les donn�es du buffer de sortie, et �teint la bufferisation de sortie 
			
		
	} elseif (($op == prt) || ($op == stp2) || ($op == rfsh) || ($op == ctg) || ($op == ctgY) || ($op == ctgN) || ($op == mkctg) || ($op == cons) || ($op == afi) || ($op == scf) || ($op == sscf) || ($op == ajfc) || ($op == ajfsc) || ($op == com) || ($op == arch) || ($op == newpv) || ($op == pvcs)) {		
		
		/*trace ngj*/
	  //echo " SygMain ligne 57 avec op = $op  <br>";

		//Enclenche la bufferisation de sortie
		ob_start();  

		/* sygConfig.php : Intialisation des variables de configuration	*/
		require("common/sygConfig.php");
		
		/* sygCommon.php : d�finition de constantes et de variables communes aux fonctions de 	*/
		/* l'application.	*/
		require("common/sygCommon.php");
		
		/* sygFileCrClasses.php : ce fichier d�finit l'ensemble des classes con�ues pour structurer et	*/
		/* d�finir les comportements des objets �labor�s � partir des donn�es d'un cahier de recette*/
		require("classes/sygFileCrClasses.php");
		
		/* fonctionsStep2.php : ce fichier fournit les fonctions n�cessaires � la mise en oeuvre des	*/
		/* fonctionnalit�s de l'�tape 2 � savoir l'affchage du cahier de recette et la 		*/
		/* construction du catalogue final							*/	
		require("step2/fonctionsStep2.php");
		
		/* sygReadFileCr.php : lecture du fichier en gestion sous GEDI repr�sentant le cahier de  	*/
		/* recette. Ce fichier liste l'ensemble des fiches de recette d'un projet structur�es	*/
		/* en chapitres et sous-chapitres							*/
		require("step2/sygReadFileCr.php");
		
		/* sygMainStep2.php : construction du formulaire et avec affichage du CR et construction du	*/
		/* catalogue 										*/
		require("step2/sygMainStep2.php");
		
		if($op != end){ 
		
			ob_end_flush(); //Envoie les donn�es du buffer de sortie, et �teint la bufferisation de sortie 
		}
	} else {
		
		//erreur
				//JNG trace
			//echo " SygMain ligne erreur avec op = $op  <br>";
	}
}
	else
	
	// identification demande
	echo "<script language=\"javascript\">window.location.href=\"./CIL/\"</script>";


?>
