<?php

/****************************************************************************************/
/********************************* sygCommon.php ****************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: définition de constantes et de variables communes aux fonctions de 	*/
/* l'application.		 							*/
/*  											*/
/* fonctions:										*/
/*  											*/
/****************************************************************************************/

/******************   Déclaration des variables globals *************************************/ 

//chemin relatif a la racine du répertoire utilisateur
$repUtil = "";

//ensemble des systèmes stockés dans le fichier listant les projets
$systemes = array();

//ensemble des systèmes retenus à savoir ceux vérifiant le critère de recherche
//rentré par l'utilisateur
$systemesRetenus = array();

//cahier de recette récupéré
$crRecupere;

//descripteur du fichier HTML pour le sommaire
$file_s;

//descripteur du fichier HTML pour le titre du cahier de recette
$file_t;

//descripteur du fichier HTML pour le contenu du cahier de recette
$file_c;

//chapitres de ce cahier de recette
$chapitres_tab = array();

//sous-chapitres d'un chapitre donné
$sousChap_tab = array();

//tableau des FR pour un chapitre ou un sous-chapitre
$fr_tab = array();

//tableau qui liste les langues disponibles pour un projet donné
$lguesDispo = array();

//booléen permettant de savoir si toutes les données sont désérialisées afin de procéder à
//la génération de la page HTML
$dataIsReady = 0;

//nombre de fiches sélectionnées max autoriés est 50
$nbfiche_select_max=250;
$nbfiche_select;
$nbfiche_total;
$refresh_ancre;



/**************************      Déclaration des fonctions communes           ******************************/
	
function verif_car_spe_gedi($passwd)
{
    $faute = False;
	$car_spe_interd = "' , ^ )";
	$tab_car_spe = explode( " ",$car_spe_interd);
	$nbcartab= count($tab_car_spe);
	$passwd_reconst=$passwd;
	for($i=0;($i<$nbcartab) & ($faute == False);$i++){
		if(strstr($passwd_reconst, $tab_car_spe[$i])){
			// imagine qu'on a plusieurs de car speciales, traitement à revoir
			$faute = True;
		}
	}
	return $faute;
}  // fin de la  function verif_car_spe_gedi
	
//Cette fonction permet de recréer le mot de passe au norme de la commande gediget 	
function relance_gediget($passwd)
{

	$car_spe_iden = "[ ! \" # $ % & * + , - . / ; < = > ? @ ^ _ ` ) | } ~ ]";
	$tab_car_spe = explode( " ",$car_spe_iden);
	$nbcartab= count($tab_car_spe);
	$car_spe="";
	//recherche des blancs dans le password
	$passwd_reconst=$passwd;
	//recherche des blancs dans le password
	$part_passwd = explode ( " ",$passwd_reconst);
	$lgpasswd=count($part_passwd);

	if ($lgpasswd > 1) {  
	// password contient au moin 1 blanc
      $passwd_reconst=implode("+",$part_passwd);
	}
	//recherche des anti_slash dans le password
	$part_passwd = explode ( "\\",$passwd_reconst);
	$lgpasswd=count($part_passwd);
	if ($lgpasswd > 1) {  
	// password contient au moin 1 slash
		$passwd_reconst=implode("\\\\",$part_passwd);
	}
	for($i=0;$i<$nbcartab;$i++){
		if(strstr($passwd_reconst, $tab_car_spe[$i])){
			// imagine qu'on a plusieurs de car speciales, traitement à revoir
			$part_passwd = explode ( $tab_car_spe[$i],$passwd_reconst);
			$antislash="\\";
			$car_spe = "$antislash$tab_car_spe[$i]";
			$passwd_reconst=implode($car_spe,$part_passwd);
			//echo " password en traitements des car special : $passwd_reconst <br>";
				
		}
	}
	$passwd_reconst = "\"$passwd_reconst\"";
	return $passwd_reconst;
} // fin de la function relance_gediget

//cette fonction permet de savoir si un element est present dans un tableau
//$type vaut "entier" ou "chaine" 
//function isPresent($tab, $elt, $type="entier"){
function isPresent($tab, $elt, $type){
    $nbelemtab = count($tab);
										// NGJ trace
									$edition_etat = "sygcommon.isPresent => ligne 126 ";
	               $string1000 = "nb element = $nbelemtab ,elt = $elt, type = $type\n";
	                erori ($edition_etat,$string1000);
									//
	for($i = 0; $i < count($tab); $i++) {

		/*
		if (strcmp($type,"entier")==0) {
			if ($tab[$i] == $elt) return 1; //true
		} elseif (strcmp($type,"chaine")==0) {
			if (strcmp($tab[$i],$elt) == 0) return 1; //true
		} else {
			die(sprintf("probleme valeur parametre type = chaine ou entier !"));
		}
		*/
		
		
		if (strncmp($type,"entier",6)==0) {
			if ($tab[$i] == $elt) return 1; //true
		} elseif (strncmp($type,"chaine",6)==0) {
		  $lg_elt=strlen($elt);
			if (strncmp($tab[$i],$elt,$lg_elt) == 0) return 1; //true
		} else {
			die(sprintf("probleme valeur parametre type = chaine ou entier !"));
		}
	}
	return 0;
} // fin de la function isPresent


