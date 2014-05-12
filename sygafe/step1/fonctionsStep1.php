<?php

/****************************************************************************************/
/******************************** fonctionsStep1.php ************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: ce fichier fournit les fonctions permettant la création des composants	*/
/* de la page principale								*/
/*											*/
/* fonctions:										*/
/*  											*/
/****************************************************************************************/


$csl=$HTTP_COOKIE_VARS[Csl];
$passwd = $HTTP_COOKIE_VARS[Cip];
$cil = $HTTP_COOKIE_VARS[Login];

//echo " fonctionsStep1 ligne 15 : csl=$HTTP_COOKIE_VARS[Csl];passwd = $HTTP_COOKIE_VARS[Cip];cil = $HTTP_COOKIE_VARS[Login];<br>";



//Création du répertoire de stockage sygTemp s'il n'existe pas
function createSygTemp()
{
	global $racineSyg;
	global $nameSygTemp,$racineSygTemp;
	
	$trouve = 0;

	$files_root = list_dir($racineSygTemp);
	foreach($files_root as $file) {
		if (ereg($nameSygTemp, $file)) {
			$trouve = 1;
		}
	}
	
	if ($trouve == 0) {
		system("mkdir $racineSygTemp$nameSygTemp");
		system("chmod 777 $racineSygTemp$nameSygTemp");
		
	//
	//$edition_etat = "fonctionsStep1.createSygTemp => ligne 38 : création du répertoire SygTemp";
	//$string1000 = " mkdir $racineSygTemp$nameSygTemp";
	//erori ($edition_etat,$string1000);
	//
	}
}

