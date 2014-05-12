<?php



/****************************************************************/

/********************** sygConfig.php ***************************/

/****************************************************************/

/* Description: Intialisation des variables de configuration	*/

/* 		PHP						*/

/*  								*/

/* fonctions:							*/

/*  								*/

/****************************************************************/

$DEBUG_FLAG = 0; //      = 1 ==> trace mode ON; = 0 ==> trace mode OFF           //      jpg/20100111

// Récupération des informations sur le PHP


//phpinfo(INFO_VARIABLES);


function http_cookie_vars($var_http_cookie)

{

	// calcul la longueur

	$lg_var = strlen($var_http_cookie); 

	$pos = strrpos($var_http_cookie, "/");

  if ($pos+1 == $lg_var) { // 

    //  trouvé

	$newphrase = str_replace("CIL/", "", $var_http_cookie);

  }else{

	$newphrase = str_replace("sygMain.php", "", $var_http_cookie);

  }

	return $newphrase;

}  // fin de la  function verif_car_spe_gedi

//



// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>   Apache environnement >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//  	Variable  global        	//                                 Value                                                          

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>             

// DOCUMENT_ROOT                :     /home/nguyen6/aww/las43310/apache/8888/htdocs

// SCRIPT_NAME			:    /sygafe_V2.06/sygMain.php

// SCRIPT_FILENAME              :     /home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe_V2.06/sygMain.php

// HTTP_HOST			:   las43310.ln.cit.alcatel.fr:8888

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  Fin Apache environnement >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>   PHP Variables  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//  	    Variable  global           	//                                 Value                            

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>                         

//  HTTP_GET_VARS["op"]      	      :      RAS

// HTTP_COOKIE_VARS["Langue"]  :      EN

// HTTP_COOKIE_VARS["CONTOR"] :   OFF

// HTTP_COOKIE_VARS["URL_BASE"] : http://las43310.ln.cit.alcatel.fr:8888/sygafe_V2.06/CIL/

// HTTP_COOKIE_VARS["COUNTER"]  : OFF

// HTTP_COOKIE_VARS["Login"] : jacob nguyentrituo

// HTTP_COOKIE_VARS["Cil"] : Jacob NGUYENTRITUO

// ********************************************************HTTP_COOKIE_VARS["PASSWD"] : jacob 7452

// HTTP_COOKIE_VARS["Cip"] : Corporate Intranet Password 

// HTTP_COOKIE_VARS["Csl"] : Corporate system login 

// HTTP_SERVER_VARS["DOCUMENT_ROOT"] : /home/nguyen6/aww/las43310/apache/8888/htdocs

// HTTP_SERVER_VARS["HTTP_HOST"] : las43310.ln.cit.alcatel.fr:8888

// HTTP_SERVER_VARS["REQUEST_URI"] : /sygafe_V2.06/sygMain.php?op=RAS

// HTTP_SERVER_VARS["SCRIPT_NAME"] : /sygafe_V2.06/sygMain.php

// HTTP_SERVER_VARS["PATH_TRANSLATED"] : /home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe_V2.06/sygMain.php

// HTTP_ENV_VARS["PWD"] : /home/nguyen6/aww/las43310/apache/8888

// HTTP_ENV_VARS["STDHOME"] : /agl/share/std/current

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>     Exemple   >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><

//   Récupération du login,  passaword et nom d'utilisateur :

$cil = $HTTP_COOKIE_VARS[Login];
$passwd = $HTTP_COOKIE_VARS[Cip];
$csl=$HTTP_COOKIE_VARS[Csl];