//Cette fonction supprime les répertoires utilisateurs à interval régulier d'une semaine et le lundi
//à la première exécution de SYGAFE
//Si le fichier lastReset.syg est absent on le crée. Ce fichier contient la date du dernier
//effacement des répertoires utilisateurs. Cette date est en seconde et correspond au timestamp
//Unix. On réinitialise la valeur stockée dans le fichier à chaque effacement des répertoires soit
//toutes les 604800 sec (7*24*60*60) et que le jour courant soit le lundi 
function deleteRepUtil()
{
//version 2.06
	global $racineSyg,$racineSygTemp;
	global $nameSygTemp, $file_orig, $file_3DR, $ref_CR, $ref_3DR;
	global $file_count_date; //JNG ajout le 26/10/2006
	$xml = ".xml";
	$xzip = ".xzip";
	$trouve = 0;

		//on recherche le fichier lastReset.syg, s'il n'existe pas on le crée.
	$files_root = list_dir($racineSygTemp.$nameSygTemp."/");
	foreach($files_root as $file) {
		if (ereg("lastReset.syg", $file)) {
				$trouve = 1;
		}
		//en mode debug recherche le fichier erori et le supprimer
		if (ereg("erori", $file)) {
				system("rm -fR $racineSyg$nameSygTemp/$file");
		}
	}
	$currentTime = mktime(date("h"), date("i"), date("s"), date("m"), date("d"), date("Y"));

	if ($trouve == 0) {
		if (!$file_reset = fopen($racineSygTemp.$nameSygTemp."/lastReset.syg","w")) {
		 	die("Impossible de créer le fichier de réinitialisation lastReset.syg");
		} else {
		 	$fs = serialize($currentTime);	
		 	fputs($file_reset, $fs);
		 	fclose($file_reset);
		}
	}	
	$jourdelasemaine=date("l");
	//$jourdelasemaine="Monday";

	if ($jourdelasemaine == "Monday") {
		if (!$file_reset = fopen($racineSygTemp.$nameSygTemp."/lastReset.syg","r")) {
			 die("Impossible de lire le fichier de réinitialisation lastReset.syg");
		} else { 		
			$var = fgets($file_reset, 4096);	
			$lastTime = unserialize($var);
			fclose($file_reset);
		}
		$expiration = $lastTime + (7*24*60*60);
		if ($currentTime > $expiration){
			if (!$file_reset = fopen($racineSygTemp.$nameSygTemp."/lastReset.syg","w")) {
			 	die("Impossible de créer le fichier de réinitialisation lastReset.syg");
			} else {
			 	$fs = serialize($currentTime);	
			 	fputs($file_reset, $fs);
			 	fclose($file_reset);
			}
		}else{  //on efface tout ce que contient sygTemp sauf le fichier lastReset.syg
			$filesRep = list_dir($racineSygTemp.$nameSygTemp);
			foreach($filesRep as $file) {
				if (!ereg("lastReset.syg", $file) and !ereg("counter", $file))
					system("rm -fR $racineSygTemp$nameSygTemp/$file");
			}
				//on remplace les fichiers listeCR et Correspondances_FR-3DR.xml avec les eventuelles nouvelles version GEDI
			system("rm  $racineSygTemp$nameSygTemp/Correspondances_FR-3DR.xml liste_cr.xml");
			
			 //	file_gedi($ref_CR, $file_orig.$xzip); 
			 //	file_gedi($ref_3DR, $file_3DR.$xzip);
				
			$files = list_dir("$racineSygTemp$nameSygTemp/");
			foreach ($files as $file){
				$chDirOk = chdir("$racineSygTemp$nameSygTemp");			 	
				if (ereg("$ref_CR",$file)){
				 	$nomreferenceCR = $file;
				 	system("cp  $nomreferenceCR $file_orig.$xml");
				 	system("rm  $nomreferenceCR ");
				}
				 if (ereg("$ref_3DR",$file)){
				 	$nomreference3DR = $file;
				 	system("cp  $nomreference3DR $file_3DR.$xml");
				 	system("rm  $nomreference3DR ");
				}
			}
		}
	}			
}  //  fin de la function deleteRepUtil


//cette fonction lit les fichiers (mais pas les répertoires) contenu dans un répertoire et renvoie son contenu dans un tableau
function list_dir($dirname)
{
	$result_array = array();
		
	$handle=opendir($dirname);
	while ($file = readdir($handle))
	{
		if($file=='.'||$file=='..')
			continue;
		$result_array[]=$file;
	}	
	closedir($handle);
	return $result_array;
} // fin de la function list_dir