//formulaire de démarrage - on demandera à l'utilisateur de saisir un identifiant. Ainsi un
//répertoire de nom cet identifiant sera créé temporairement pour stocker les fichiers
function logForm()
{
	global $urlASTRID, $urlSygafe;
	global $racineSyg,$racineSygTemp;
	global $nameSygTemp;
	global $langue_browser;
	
	echo("<TITLE> SYGAFE </TITLE>\n");
	echo("</HEAD>\n");
//	echo("<BODY BGCOLOR=\"#FFFFES\" BACKGROUND=\"images/fond.jpg\" onLoad=\"begin()\">\n");
	echo("<BODY BGCOLOR=\"#FFFFES\" BACKGROUND=\"images/fond.jpg\" onLoad=\"logSygafe1_js()\">\n");
	echo("<FORM method=\"post\" action=\"sygMain.php?op=RAS\" name=\"logForm\">\n");
	echo "<script language=\"javascript\">window.location.href=\"".$urlSygafe."sygMain.php?op=RAS\"</script>";
	
	//champ caché pour mémoriser le path du répertoire de stockage
	//echo("<INPUT TYPE=\"hidden\" name=\"racine\" value=\"".$racineSyg.$nameSygTemp"\">\n");
	
	
	if ($langue_browser == "FR") {
	//création du lien vers le fichier A propos de Sygafe
		echo("<TR><TD width=30% align=left><FONT COLOR=\"#000088\" SIZE=\"3\">\n");
		echo("<A HREF=".$urlSygafe."A_propos.htm TARGET=\"_blank\"><IMG SRC=\"images/paper.gif\" name=\"aboutImg\" width=\"89\" height=\"89\" border=\"0\" align=\"bottom\"></A>A propos de SYGAFE V2.0\n");
		echo("</FONT></TD>\n");
	
	//création du lien vers le manuel d'utilisateur de SYGAFE

		echo("<TD width=45% align=center><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
		echo("<A HREF=".$urlSygafe."MANUEL_UTILISATION.pdf TARGET=\"_blank\" onmouseOver=\"helpOver_js()\" onmouseOut=\"helpOut_js()\"><IMG SRC=\"".$urlSygafe."images/help.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
		echo("</FONT></TD>\n");
    }else{
		echo("<TR><TD width=30% align=left><FONT COLOR=\"#000088\" SIZE=\"3\">\n");
		echo("<A HREF=".$urlSygafe."About.htm TARGET=\"_blank\"><IMG SRC=\"images/paper.gif\" name=\"aboutImg\" width=\"89\" height=\"89\" border=\"0\" align=\"bottom\"></A>A propos de SYGAFE V2.0\n");
		echo("</FONT></TD>\n");
	
	//création du lien vers le manuel d'utilisateur de SYGAFE

		echo("<TD width=45% align=center><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
		echo("<A HREF=".$urlSygafe."USER_MANUAL.pdf TARGET=\"_blank\" onmouseOver=\"helpOver_js()\" onmouseOut=\"helpOut_js()\"><IMG SRC=\"".$urlSygafe."images/help.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
		echo("</FONT></TD>\n");
	}
	//image pour le mail
	echo("<TD width=25% align=right><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	echo("<A HREF=\"mailto:jean-francois.treyssat@alcatel.fr\"><IMG SRC=\"images/mail.jpg\" name=\"mailImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	echo("</FONT></TD></TR>\n");
/**/
	echo("</TABLE>\n");
	echo("</FORM>\n");

	deleteRepUtil();
	
	echo("</BODY>\n");
	echo("</HTML>\n");
}


//cette fonction crée l'entête de la page principale
//elle crée également le formulaire
function entetePage1()
{
	global $urlASTRID;
	global $racineSyg, $repUtil;
	global $nameSygTemp, $racineSygTemp;
	global $sys_search;
	global $langue_browser;
	global $op;
	
	if ($langue_browser == "FR")
		{
			$string1="Constituer un cahier de recette à partir d'un cahier générique";	
			$string2="Nom du système :";
			$string3="Systèmes reconnus :";
			$string4="Rechercher";
		}
	else
		{
			$string1="Create an acceptance book from a generic one";
			$string2="System name:";
			$string3="Recognised system:";
			$string4="Search";
		}
		
	//creation de l'entete de la page principale
	
	echo("<TITLE> SYGAFE </TITLE>\n");	
	echo("</HEAD>\n");
	echo("<BODY BGCOLOR=\"#FFFFES\" BACKGROUND=\"images/fond.jpg\">\n");
	echo("<TABLE width=100%>\n");
	
	//image SYGAFE = A propos
	echo("<TR><TD width=40% align=left><FONT COLOR=\"#000088\" SIZE=\"3\">\n");
/*	
	echo("<A HREF=".$urlSygafe."A_propos.htm TARGET=\"_blank\"><IMG SRC=\"images/paper.gif\" name=\"aboutImg\" width=\"89\" height=\"89\" border=\"0\"></A>\n");
*/	
	
//	echo("<A HREF=".$urlASTRID."aide/aideSygafe.html TARGET=\"_blank\" onmouseOver=\"helpOver_js()\" onmouseOut=\"helpOut_js()\"><IMG SRC=\"images/help.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");	
	if ($langue_browser == "EN"){ 
		echo("<A HREF=".$urlSygafe."USER_MANUAL.pdf TARGET=\"_blank\" onmouseOver=\"helpOver_js()\" onmouseOut=\"helpOut_js()\"><IMG SRC=\"".$urlSygafe."images/help.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	}else{
		echo("<A HREF=".$urlSygafe."MANUEL_UTILISATION.pdf TARGET=\"_blank\" onmouseOver=\"helpOver_js()\" onmouseOut=\"helpOut_js()\"><IMG SRC=\"".$urlSygafe."images/help.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	}
	echo("</FONT></TD>\n");
	
	//Titre
	echo("<TD width=60% align=left><FONT COLOR=\"#0000FF\" SIZE=\"7\">\n");
	
	echo("<B>SYGAFE<B>\n");
	echo("<TD width=75% align=left><FONT COLOR=\"#0000FF\" SIZE=\"7\">\n");
	
	
	if ($op == "RAS")
	{
	
		// Permettre de changer de la langue d'affichage dans la page
		if ($langue_browser == "EN")
			{ 
				echo("<A HREF=".$urlSygafe."sygMain.php?op=DCX&home=1&langue_browser=FR TARGET=\"_self\"><IMG SRC=\"images/Francais.png\" name=\"aboutImgz\" width=\"83\" height=\"34 \" border=\"0\"></A>\n");
			}
		else
			{ 
				echo("<A HREF=".$urlSygafe."sygMain.php?op=DCX&home=1&langue_browser=EN TARGET=\"_self\"><IMG SRC=\"images/English.png\" name=\"aboutImgz\" width=\"83\" height=\"34 \" border=\"0\"></A>\n");
			}
	}

	echo("</FONT></TD></TR>\n");

	echo("</TABLE>\n");
	
	//construction du formulaire
	
	echo("<FORM method=\"post\" action=\"sygMain.php\" name=\"firstPage\">\n");
		
	//champ caché pour mémoriser le chemin du répertoire utilisateur
	echo("<INPUT TYPE=\"hidden\" name=\"rep\" value=\"".$repUtil."\">\n");

	echo("<CENTER>\n");
	echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
	echo("<B>$string1</B><BR><BR>\n");
	
		
	echo("</FONT>\n");
	echo("</CENTER><BR>\n");
	
	echo("<TABLE CELLPADDING=5>\n");
/*		jpg/20100111	BEGIN
	// Nom du système :
	echo("<TR><TD COLSPAN=2><FONT COLOR=\"#0000F\" SIZE=\"4\">$string2</FONT></TD>\n");
	
	//on affiche le critère de recherche
	//de toute façon s'il n'a pas été précisé il vaut la chaine nulle
	echo("<TD><INPUT type=\"text\" size=\"40\" name=\"sys_search\" value=\"$sys_search\"></TD>\n");
	// rechercher
	echo("<TD><INPUT type=button name=crGen value=\"$string4\" onClick=afficheSys_js()></TD></TR>\n");
*/	//	jpg/20100111	END
}

//cette function permet l'affichage de la zone de saisie et du bouton pour
//accéder directement à un catalogue - cette partie se trouve à la suite de la partie
//permettant une recherche pas à pas d'un CR générique
function accesDirect()
{
	global $langue_browser;
	global $op, $traitement_mode;
	//NGJ 08_01_2007
	global $crRecupere;
	//fin
	if ($langue_browser == "FR")
		{
			$string1="Modifier un cahier de recette existant et/ou Constituer le Procès Verbal du cahier de recette";
			$string2="Référence :";
			$string3="Obtenir le cahier de recette";
			$string4="Constituer le Procès Verbal du cahier de recette";
			$string5="Fonction en restriction     ";
		}
	else
		{
			//$string1="Modify an acceptance book from an existing one";
			$string1="Modify an existing acceptance book and/or Generate an acceptance form";
			$string2="Reference:";
			$string3="Obtain an acceptance book";
			$string4="Generate an acceptance form";
			$string5="Restricted function    ";
		}	
	echo("<BR><HR><BR>\n");
	
	echo("<CENTER>\n");
	echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
	echo("<B>$string1</B><BR>\n"); //Modifier un cahier de recette existant
	echo("</FONT>\n");
	echo("</CENTER><BR>\n");
	
	echo("<TABLE CELLPADDING=5>\n");
	
	//Référence d'un cahier de recette générique ou customisé
	echo("<TR><TD COLSPAN=2><FONT COLOR=\"#0000F\" SIZE=\"4\">$string2</FONT></TD>\n");//reference
	echo("<TD><INPUT TYPE=\"text\" maxLength=\"17\" SIZE=\"40\" NAME=\"ref_cr\" VALUE=\"\"></TD>\n");
	echo("<TD><INPUT type=button name=crCusto value=\"$string3\" onClick=recupererCR_js()></TD>\n");//obtain an acceptance book
	// fonction est en restriction 
	//echo("<TD><INPUT type=button name=newpv value=\"$string4  \" onClick=\"alert('$string5')\"></TD></TR>\n");//Generate an acceptance form

		  //JNG trace
		  if (($op == "RP")||($op == "EF")||($op == "RS")){
			$crRecupere->traitement_mode = "CREATE";
		  }else{
			$crRecupere->traitement_mode = "MODIF";
		  }
		  
	echo("</TABLE>\n");
	//NJ 02_07_07
	// ajout d'apel delta
	delta();
		
			// fin trace

}// fin function accesDirect

//Cette fonction ferme le formulaire et définit le pied de la page prinicipale
function piedDePage1()
{
	global $urlASTRID, $visitcnt;
	global $langue_browser;
	global $file_count_date,$SYGAFE_VERSION;
	
	if ($langue_browser == "FR")
		{
			$string1=" DECONNEXION ";
			$string2=" DEPUIS ";
			$string3=" Nombre de visiteurs ";
			$string4="A_propos.htm ";
		}
	else
		{
			$string1="    LOGOUT    ";
			$string2=" SINCE ";
			$string3=" Number of visitors ";
			$string4="About.htm ";
		}
	echo("<HR><TABLE width=100%>\n");
	
	//image pour le mail
	echo("<TD width=50% align=left><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	echo("<A HREF=\"mailto:jean-francois.treyssat@alcatel.fr\"><IMG SRC=\"images/mail.jpg\" name=\"mailImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	echo("</FONT></TD>\n");
	
	//création du lien vers le fichier d'aide de SYGAFE
	echo("<TD width=50% align=left><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	
//	echo("<A HREF=".$urlASTRID."aide/aideSygafe.html TARGET=\"_blank\" onmouseOver=\"helpOver_js()\" onmouseOut=\"helpOut_js()\"><IMG SRC=\"images/help.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	echo("<A HREF=".$urlSygafe.$string4."TARGET=\"_blank\"><IMG SRC=\"images/paper.gif\" name=\"aboutImg\" width=\"89\" height=\"89\" border=\"0\"></A>\n");
	echo("<TD width=80% align=right><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	echo("<INPUT type=button name=dec  value=\" $string1 \" onClick=\"deconnex_js('firstPage')\">\n");		
	echo("<BR>Ver. $SYGAFE_VERSION");
	echo("<FONT COLOR=\"#0000FF\" SIZE=\"2\"><BR> $string2 $file_count_date ");
	echo("<BR>$string3 $visitcnt");
	echo("<INPUT TYPE=\"hidden\" name=\"visitcnt\" value=\"$visitcnt\">\n");
	echo("<INPUT TYPE=\"hidden\" name=\"file_count_date\" value=\"$file_count_date\">\n");
	echo("<FONT COLOR=\"#000000\" SIZE=\"3\">");
	echo("</FONT></TD></TR>\n");
	
	echo("</TABLE>\n");
	
	echo("</FORM>\n");
	echo("</BODY>\n");
	echo("</HTML>\n");
}

function piedDePage11($formname)
{
	global $urlASTRID;
	global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1=" DECONNEXION ";
			$string2="A_propos.htm ";
		}
	else
		{
			$string1="LOGOUT";
			$string2="About.htm ";
		}
	echo("<HR><TABLE width=100%>\n");
	
	//image pour le mail
	echo("<TD width=50% align=left><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	echo("<A HREF=\"mailto:jean-francois.treyssat@alcatel.fr\"><IMG SRC=\"images/mail.jpg\" name=\"mailImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	echo("</FONT></TD>\n");
	
	//création du lien vers le fichier d'aide de SYGAFE
	echo("<TD width=50% align=left><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	
//	echo("<A HREF=".$urlASTRID."aide/aideSygafe.html TARGET=\"_blank\" onmouseOver=\"helpOver_js()\" onmouseOut=\"helpOut_js()\"><IMG SRC=\"images/help.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	echo("<A HREF=".$urlSygafe.$string2."TARGET=\"_blank\"><IMG SRC=\"images/paper.gif\" name=\"aboutImg\" width=\"89\" height=\"89\" border=\"0\"></A>\n");
	echo("<TD width=80% align=right><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	echo("<INPUT type=button name=home  value=\" HOME \" onClick=\"home_js('$formname')\">\n");	
	
	echo("</FONT></TD></TR>\n");
	
	echo("</TABLE>\n");
	echo("<CENTER>\n");
	echo("<BR><INPUT type=button name=dec  value=\" $string1 \" onClick=\"deconnex_js('$formname')\">\n");			
	
	echo("</FORM>\n");
	echo("</BODY>\n");
	echo("</HTML>\n");
}

//Cette fonction c'est pour DELTA entre deux CR
function delta()
{
		global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1="Comparer deux cahiers de recette";
			$string2="Introduire les références ";
		}
	else
		{
			$string1="Compare two acceptance books";
			$string2="Select the references";
		}
	echo("<BR><HR>\n");
	
	echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
	echo("<CENTER><B>$string1</B><P>\n");
	echo("</FONT>\n");
	echo("<INPUT type=button name=delta value=\"$string2\" onClick=delta_refs_js()>\n");
	echo("<BR>\n");
	

}

//cette fonction met à jour le tableau systemesRetenus definis dans sygCommon.php
//les systèmes (tableau systemes du même fichier) commençant par la chaine
//entrée par l'utilisateur dans la zone de texte du formulaire sont copiés dans le tableau
function rechercherSystemes($sys_search)
{
global $systemes,$systemesRetenus;

	if ($sys_search == ""){
		$systemesRetenus = $systemes;
	} else {
		
		$i=0;
		$nomSys = ltrim($sys_search); //on supprime les blancs à gauche
		$sys_search = $nomSys;
		foreach($systemes as $sys){
			$nomSys = ltrim($sys->nom); 
			//on récupère tous les systèmes commençant par la chaine que 
			//l'utilisateur a saisie
			// non case sensitive
			$sys_search_non = strtolower ($sys_search);
			$nomSys_non = strtolower ($nomSys);
			if(ereg($sys_search_non,$nomSys_non)){
				$systemesRetenus[$i] = $sys;
				$i++;
			}
			
			
		}
	}
	
}
		
	
//cette fonction crée un select dynamiquement en y insérant les systèmes retenus
//suite à une éventuelle recherche de l'utilisateur.
function creerSelectSys($tab)
{
	global $op, $langue_browser;
	
	if ($langue_browser == "FR")
		{
			$string1="Systèmes reconnus :";
			$string2="------Sélectionner un système------";
			
		}
	else
		{
			$string1="Recognised systems:";
			$string2="------Select a system------------";
	
		}
	echo("<TR><TD COLSPAN=2><FONT COLOR=\"#0000F\" SIZE=\"4\">$string1</FONT></TD>\n");
	echo("<TD><SELECT name=selection_sys onChange=afficheP_js()>\n");
	
	//le premier element du tableau des systemes (systemes ou systemesRetenus) se trouve
	// à l'index 0 du tableau après la désérialisation
	
	echo("<OPTION value=-1 SELECTED>$string2\n");
	
	//on affiche tous les systèmes retenus du tableau correspondant.
	//si aucune recherche n'a été effectuée, l'ensemble des systèmes sont affichés
	
	while(list($key, $val) = each($tab)) {
	
		//la valeur attribuée à selection_sys est l'indice du tableau $systemesRetenus
			
			echo("<OPTION value=$key>".$val->nom."\n");
	}	
	echo("</SELECT></TD></TR>\n");
}
	
//cette fonction crée un select dynamiquement en y insérant les projets associés au 
//système sélectionné par l'utilisateur
function creerSelectProjet($unSysteme)
{
	global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1="------Sélectionner un projet------";
			$string2="Projets reconnus :";
		}
	else
		{
			$string1="------Select a project------------";
			$string2="Recognised projects:";
		}
	echo("<TR><TD COLSPAN=2><FONT COLOR=\"#0000F\" SIZE=\"4\">$string2</FONT></TD>\n");
	echo("<TD><SELECT name=selection_p onChange=afficheLgue_js()>\n");
	
	//le premier element du tableau des projets d'un systeme donné se trouve
	// à l'index -1 du tableau après la désérialisation
	
	echo("<OPTION value=-2 SELECTED>$string1\n");
	
	//on affiche tous les projets pour ce systeme
	
	while(list($key, $val) = each($unSysteme->projets)) {
	
		//la valeur attribuée à selection_p est l'indice du tableau des projets 
		//du système sélectionné
		
			echo("<OPTION value=$key>".$val->nom."\n");
	}	
	
	echo("</SELECT></TD></TR>\n");
}

//cette fonction permet de construire le tableau des langues pour un projet 
//variable globale $lguesDispo 
function tableauLguesDispo($unProjet){
	global $lguesDispo;
	
	//on ne prend que les langues différentes
	foreach($unProjet->cr as $ctg){
			if (isPresent($lguesDispo, $ctg->lgue,"chaine") == 0) {
				$lguesDispo[] = $ctg->lgue;
			
			}
		}				
}
		
//cette fonction crée un select dynamiquement en y insérant les CR associés au 
//projet sélectionné par l'utilisateur. Ces CR sont rangés par langue. 
function creerSelectLgue($unProjet,$index=-1)
{
	
	global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1="------Sélectionner la langue------";
			$string2="Langues disponibles :";
		}
	else
		{
			$string1="------Select the language---------";
			$string2="Available languages:";
		}
	$k = 0;
	$sel = "SELECTED";
		//ces deux variables sont définies dans sygCommon.php
		global $lguesDispo, $selection_lgue;
		
		echo("<TR><TD COLSPAN=2><FONT COLOR=\"#0000F\" SIZE=\"4\">$string2</FONT></TD>\n");
		echo("<TD><SELECT name=selection_lgue>\n");
		echo("<OPTION value=-2 >$string1\n");
	
		$i = 0;
		foreach($unProjet->cr as $ctg){
			if (isPresent($lguesDispo, $ctg->lgue,"chaine") == 0) {
				if($k == 0)
				{	
					$selected = $sel;
				}
				else
					$selected = "";
				$lguesDispo[] = $ctg->lgue;
				echo("<OPTION value=$i $selected>".$ctg->lgue."\n");
				$i++;
				$k++;
			
			}
		}		
		echo("</SELECT></TD></TR>\n");
		
}

//cette fonction c'est pour introduction des references CRs a comparer
function affich()
{
	global $langue_browser;

	
	if ($langue_browser == "FR")
		{
			$string1="Comparaison entre deux CAHIERS DE RECETTE en donnant les références GEDI";
			$string2="Référence CAHIER RECETTE 1:";
			$string3="Référence CAHIER RECETTE 2:";
			$string4="COMPARER LES CAHIERS";
		}
	else
		{
			$string1="Compare two acceptance books giving GEDI references";
			$string2="ACCEPTANCE BOOK reference 1:";
			$string3="ACCEPTANCE BOOK reference 2:";
			$string4="COMPARE THE ACCEPTANCE BOOKS";
		}

	echo("<FORM method=\"post\" action=\"sygMain.php\" name=\"delta\">\n");
	
	echo("<CENTER>\n");
	echo("<FONT COLOR=\"FF0000\" SIZE=\"6\">\n");
	echo("<B>SYGAFE</B><BR><BR>\n");
	echo("</FONT>\n");
	echo("</CENTER>\n");	
	echo("<HR><BR>\n");	
	
	echo("<CENTER>\n");
	echo("<FONT COLOR=\"000FFF\" SIZE=\"5\">\n");
	echo("<B>$string1</B><BR><BR>\n");
	echo("</FONT>\n");
	echo("</CENTER><BR>\n");
	
	echo("<TABLE CELLPADDING=5>\n");
	
	
//	$a1="3BW81085AAAAADAHB"; // Catalogue des fiches de recette   R25.2 
//	$a2="3BW808270001ADZZA"; // General Catalogue of acceptance   forms R25.1 
	
	
	//Référence d'un cahier de recette générique ou customisé
	echo("<TR><TD COLSPAN=2><FONT COLOR=\"#0000F\" SIZE=\"4\">$string2</FONT></TD>\n");
	echo("<TD><INPUT TYPE=\"text\" maxLength=\"17\" SIZE=\"24\" NAME=\"ref1\" VALUE=\"$a1\"></TD>\n");
	echo("<TR><TD COLSPAN=2><FONT COLOR=\"#0000F\" SIZE=\"4\">$string3</FONT></TD>\n");
	echo("<TD><INPUT TYPE=\"text\" maxLength=\"17\" SIZE=\"24\" NAME=\"ref2\" VALUE=\"$a2\"></TD>\n");
	echo("</TABLE>\n");	
	
	echo("<CENTER>\n");
	echo("<BR><BR><TR><TD><INPUT type=button name=delta value=\"$string4\" onClick=check_ref_js()></TD></TR>\n");
	echo("</CENTER><BR>\n");

	piedDePage11("delta");
	

}

function fileGedi($ref)
{

	global $racineSyg, $racineSygTemp,$nameSygTemp, $repUtil, $identificationCIl, $res, $refc;
	
	 		//la lettre désignant la langue se trouve être le 17ème caractère
	 		$lastLetter = $ref[16]; //premier élément à l'indice 0 !!
	 	
	 		$refc =$ref;
	 		if (strcmp($lastLetter,"A") == 0) {
				$lgue = "EN";
			} elseif (strcmp($lastLetter,"B") == 0) {
				$lgue = "FR";
			} elseif (strcmp($lastLetter,"D") == 0) {
				$lgue = "SP";
			}
	 	
			$res = -1; //code retour du gediget
		
		 	$chDirOk = chdir("$racineSygTemp$nameSygTemp/$repUtil/delta");
		 	if (!$chDirOk) {
		 		die("pb chdir script sygMainStep1!!");
		 	} else {
			
				system("gediget ".$ref." ".$lgue." $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/delta/res", $res);
				
			//	echo " fonctionsStep1:fileGedi ligne 636 : identificationCIl = $identificationCIl , code retour = $res <br>";
      // echo " fonctionsStep1:fileGedi ligne 636 : dir > = $racineSygTemp$nameSygTemp/$repUtil/delta/res <br>";
			 }
		 //on doit igonorer /sygTemp de $repUtil pour ne garder que le nom d'utilisateur ou identifiant saisi
		 $pos = strpos($repUtil,"/") + 1; //$repUtil est de la forme sygTemp/identifiant
		 $identifiant = substr($repUtil,$pos);
		
		if ($res != 0 ) {
		
		}else{
			$res=11;
		 	 //on récupère le nom du fichier xzip (un seul) pour le dézipper
			 $filesStep1 = list_dir("$racineSygTemp$nameSygTemp/$repUtil/delta/");
			 foreach ($filesStep1 as $file) {
			 	if (ereg(".xzip$",$file)) {
			 		//$nomFileXzip = $file;
					$tab_car_spe = explode( "_",$file);
						list($ref_cp,$edit_cp ,$langue_cp) = explode ("_", $file);
						if (strcmp($ref_cp,$ref) == 0)
						{	
							$res=0;
							$nomFileXzip = $file;
							//system("rm -fR $racineSygTemp$nameSygTemp/$repUtil/delta/$file");			
						}				
				}
			}
		}
		if ($res == 0 ) {
			 //unzip du fichier
			 //ATTENTION ! on utilisera provisoirement le unzip se trouvant sous
			 // /home/morin38/ mais il devra être installé correctement dans 
			 //l'environnment standard or pb avec MANPATH actuellement ??
			 system("unzip $racineSygTemp$nameSygTemp/$repUtil/delta/$nomFileXzip >> $racineSygTemp$nameSygTemp/$repUtil/resCdes");
			 system("rm -f $racineSygTemp$nameSygTemp/$repUtil/delta/$nomFileXzip");
						 
			 //on récupère le nom du fichier xml après dézippage
			 $filesStep1 = list_dir("$racineSygTemp$nameSygTemp/$repUtil/delta/");
			 foreach ($filesStep1 as $file) {
			 	if (ereg(".xml$",$file)) {
			 		$nomFileCr = $file;
			 	}
			 }
		}
return 	$nomFileCr;	 
}


?>