/*
if(($csl=="")or ($csl != "nguyen6")){
			echo("<BR><BR>\n");
			echo("<CENTER><FONT COLOR=\"#ff0000\" SIZE=\"5\">\n");
			if ($csl==""){
          $nom_util="?????";
      }
      else{
          $nom_util=$csl;
          $csl="";
          setcookie ("Csl", $csl);
      }
      if ($langue_browser == "FR"){
				       echo("<B>Erreur !! csl=$nom_util valeur incorrecte ou vous n'avez pas l'autorisation d'accès.</B><BR>\n");
				       echo("<B>Veuillez contacter avec l'administrateur.</B><BR><BR>\n");
			}else{
				      echo("<B>Error !! csl=$nom_util incorrect value or you do not have the access authorization. </B><BR>\n");
				      echo("<B>Please contact with the manager.</B><BR><BR>\n");
			} 

			echo("</FONT>\n");
			echo("</CENTER>\n");
			echo("<HR><BR>\n");				
	}
	
	
if($passwd==""){

			echo("<BR><BR>\n");
			echo("<CENTER><FONT COLOR=\"#ff0000\" SIZE=\"5\">\n");
        $motdepass="?????";       
			if ($langue_browser == "FR")
				{
				echo("<B>Erreur !! Cip=$motdepass valeur incorrecte.</B><BR>\n");
				echo("<B>Veuillez contacter avec l'administrateur.</B><BR><BR>\n");
			}else{
				echo("<B>Error !! Cip=$motdepass incorrect value. </B><BR>\n");
				echo("<B>Please contact with the manager.</B><BR><BR>\n");
			}
			echo("</FONT>\n");
			echo("</CENTER>\n");
			echo("<HR><BR>\n");				
	}
if($cil==""){
			echo("<BR><BR>\n");
			echo("<CENTER><FONT COLOR=\"#ff0000\" SIZE=\"5\">\n");
        $nom_login="?????";       
			if ($langue_browser == "FR")
				{
				echo("<B>Erreur !! cil=$nom_login valeur incorrecte.</B><BR>\n");
				echo("<B>Veuillez contacter avec l'administrateur.</B><BR><BR>\n");
			}else{
				echo("<B>Error !! cil=$nom_login incorrect value. </B><BR>\n");
				echo("<B>Please contact with the manager.</B><BR><BR>\n");
			}
			echo("</FONT>\n");
			echo("</CENTER>\n");
			echo("<HR><BR>\n");				
	}


*/
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  Fin PHP Variables >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//url de SYGAFE par la variable global


	//Récupération des variables globals

	// la variable global du chemin racine de sygafe

	$racine_Sygafe = $HTTP_SERVER_VARS[PATH_TRANSLATED];//=/home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe_V2.06/sygMain.php 

	//




//constituer l'adresse du host

$url_name_port= $HTTP_SERVER_VARS[HTTP_HOST];//las40374.ln.cit.alcatel.fr:8283

//constituer l'adresse de lancement du sygafe

$script_name= $HTTP_SERVER_VARS[SCRIPT_NAME];	//sygafe/sygMain.php

$script_name=http_cookie_vars($script_name);	//sygafe/

$urlSygafe = "http://".$url_name_port.$script_name;

$urlRacineSyg = "http://".$url_name_port.$script_name;

$racineSyg =http_cookie_vars($racine_Sygafe);

$racineSygTemp = $racineSyg ;

// fin info

//nom du répertoire permettant le stockage des répertoires utilisateurs

//Ce répertoire a pour racine le répertoire $racineSyg

$nameSygTemp = "sygTemp";
//url du serveur web ASTRID pour accéder aux fichiers d'aide en ligne et au fichier liste_cr.xml  
$urlASTRID = "http://astrid2.ln.cit.alcatel.fr/ASTRID/st/doc/SYGAFE/";

//reference du fichier d'entrée de SYGAFE listant les cahiers de recette génériques sous GEDI

$ref_CR = "3BW80806AAAAADAHB";

//reference du fichier du Correspondances entre les fiches recette et specifications 3DR

$ref_3DR = "3BW86766AAAAADAHB";

//nom du fichier d'entrée de SYGAFE listant les cahiers de recette génériques sous GEDI

$file_orig = "liste_cr";

// fichier du Correspondances entre les fiches recette et specifications 3DR

$file_3DR = "Correspondances_FR-3DR";

//***** version courant du SYGAFE

$SYGAFE_VERSION = "2.06.02";