//cette fonction apporte un fichier xml du gedi 
function file_gedi($reference, $filename)
{
	global $identificationCIl, $racineSygTemp,$racineSyg, $nameSygTemp, $file_orig, $file_3DR, $ref_CR, $ref_3DR;
	global $motdepasse,$login_user,$passwd,$repUtil;
	
	$lgue = "FR";
	$nomFileXzip = "";

	$dir_courant = getcwd();
	
	$chDirOk = chdir("$racineSygTemp$nameSygTemp");

	if ( $chDirOk == false) {
		die("pb chdir script sygMainStep1!!");
	} else {
	

//  ancienne code 
		$identificationCIl = "";
		system("gediget ".$reference." ".$lgue." $identificationCIl -save $filename", $resCde);	
			
		if ($DEBUG_FLAG) { echo date('h:i:s') . " " . __FILE__ . __LINE__ . " retour première  d'appel gediget <br>";}
		if ($DEBUG_FLAG) { echo date('h:i:s') . " " . __FILE__ . __LINE__ . " ref=$reference $lgue  Id=$identificationCIl -save file=$filename, code retour = $resCde<br>";}

		if ($resCde != 0) {
			$motdepasse = relance_gediget($passwd);
			$identificationCIl = "-cn $login_user -pass $motdepasse";
			//$identificationCIl = "";
			system("gediget ".$reference." ".$lgue." $identificationCIl -save $filename > $racineSygTemp$nameSygTemp/resCdes", $resCde);
			
		} // fin  if ($resCde != 0)
			//on analyse le résultat de la commande afin de savoir si
	//c'est un pb se serveur FTP Gedi ou que le document n'est pas dans
	//la base
		if ($resCde != 0) {
		
				// formatage du message d'erreur selon le code de retour du gediget
				//$file_resCdes = "$racineSygTemp$nameSygTemp/resCdes";
				$string1000 = edit_mess_gedi ("$racineSygTemp$nameSygTemp/resCdes",$reference,$identificationCIl,$lgue);
		
			echo("<BR><BR>\n");
			echo("<FONT COLOR=\"#000000\" SIZE=\"5\">\n");
			/*
			if ($langue_browser == "FR")
				{
				echo("<B>Erreur : $reference document inexistant ou problème avec le serveur FTP de GEDI</B><BR><BR>\n");
			}else{
				echo("<B>Error : $reference document not found or GEDI FTP server problem</B><BR><BR>\n");
			}
			*/
			echo("<B>Error : $string1000 </B><BR><BR>\n");
			echo("</FONT>\n"); 	
		}else{
		//on récupère le nom du fichier xzip (un seul) pour le dézipper
		//$files = list_dir("$racineSyg$nameSygTemp/");
		//NGJ 04_07_07
			$files = list_dir("$racineSygTemp$nameSygTemp/");		
		//
			foreach ($files as $file){
				if (ereg(".xzip$",$file)){
					$nomFileXzip = $file;
					if ($DEBUG_FLAG) { echo date('h:i:s') . " " . __FILE__ . __LINE__ . " : retour première  d'appel gediget = OK <br>";}
					if ($DEBUG_FLAG) { echo date('h:i:s') . " " . __FILE__ . __LINE__ . " : recherche du fichier nomFileXzip=$nomFileXzip<br>";}
				}
			}
		}
	}  //fin if ( $chDirOk != 0)
		//unzip du fichier
	if($nomFileXzip != ""){
		system("unzip $racineSygTemp$nameSygTemp/$nomFileXzip  >> $racineSygTemp$nameSygTemp/resCdes");
		system("rm  $racineSygTemp$nameSygTemp/resCdes");
		system("rm -f $racineSygTemp$nameSygTemp/$nomFileXzip");
	}else{
		echo("<BR><BR>\n");
		echo("<FONT COLOR=\"#000000\" SIZE=\"5\">\n");
		
		if ($langue_browser == "FR")
		{
			echo("<B>Erreur : Problème de la création du document : $reference ==> $filename</B><BR><BR>\n");
		}else{
			echo("<B>Error : Bad creation of the document:  $reference ==> $filename</B><BR><BR>\n");
		}
		echo("</FONT>\n"); 	
	}
		if ($DEBUG_FLAG) { echo date('h:i:s') . " " . __FILE__ . __LINE__ . " : fin de la function file_gedi <br>";}
		if ($DEBUG_FLAG) { echo date('h:i:s') . " " . __FILE__ . __LINE__ . " : recherche du fichier nomFileXzip=$nomFileXzip<br>";}
}	// fin de la function file_gedi

// fonction utilise dans le cas des absence des fichiers liste_cr.xml ou Correspondances_FR-3DR.xml
function ficpres($file_s)
{
	global $file_orig, $file_3DR, $racineSyg, $racineSygTemp,$nameSygTemp, $ref_CR, $ref_3DR;
	$xml = ".xml";
	$xzip = ".xzip";
	
	$chDirOk = chdir("$racineSygTemp$nameSygTemp");
	$files = list_dir("$racineSygTemp$nameSygTemp/");
	foreach ($files as $file) 
	{
		if (ereg("$file_s",$file)){
			return 1;
		}		
	}
	$nrchar = strlen ($file_s);
	$file_s = substr($file_s,0,$nrchar - 4);
	if (strcmp($file_s, $file_orig) == 0) 
		{
			file_gedi($ref_CR, $file_s.$xzip);
		}
	elseif (strcmp($file_s, $file_3DR) == 0)
		{
			file_gedi($ref_3DR, $file_s.$xzip);
		}
	$files = list_dir("$racineSygTemp$nameSygTemp/");
		$chDirOk = chdir("$racineSygTemp$nameSygTemp");
			 foreach ($files as $file) 
			 	{
				 				 	
				 	if (ereg("$ref_CR",$file)) 
				 		{
				 		$nomreferenceCR = $file;
						if ($DEBUG_FLAG) { echo date('h:i:s') . " " . __FILE__ . __LINE__ . " : recherche du fichier nomreferenceCR=$nomreferenceCR <br>";}
		    			if ($DEBUG_FLAG) { echo date('h:i:s') . " " . __FILE__ . __LINE__ . " : copier $nomreferenceCR ==> $file_orig$xml <br>";}
				 		system("cp  $nomreferenceCR $file_orig$xml");
				 		system("rm  $nomreferenceCR ");
				 		}
				 	if (ereg("$ref_3DR",$file)) 
				 		{
				 		$nomreference3DR = $file;
				 		system("cp  $nomreference3DR $file_3DR$xml");
				 		system("rm  $nomreference3DR ");
				 		}
				 				
			 	}	
}

// fonction debug
function erori ($local_appel,$val)
{	
	global $cil,$csl;
	global $racineSygTemp, $nameSygTemp;
	$erori = 0;
	$linie ="   \n";
	$data=($val);
	$fic = fopen ("$racineSygTemp$nameSygTemp/$csl/erori" , "a+");
	fwrite($fic, $csl." :: ".$local_appel."\n".$data."\n");
	fclose ($fic);
}

//fonction permettre de créer le fichier serialisation 
function cre_all_serialiser_file_syg ($path_Value,$filename_syg,$val)
{	
  /*
	$titre_orig="";
	$titre_orig = str_replace("\n", " ", $val);
	$lgtitre=strlen($titre_orig);
	$titre_orig[$lgtitre]="\n";
  */
	//$data=$filename_syg."\n".$titre_orig."\n";  // ecrire le nom du fichier syg dans le fichier
  //$data=$titre_orig."\n";  // ecrire sans le nom du fichier syg dans le fichier
  $data=$filename_syg."\n".$val."\n";  // ecrire avec le nom du fichier syg dans le fichier
	$fic = fopen ($path_Value , "a+");
	fwrite($fic,$data);
	fclose ($fic);
	
}
//fin fonction

//fonction permettre de déserialisation 
//function file_deserialiser_sygafe($file_path,$file_name)
//{




//}
//fin fonction
//**********************************************
function cre_gediInit($path_Value)
{
global $cil,$passwd;

	if (!$file_ini = fopen($path_Value,"w"))
	{  //JNG ajouter pour test
	 	die("Can not create gedi.ini file.");
	}   //JNG ajouter pour test
	else 
	{				
		//Ngj 13_06_07 remettre le blanc par le +
		$part_passwd = explode ( " ",$passwd);
		$lgpasswd=count($part_passwd);
		if ($lgpasswd > 1) {  
			// password contient au moin 1 blanc
			$passwd=implode("+",$part_passwd);
		}
	 	fwrite($file_ini, "PROXY_IP=\n");
	 	fwrite($file_ini, "PROXY_PORT=\n");
	 	fwrite($file_ini, "GEDI_IP=gedi.ln.cit.alcatel.fr\n");
	 	fwrite($file_ini, "CONFIG_FILE=configparam.xml\n");
	 	fwrite($file_ini, "CN=$cil\n");
	 	fwrite($file_ini, "PASSWD=$passwd\n");
	 	fwrite($file_ini, "FTP_IP=\n");
	 	fwrite($file_ini, "FTP_USER=\n");
	 	fwrite($file_ini, "FTP_PASSWD=\n");
	 	fwrite($file_ini, "FTP_SUBDIR=\n");
		fclose ($file_ini);
		
	}

}// fin fonction