//>>>>>>>>>>>>>>>>>>>>>>>  ajouter pour test 5_02_2008

//if(($csl=="")or ($csl != "nguyen6")){
if($csl==""){
			echo("<BR><BR>\n");
			echo("<CENTER><FONT COLOR=\"#ff0000\" SIZE=\"5\">\n");
			if ($csl==""){
          $nom_util="?????";
      }
      else{
          $nom_util=$csl;
          $csl="";
          setcookie ("Csl", $csl);
      }
      if ($langue_browser == "FR"){
				       echo("<B>Erreur !! csl=$nom_util valeur incorrecte ou vous n'avez pas l'autorisation d'accès.</B><BR>\n");
				       echo("<B>Veuillez contacter avec l'administrateur.</B><BR><BR>\n");
			}else{
				      echo("<B>Error !! csl=$nom_util incorrect value or you do not have the access authorization. </B><BR>\n");
				      echo("<B>Please contact with the manager.</B><BR><BR>\n");
			} 

			echo("</FONT>\n");
			echo("</CENTER>\n");
			echo("<HR><BR>\n");				
	}
	
	
if($passwd==""){

	//
	$dir_courant=getcwd(); // sauvegarde le directory courant
	chdir("$racineSygTemp$nameSygTemp/$csl");// se mettre dans le répertoire d'utilisateur
	
	$refValue="PASSWD";
	$ref_rech="";
	if ($fic = fopen ("$racineSygTemp$nameSygTemp/$csl/gedi.ini" , "r")) {
	
  //	echo " sygcConfig.lire_gediini ligne 286 : dir_courant=$dir_courant<br>";				//	jpg/20100111
	 
	   while (!feof ($fic)) 
		  {
			$buffer = fgets($fic, 4096);
			$tab_car_spe = explode( "=",$buffer);
			$nbcartab= count($tab_car_spe);
			$lgrefValue=strlen($refValue);
			if (strncmp($tab_car_spe[0], $refValue,$lgrefValue) == 0)
			{ 
				$ref_rech = rtrim($tab_car_spe[1]);
			}
		}
	fclose ($fic);	
   
	if ($DEBUG_FLAG) { echo date('h:i:s') . " " . __FILE__ . __LINE__ . " ref_rech = $ref_rech<br>";}	//      jpg/20100111
	
	} 

    if($ref_rech==""){

			echo("<BR><BR>\n");
			echo("<CENTER><FONT COLOR=\"#ff0000\" SIZE=\"5\">\n");
        $motdepass="?????";       
			if ($langue_browser == "FR")
				{
				echo("<B>Erreur !! Cip=$motdepass valeur incorrecte.</B><BR>\n");
				echo("<B>Veuillez contacter avec l'administrateur.</B><BR><BR>\n");
			}else{
				echo("<B>Error !! Cip=$motdepass incorrect value. </B><BR>\n");
				echo("<B>Please contact with the manager.</B><BR><BR>\n");
			}
			echo("</FONT>\n");
			echo("</CENTER>\n");
			echo("<HR><BR>\n");		
     } else{
        $passwd=$ref_rech;
     }
     
  chdir($dir_courant);// se remettre dans le répertoire d'initiale
      		
	}
if($cil==""){
			echo("<BR><BR>\n");
			echo("<CENTER><FONT COLOR=\"#ff0000\" SIZE=\"5\">\n");
        $nom_login="?????";       
			if ($langue_browser == "FR")
				{
				echo("<B>Erreur !! cil=$nom_login valeur incorrecte.</B><BR>\n");
				echo("<B>Veuillez contacter avec l'administrateur.</B><BR><BR>\n");
			}else{
				echo("<B>Error !! cil=$nom_login incorrect value. </B><BR>\n");
				echo("<B>Please contact with the manager.</B><BR><BR>\n");
			}
			echo("</FONT>\n");
			echo("</CENTER>\n");
			echo("<HR><BR>\n");				
	}

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  Fin PHP Variables >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>



?>