/******************************************************************************/
//fonction lecture du fichier gedi.init pour récupérer le login et le password
//parametre en entrer peut avoir les valeurs : 
//$refValue=GEDI_IP =>  gedi.ln.cit.alcatel.fr l'adresse du IP de GEDI 
//$refValue=CONFIG_FILE => configparam.xml
//$refValue=CN => login de connexion du GEDI
//$refValue=PASSWD => jacob+7452
//**********************************************
function lire_gediInit($refValue)
{
	global $cil,$csl;
	global $racineSygTemp, $nameSygTemp;
	$file_gediinit="$racineSygTemp$nameSygTemp/$csl/gedi.ini";
	
	//echo " sygcommon.lire_gediinit dans directory d'utilisateur : $file_gediinit <br>";
	
	
	if (file_exists($file_gediinit)) {
    //print "Le fichier $file_gediinit existe";
	} else {
    //print "Le fichier $file_gediinit n'existe pas : création du fichier gedi.init";
		cre_gediInit($file_gediinit);
	}

	//****************************
	if (!$fic = fopen ("$racineSygTemp$nameSygTemp/$csl/gedi.ini" , "r")) {
			 die("Impossible de lire le fichier gedi.ini : fichier inexistant");
	} else {
	//*****************************
	
	//$fic = fopen ("$racineSygTemp$nameSygTemp/$csl/gedi.ini" , "r");
	$ref_rech=0;
	while (!feof ($fic)) 
		{
			$buffer = fgets($fic, 4096);
			$tab_car_spe = explode( "=",$buffer);
			$nbcartab= count($tab_car_spe);
			//$CipValue="PASSWD";
			$lgrefValue=strlen($refValue);
			if (strncmp($tab_car_spe[0], $refValue,$lgrefValue) == 0)
			{ 
				//$ref_rech = $tab_car_spe[1];
				$ref_rech = rtrim($tab_car_spe[1]);

			}
		}
	//}
	fclose ($fic);
	return $ref_rech;
	}
}// fin fonction


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><>>
// This function read a specific line from a file
// To read a line from the begining of the file use positive integer
// To read a line frem the end of the file use negative integer
//   Examples : ReadLine("text.txt",1) returns the first line
//              ReadLine("text.txt",-1) returns the last line
//              ReadLine("text.txt",-3) returns the third line from the end of
//                                      the file
function fReadLine($Filealire,$LineNo)
  {

	if (!$fhandle = fopen ($Filealire , "r")) {
			 die("Impossible de lire le fichier $Filealire : fichier inexistant");
	} else {
  //$fhandle = fopen($Filealire,"r");                  // Open the file
  $cChar = "";                                  // Init the current Character
  if ($LineNo > 0){
    $cLine = 1;                                 // Init line counter
    $pos = 0;                                   // Init file position counter
    while (!feof($fhandle) && $cLine < $LineNo) // Loop while EOF and not requested line
      {
      if (!fseek($fhandle,$pos,SEEK_SET))       //Seek to the next char
        {
        $cChar = fgetc($fhandle);               // Get the char at the pointer position
        }
      if ($cChar == "\n")                       // Condition to increment line counter
        {
        $cLine++;                               // Increment line by 1
        }
      $pos++;                                   // Increment position by 1
      }
    }
  
  return fgets($fhandle, 4096);                     // Returns the line content.
  fclose($fhandle); 
     }                       // Close the file
  }
//***************************************************************************
function fnblinefile($Filealire)
  {
  $cLine=0;
  if (!$fhandle = fopen ($Filealire , "r")) {
			 die("Impossible de lire le fichier $Filealire : fichier inexistant");
	} else {
  
  //$fhandle = fopen($Filealire,"r");                  // Open the file
  $cChar = "";                                  // Init the current Character
// *** Read Line from the begining of the file ***

    $cLine = 1;                                 // Init line counter
    $pos = 0;                                   // Init file position counter
    while (!feof($fhandle)) // Loop while EOF and not requested line
      {
      if (!fseek($fhandle,$pos,SEEK_SET))       //Seek to the next char
        {
        $cChar = fgetc($fhandle);               // Get the char at the pointer position
        }
      if ($cChar == "\n")                       // Condition to increment line counter
        {
        $cLine++;                               // Increment line by 1
        }
      $pos++;                                   // Increment position by 1
      }

  fclose($fhandle);                           // Close the file
  
  //  $edition_etat = "sygCommon.fnblinefile => ligne 622 : lecture dans le fichier :".$Filealire;
	//$string1000 ="nombre de lignes trouvées N°: ".$cLine."\n";
	//erori ($edition_etat,$string1000);
  }
  return $cLine;
  }


//***************************************************************************
//***************************************************************************
// fonction recherche et remplace les caractères spécials
//***************************************************************************
function rempl_car_spe ($val)
{	
	$tab_car_spe = explode( "é",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&eacute;",$tab_car_spe);
	}
		$tab_car_spe = explode( "è",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&egrave;",$tab_car_spe);
	}
		$tab_car_spe = explode( "à",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&agrave;",$tab_car_spe);
	}
		$tab_car_spe = explode( "&",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&amp;",$tab_car_spe);
	}
		$tab_car_spe = explode( "ê",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&ecirc;",$tab_car_spe);
	}
		$tab_car_spe = explode( "ô",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&ocirc;",$tab_car_spe);
	}
		$tab_car_spe = explode( "î",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&icirc;",$tab_car_spe);
	}
		$tab_car_spe = explode( "û",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&ucirc;",$tab_car_spe);
	}
		$tab_car_spe = explode( "â",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&acirc;",$tab_car_spe);
	}
		$tab_car_spe = explode( "§",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&sect;",$tab_car_spe);
	}
		$tab_car_spe = explode( "<",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&lt;",$tab_car_spe);
	}
		$tab_car_spe = explode( "ç",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&ccedil;",$tab_car_spe);
	}
		$tab_car_spe = explode( "ù",$val);
	$nbcartab= count($tab_car_spe);
	if ($nbcartab > 1) {  
      $val=implode("&ugrave;",$tab_car_spe);
	}
	return $val;
}  // fin de la function rempl_car_spe



/**************************************************************************/
// fonction edition message d'erreur du retour de GEDI
//*************************************************************************
function edit_mess_gedi ($file_resCdes,$reference,$identification,$langue_browser)
{	
//*************************************************************************
//$file_resCdes  : chemin complet du fichier faute
//$reference     : reference du fichier traité 
//$identification: identifiant de l'operateur
//$langue_browser: langue à éditer
//*************************************************************************
	$num_err=0;
	
		//	echo " sygCommon edit_mess_gedi =>ligne 408 : debut <br>";
		//	echo " ref=$reference , Id=$identificationCIl , file_resCdes = $file_resCdes<br>";
	
	// il faut aller lire dans le fichier resCdes pour récupérer le numéro de faute
	//****************************
	if (!$fic = fopen ($file_resCdes , "r")) {
			 die("Impossible de lire le fichier $file_resCdes : fichier inexistant");
	} else {
	//*****************************

	$ref_rech=0;
	$i=0;
	while (!feof ($fic)) 
		{
			$buffer = fgets($fic, 4096);
			$element_tab = explode( ":",$buffer);//recherche le car
			$nbcartab= count($element_tab); // mettre les élément de la ligne en tableau
			//echo " sygCommon edit_mess_gedi =>ligne 424: buffer ligne $i => $buffer <br>";
			if ($nbcartab > 1) {
				//for ($j=0;$j < $nbcartab;$j++){
				//echo "$j =  $element_tab[$j]<br>";
				//}
				$num_err =  $element_tab[0];
				$string10 =  $element_tab[1];
			}
			$i=$i+1;
		}
	}
	fclose ($fic);
	
	if ($langue_browser == "FR")
	{
				if ($num_err == 1){
					$string10 =  " Document non trouvé : $reference <br>";
				}elseif ($num_err == 2){
					$string10 =  " Document indisponible (pas de pdf, document déjà extrait…): $reference  <br>";
				}elseif ($num_err == 3){
					$string10 =  " Identification incorrect : $identification<br>";
				}elseif ($num_err == 4){
					$string10 =  " Erreur lors de l’enregistrement avec l’option save. <br>";
				}elseif ($num_err == 5){
					$string10 =  " Le serveur ne répond pas, Contacter l’équipe support GEDI. <br>";
				}elseif ($num_err == 6){
					$string10 =  " Autres erreurs <br>";
				}elseif ($num_err == 7){
					$string10 =  " Pas assez de données (erreur avec l’option save) <br>";
				}elseif ($num_err == 8){
					$string10 =  " Erreur dans le lancement du programme <br>";
				}elseif ($num_err == 9){
					$string10 =  " Le fichier de configuration gedi.ini n’existe pas <br>";
				}elseif ($num_err == 10){
					$string10 =  " Hôte (serveur) inconnu. <br>";
				}elseif ($num_err == 11){
					$string10 =  " Il manque un argument dans la ligne de commande <br>";
				}elseif ($num_err == 12){
					$string10 =  " Le serveur a renvoyé une réponse XML invalide <br>";
				}elseif ($num_err == 13){
					$string10 =  " La requête n’a pas marché <br>";
				}elseif ($num_err == 14){
					$string10 =  " La consignation a échoué <br>";
				}elseif ($num_err == 15){
					$string10 =  " Problème de configuration du ftp (identifiant, adresse IP) <br>";
				}elseif ($num_err == 16){
					$string10 = " Problème pendant la transmission de données <br>";
				}elseif ($num_err == 17){
					$string10 =  " Fichier de configuration non trouvé vérifiez que le fichier configparam.xml<br>";
				}elseif ($num_err == 18){
					$string10 =  " Document déjà présent dans la base mais non extrait. Vous devez extraire ce document pour le consigner<br>";
				}elseif ($num_err == 19){
					$string10 =  " Le PDF du document est déjà dans la base de données<br>";
				}elseif ($num_err == 20){
					$string10 =  " Le document est à l'état valide dans la base<br>";
				}elseif ($num_err == 21){
					$string10 =  " Erreur lors d'un gediget avec l'option undo : le document n'est pas extrait.<br>";
				}elseif ($num_err == 22){
					$string10 =  "Le fichier csv n'existe pas ou est incorrect.<br>";
				}elseif ($num_err == 23){
					$string10 =  " Le serveur JDBC ne répond pas ou a un problème.<br>";
				}
	}else{
				if ($num_err == 1){
					$string10 =  " $reference document is not in the base <br>";
				}elseif ($num_err == 2){
					$string10 =  " There is no PDF available <br>";
				}elseif ($num_err == 3){
					$string10 =  " Incorrect identification : $identification<br>";
				}elseif ($num_err == 4){
					$string10 =  " Error saving file <br>";
				}elseif ($num_err == 5){
					$string10 =  " Server not responding <br>";
				}elseif ($num_err == 6){
					$string10 =  " Error other <br>";
				}elseif ($num_err == 7){
					$string10 =  " Insuffisant data. Operation cancelled. <br>";
				}elseif ($num_err == 8){
					$string10 =  " Run error <br>";
				}elseif ($num_err == 9){
					$string10 =  " File gedi.ini does not exist. <br>";
				}elseif ($num_err == 10){
					$string10 =  " Unknown host <br>";
				}elseif ($num_err == 11){
					$string10 =  " Incorrect or missing attributes <br>";
				}elseif ($num_err == 12){
					$string10 =  " The server has send an invalid response <br>";
				}elseif ($num_err == 13){
					$string10 =  " Problem during query <br>";
				}elseif ($num_err == 14){
					$string10 =  " Problem during job status checking <br>";
				}elseif ($num_err == 15){
					$string10 =  " You must be identified for connection ftp. <br>";
				}elseif ($num_err == 16){
					$string10 = " Problem during data transmission <br>";
				}elseif ($num_err == 17){
					$string10 =  " Configuration file not found <br>";
				}elseif ($num_err == 18){
					$string10 =  " A document present in the base, already has the reference $reference. You should already extract .. <br>";
				}elseif ($num_err == 19){
					$string10 =  " PDF already in database <br>";
				}elseif ($num_err == 20){
					$string10 =  " Edition number incorrect <br>";
				}elseif ($num_err == 21){
					$string10 =  " This document was not extracted <br>";
				}elseif ($num_err == 22){
					$string10 =  " File csv does not exist or file csv incorrect <br>";
				}elseif ($num_err == 23){
					$string10 =  " JDBC server does not respond or problem on JDBC server <br>";
				}
	}
			
			
			return $string10;
}
//**********************************************
//**********************************************
function cre_CR_doc($path_Value,$ref_Value,$ed_value,$languewzip_value)
{
global $cil,$passwd;

    $tab_explode = explode( ".",$languewzip_value);//ref_ed_lng.wzip
	    $nb_tab= count($tab_explode);
	    if ($nb_tab > 1){
	    $langue_value=$tab_explode[0];
      }

  $nomfilexml=$path_Value.$ref_Value."_".$ed_value."_".$langue_value."."."xml"; //ref_ed.lg.xml
	if (!$file_ini = fopen($nomfilexml,"w"))
	{  //JNG ajouter pour test
	 	die("Can not create CR_word file.");
	}   //JNG ajouter pour test
	else 
	{			
  	$resCde = 0;
    //appel à gediget pour récupérer les infos nécessaires le titre et la status
   // $identificationCIl = "";
		//system("gediget ".$ref_Value." ".$langue_value." $identificationCIl -title -status > $path_Value.gediget_res", $resCde);	
    
    if ($resCde == 0) {

      /*  A remettre en service avec les nouvelles commandes gedi
      $title_status_edition_ref = get_mess_gedi($path_Value.gediget_res);
      $element_tab = explode( "><",$title_status_edition_ref);//recherche le car :
			$nbcartab= count($element_tab); // mettre les élément de la ligne en tableau
			//echo " sygCommon edit_mess_gedi =>ligne 424: buffer ligne $i => $buffer <br>";
			if ($nbcartab > 1) {
			   $title_ref=$element_tab[0];
			   $status_ref=$element_tab[1];
			   $ed_value=$element_tab[2];
      }
      
      echo " sygCommon cre_CR_doc =>ligne 710 : title_ref=$title_ref, status_ref=$status_ref, ed_value=$ed_value<br>";
      */
    
      $title_ref="Catalogue of the ngHLR R3.2 Acceptance tests sheets collection - FRANCE - ";
      $title_ref="Out of catalog. Manually inserted";
      $status_ref="RL";
      $prefix_ref=$ref_Value[0].$ref_Value[1].$ref_Value[2];
      $number_ref=$ref_Value[3].$ref_Value[4].$ref_Value[5].$ref_Value[6].$ref_Value[7];
      $variant_ref=$ref_Value[8].$ref_Value[9];
      $version_ref=$ref_Value[10].$ref_Value[11];
      $documenttype_ref=$ref_Value[12].$ref_Value[13];
      $zz_ref=$ref_Value[14].$ref_Value[15];
      $language_ref=$ref_Value[16];
    
	 	  fwrite($file_ini, "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	 	  fwrite($file_ini, "<!DOCTYPE genericdoc PUBLIC \"-//ALCATEL//GENERICDOC 2.0//EN\" \"genericdoc.dtd\">\n");
	 	  fwrite($file_ini, "<!--ArborText, Inc., 1988-2001, v.4002-->\n");
	 	  fwrite($file_ini, "<?Pub EntList alpha bull copy rArr sect trade Omega?>\n");
	 	  fwrite($file_ini, "<?Pub Inc?>\n");
	 	  fwrite($file_ini, "<genericdoc template=\"other\" lang=\"$langue_value\"><docstatus>\n");
	 	  fwrite($file_ini, "<refdoc codif=\"new\"><prefix>$prefix_ref</prefix><number>$number_ref</number><variant>$variant_ref</variant>\n");
	 	  fwrite($file_ini, "<version>$version_ref</version><documenttype>$documenttype_ref</documenttype><zz>$zz_ref</zz><language><?Pub Caret\?>$language_ref</language></refdoc>\n");
	 	  fwrite($file_ini, "<titledoc><title1>$title_ref</title1></titledoc>\n");
	 	  fwrite($file_ini, "<heading><content></content></heading>\n");
	 	  fwrite($file_ini, "<subheading><content></content></subheading>\n");
	 	  fwrite($file_ini, "<mnemonicdoc></mnemonicdoc>\n");
	 	  fwrite($file_ini, "<ednum>$ed_value</ednum>\n");
	 	  fwrite($file_ini, "<status statuscode=\"$status_ref\"/>\n");
	 	  fwrite($file_ini, "<confidentiality cf=\"no\"/>\n");
	 	  fwrite($file_ini, "<lastmodifdate><year>2007</year><month>10</month><day>18</day></lastmodifdate>\n");
	 	  fwrite($file_ini, "<descript><descriptitem></descriptitem></descript>\n");
	 	  fwrite($file_ini, "<typedoc>ACCEPTANCE BOOK</typedoc>\n");
	 	  fwrite($file_ini, "</docstatus>\n");
	 	  fwrite($file_ini, "<specific><other><systemtitlehdl/><systemcodehdl/><doctitlehdl/><typedochdl/><statushdl/><mnemonichdl/><copyrighthdl/></other></specific>\n");
	 	  fwrite($file_ini, "<doccontent>\n");
	 	  fwrite($file_ini, "<toc/>\n");
	 	  fwrite($file_ini, "<docbody></docbody>\n");
	 	  fwrite($file_ini, "</doccontent>\n");
	 	  fwrite($file_ini, "</genericdoc>\n");
	 	  fwrite($file_ini, "<?Pub *0000069712\?>\n");
		  fclose ($file_ini);
	  }else $nomfilexml = "";
	}
 return $nomfilexml;
}// fin fonction

//***************************************************************************
function aff_temp_ecoule($t_debut)
{

  $top0 = gettimeofday();

   if($t_debut != 0){
    if ($t_debut == $top0["sec"])
      $t_ecoule = 1;
    else
      $t_ecoule = $top0["sec"]-$t_debut;
   }else
     $t_ecoule = 0;
       
  //
  if($t_ecoule>60){
    $t_ecoule_minutes=$t_ecoule/60;
    $tab_explode = explode( ".",$t_ecoule_minutes);//ref_ed_lng.wzip
	    $nb_tab= count($tab_explode);
	    $t_ecoule_minutes=$tab_explode[0];
    $t_ecoule_secondes=$t_ecoule%60;
    $string1000 = "Temps écoulé = ".$t_ecoule_minutes." minute(s) ".$t_ecoule_secondes." seconde(s)\n";
  }else{
    $string1000 = "Temps écoulé = ".$t_ecoule." seconde(s)\n";
  }
	$edition_etat = "\n sygcommon.aff_temp_ecoule ligne 898 : $t_ecoule seconde(s) ";
	
	erori ($edition_etat,$string1000);
	//     
	return $top0["sec"];
}
//**************************************************************************

/**************************************************************************/
// fonction recupérer les infos de retour de GEDI
/*************************************************************************/
function get_mess_gedi ($file_resCdes)
{	
	
	if (!$fic_ref = fopen ($file_resCdes , "r")) {
			 die("Impossible de lire le fichier $file_resCdes : fichier inexistant");
	} else {
	
	while (!feof ($fic_ref)) 
		{
			$buffer = fgets($fic_ref, 4096);
			$element_tab = explode( ":",$buffer);//recherche le car :
			$nbcartab= count($element_tab); // mettre les élément de la ligne en tableau
			//echo " sygCommon edit_mess_gedi =>ligne 424: buffer ligne $i => $buffer <br>";
			if ($nbcartab > 1) {
			
			  $element_tab_2 = explode( " ",$element_tab[0]);//recherche le car blanc
        $nbcartab_2= count($element_tab_2);
        if ($nbcartab_2 > 1) {
        
    //  echo " sygCommon get_mess_gedi =>ligne 766 : title_ref=$title_ref, element_tab_2 = $element_tab_2[2] <br>";

            if("Title" == $element_tab_2[2]){
              $title_ref =  $element_tab[1];
            }
            if("Status" == $element_tab_2[2]){
              if(($element_tab[1] == "AVAILABLE")or($element_tab[1] == "VALID")){
              }
              $status_ref = "RL"; //AVAILABLE, VALID
            }
            if("Edition" == $element_tab_2[2]){
              $edition_ref =  $element_tab[1];
            }
        }
			}
		}
		$string10 = $title_ref."><".$status_ref."><".$edition_ref;
	}
	fclose ($fic_ref);
	
	return $string10;
}
//**********************************************

//**********************************************
//--------------------------------------------------------------------------------------------------------------------------
//
//
//--------------------------------------------------------------------------------------------------------------------------


	$csl=$HTTP_COOKIE_VARS[Csl];
	$passwd = $HTTP_COOKIE_VARS[Cip];
	$cil = $HTTP_COOKIE_VARS[Login];
	
	$repUtil = $csl;
	$login_user = "\"$cil\"";
		
//	echo " sygCommon ligne 953 : csl=$HTTP_COOKIE_VARS[Csl]; passwd = $HTTP_COOKIE_VARS[Cip];cil = $HTTP_COOKIE_VARS[Login];<br>";
	
	$motdepasse = relance_gediget($passwd);
	$login_user = "\"$cil\"";
	
	list ($nameCil, $surnameCil)= explode ( " ",$cil);
		
	//erreur LDAP
	$cile = $cil;
	$ok = "NOK";
	for ($u = 0; $u < strlen($cil); $u++)
	{
		if ($cil[$u] == " " and $ok == "NOK")
		{
			for($p = $u+1; $p < strlen($cil); $p++)
			{
				$ok == "OK";
				if ($cil[$p] == "_") $cil[$p] = " ";
					
			}	
		}
	}
		
	// initialisation fichier gedi.ini
	global $gedinipath;
	$gedinipath = "$racineSyg$nameSygTemp/$csl";
	putenv("GEDINIPATH=$gedinipath");   
	$identificationCIl = "";
	$matrixx = array();
	$mat = array();
	$mats;
//*****



?>
