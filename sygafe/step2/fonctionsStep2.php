<?php
/****************************************************************************************/
/******************************** fonctionsStep2.php ************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: ce fichier fournit les fonctions nécessaires à la mise en oeuvre des	*/
/* fonctionnalités de l'étape 2 à savoir l'affchage du cahier de recette et la 		*/
/* construction du catalogue final							*/	
/*											*/
/* fonctions:										*/
/*  											*/
/****************************************************************************************/


//JNG 
	
	$csl=$HTTP_COOKIE_VARS[Csl]; // nguyen6
	$repUtil = $csl;
	

	$cil = lire_gediInit("CN"); // jacob NGUYENTRITUO
	$passwd = lire_gediInit("PASSWD"); // jacob#7452
	$login_user = "\"$cil\"";  // "jacob NGUYENTRITUO"
	
//echo " fonctionsStep2 appel à lire_gediinit ligne 25: csl=$HTTP_COOKIE_VARS[Csl]; cil=$cil;login = $login_user; passwd=$passwd<br>";
	
  	
//********************************************************************************************	
// fonction permet de recalculer le nombre de fiches totals à customiser
function nbficherecette_info()
{
global $crRecupere;	//résultat de la sélection des chapitres, sous chapitres et fiches à customiser 

for($i=-1;$i < count($crRecupere->chapitres)-1;$i++)
	{
		$chap_courant = &$crRecupere->chapitres[$i];
		if ($chap_courant->selected ==1){
		//recherche des fiches dans ls chapitres
			for ($l = -1;$l < count($chap_courant->fr) - 1;$l++) 
			{
				$fr = &$chap_courant->fr[$l];
				if ($fr->selected == 1)
				{
					$nbfiche_select ++;
				}
			}// fin du for j
       
		for($j=-1;$j < count($chap_courant->sousChap)-1;$j++)
		{
			$souschap_courant = &$chap_courant->sousChap[$j];
			for ($k=-1;$k < count($souschap_courant->fr)-1;$k++)
			{
				$fiche_courant=&$souschap_courant->fr[$k];
				if($fiche_courant->selected == 1)
				{
					$nbfiche_select ++;
				}
			}
		}//fin du for j
		
	}//fin du chapitre sélectionné
	}//fin du for de i
	//echo " fonctionStep2.nbficherecette_info ligne 58: nombre de fiches sélectionnées = $nbfiche_select <br>";
	return $nbfiche_select;
}
//fin JNG

//********************************************************************************************	
//création du fichier html pour les frames avec affichage du sommaire
function frame()
{
	global $urlSygafe;
	global $urlASTRID;
	global $racineSyg, $repUtil;
	global $nameSygTemp,$racineSygTemp;
	global $urlRacineSyg;
	global $crRecupere;
	global $comp3DR;
	global $ref_cr;
	global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1="Impossible de créer le fichier titreSyg.html";
			$string2="DECONNEXION";
			$string3="Impossible de créer le fichier validSyg.html";
			$string4="Impossible de créer le fichier valid_R_Syg.html";
			$string5="Impossible de créer le fichier archive.html";
			$string6="Impossible de créer le fichier videSyg.html";
			$string7="Impossible de créer le fichier sommaireSyg.html";
			$string8="Impossible de créer le fichier FrSyg.html";
			$string9="Impossible de créer le fichier frameSyg.html";
			$string10="  Valider  ";
			$string11="Mettre à jour les compteurs";
			$string12="Désirez-vous générer une archive des Fiches de Recette ? ";	
			$string13="Les fiches de recettes sélectionnées";			
		}
	else
		{
			$string1="Can not create file titreSyg.html";
			$string2="LOGOUT";
			$string3="Can not create file validSyg.html";
			$string4="Can not create file valid_R_Syg.html";
			$string5="Can not create file archive.html";
			$string6="Can not create file videSyg.html";
			$string7="Can not create file sommaireSyg.html";
			$string8="Can not create file FrSyg.html";	
			$string9="Can not create file frameSyg.html";
			$string10="  Validate  ";
			$string11="  update meters  ";
			$string12=" Would you like to create the archive of the acceptance sheets ?";
			$string13="The acceptance sheets selected";
			
		}	
	
	$ref_ctgp = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc ;
	
	//fichier pour le titre
	if (!$file_title = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/titreSyg$ref_ctgp.html","w")) {
	 	die("$string1");
	 } else {
	 	fputs($file_title,"<HTML>\n");
	 	fputs($file_title,"<HEAD>\n");
	 	fputs($file_title,"<TITLE>SYGAFE</TITLE>\n");
	 	fputs($file_title,"</HEAD>\n");
	 	fputs($file_title,"<BODY BGCOLOR=\"#000088\">\n");
	 	fputs($file_title,"<TABLE width=100%>\n");
		
		//création du lien vers le fichier d'aide de SYGAFE
		fputs($file_title,"<TD width=5% align=left><FONT COLOR=\"#000000\" SIZE=\"2\">\n");
		if ($langue_browser == "FR"){
			fputs($file_title,"<A HREF=".$urlSygafe."MANUEL_UTILISATION.pdf TARGET=\"_blank\" ><IMG SRC=\"".$urlSygafe."images/help1.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
		}else{
			fputs($file_title,"<A HREF=".$urlSygafe."USER_MANUAL.pdf TARGET=\"_blank\" ><IMG SRC=\"".$urlSygafe."images/help1.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
		}
		//Titre
		fputs($file_title,"<TD width=95% align=center><FONT COLOR=\"#FFFF00\" SIZE=\"5\">\n");
		fputs($file_title,"<B>".$crRecupere->titleDoc.'   '.$ref_ctgp.' .'.$crRecupere->editionDoctmp."<B>\n");
		fputs($file_title,"</FONT></TD>\n");
		
		fputs($file_title,"<TD width=98% ><FONT COLOR=\"#000000\" SIZE=\"2\">\n");
		$urlSygafeHOME = $urlSygafe."sygMain.php?op=DCX&home=1";
		fputs($file_title,"<FORM><INPUT type=button name=home value=\" HOME \" onClick=\"parent.window.location='$urlSygafeHOME'\"></FORM>\n");	
			
		fputs($file_title,"<TD width=99% ><FONT COLOR=\"#000000\" SIZE=\"2\">\n");
		$urlSygafeDECC = $urlSygafe."sygMain.php?op=DCX";
		fputs($file_title,"<FORM><INPUT type=button name=home value=\" $string2 \" onClick=\"parent.window.location='$urlSygafeDECC'\"></FORM>\n");	

		//reference CR et edition
	 	fputs($file_title,"</FONT></TD></TR>\n");
		fputs($file_title,"</TABLE>\n");
				
		fputs($file_title,"</BODY>\n");
	 	fputs($file_title,"</HTML>\n");
	 }
	 
	 fclose($file_title);
	 
	 //fichier pour la frame où est affiché le bouton valider
	 if (!$file_valid = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/validSyg.html","w")) {
	 	die("$string3");
	 } else {
	 	fputs($file_valid,"<HTML>\n");
	 	fputs($file_valid,"<HEAD>\n");
	 	fputs($file_valid,"<TITLE>SYGAFE</TITLE>\n");
	 	fputs($file_valid,"</HEAD>\n");
	 	fputs($file_valid,"<BODY BGCOLOR=\"#FFFFES\">\n");
	 	fputs($file_valid,"<FORM method=post action=# name=validFrame>\n");
	 	fputs($file_valid,"<CENTER>\n");
	 	fputs($file_valid,"<INPUT type=button name=valid_b value=\"$string10\" onClick=JavaScript:parent.contenuCR.validCtg_js()>\n");
	  fputs($file_valid,"</CENTER>\n");
	 	fputs($file_valid,"</FORM>\n");
	 	fputs($file_valid,"</BODY>\n");
	 	fputs($file_valid,"</HTML>\n");
	 }
	 
	 fclose($file_valid);
	 
	 //*****
	 
 //fichier pour la frame où est affiché le bouton valider et Rafraîchir
	 if (!$file_valid = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/valid_R_Syg$ref_ctgp.html","w")) {
	 	die("$string4");
	 } else {
	 	fputs($file_valid,"<HTML>\n");
	 	fputs($file_valid,"<HEAD>\n");
	 	fputs($file_valid,"<TITLE>SYGAFE</TITLE>\n");
	 	fputs($file_valid,"</HEAD>\n");
	 	fputs($file_valid,"<BODY BGCOLOR=\"#FFFFES\">\n");
	 	fputs($file_valid,"<FORM method=post action=# name=validFrame>\n");
	 	fputs($file_valid,"<CENTER>\n");

	 	fputs($file_valid,"<INPUT type=button name=valid_b value=\"$string10\" onClick=JavaScript:parent.contenuCR.validCtg_js()>\n");
	 	fputs($file_valid,"<INPUT type=button name=refresh value=\"$string11\" onClick=JavaScript:parent.contenuCR.refresh_js('toc','')>\n");
	 	fputs($file_valid,"</CENTER>\n");

	 	fputs($file_valid,"</FORM>\n");
	 	fputs($file_valid,"</BODY>\n");
	 	fputs($file_valid,"</HTML>\n");
	 }
	 
	 fclose($file_valid);	 
	 
 //fichier pour la frame où est affiché le bouton pour generation archive avec les FRs de CR custo
	 if (!$file_valid = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/archive$ref_ctgp.html","w")) {
	 	die("$string5");
	 } else {
	 	fputs($file_valid,"<HTML>\n");
	 	fputs($file_valid,"<HEAD>\n");
	 	fputs($file_valid,"<TITLE>SYGAFE</TITLE>\n");
	 	fputs($file_valid,"</HEAD>\n");
	 	fputs($file_valid,"<BODY BGCOLOR=\"#FFFFES\">\n");
	 	fputs($file_valid,"<FORM method=post action=# name=archiv>\n");
	 	fputs($file_valid,"<CENTER>\n");
	 	fputs($file_valid,"<FONT COLOR=\"#000FF\" SIZE=\"5\"\n");
	 	fputs($file_valid,"<A>$string12</A>\n");
	 	fputs($file_valid,"<BR><BR>\n");
	 	fputs($file_valid,"<INPUT type=button name=refresh value=\"  OUI  \" onClick=archives()>\n");
	 	fputs($file_valid,"</CENTER>\n");
	 	fputs($file_valid,"</FORM>\n");
	 	fputs($file_valid,"</BODY>\n");
	 	fputs($file_valid,"</HTML>\n");
	 }
	 
	 fclose($file_valid);	 
	 //*****
	 
	 //fichier vide utilisé par la suite pour effacer le sommaire
	 if (!$file_vide = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/videSyg.html","w")) {
	 	die("$string6");
	 } else {
	 	fputs($file_vide,"<HTML>\n");
	 	fputs($file_vide,"<HEAD>\n");
	 	fputs($file_vide,"<TITLE>SYGAFE</TITLE>\n");
	 	fputs($file_vide,"</HEAD>\n");
	 	fputs($file_vide,"<BODY BGCOLOR=\"#05684d\">\n");
	 	fputs($file_vide,"</BODY>\n");
	 	fputs($file_vide,"</HTML>\n");
	 }
	 
	  fclose($file_vide);
	
	//fichier pour le sommaire
	
	if (!$file_toc = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/sommaireSyg$ref_ctgp.html","w")) {
	 	die("$string7");
	} else {
	 	fputs($file_toc,"<HTML>\n");
	 	fputs($file_toc,"<HEAD>\n");
	 	fputs($file_toc,"<TITLE>SYGAFE</TITLE>\n");
	 	fputs($file_toc,"<SCRIPT>\n");
	 	fputs($file_toc,"function refresh_js(operation,ancre) {\n");
	 	fputs($file_toc,"parent.contenuCR.document.affichCR.submit();\n");
	 	fputs($file_toc,"}\n");
	 	fputs($file_toc,"</SCRIPT>\n");
	 	fputs($file_toc,"</HEAD>\n");
	 	fputs($file_toc,"<BODY BGCOLOR=\"#05684d\">\n");
	 	
	 	
	 	
	 	$chapitres = $crRecupere->chapitres;
	 	
	 			//JNG trace
	//		$refresh_ancre =	$chap->num;
	//$edition_etat = " fonctionsStep2  appel à function frame pour créer le sommaire => ligne 265, ancre=".$refresh_ancre;
	//$string1000 = "";
	//erori ($edition_etat,$string1000);
	//
        //fin trace
		
		for ($i = -1;$i < count($chapitres) - 1 ;$i++) {
			// commencer de chercher les chapitres
			$chap = $chapitres[$i];
			//construction du sommaire : liens vers les chapitres
			fputs($file_toc,"<P><H4><A HREF=\"JavaScript:parent.contenuCR.refresh_js('toc','".$chap->num."')\"><B><FONT COLOR=\"#FFFFFF\">$chap->num $chap->nom</FONT></B></A><BR>\n");
			fputs($file_toc,"<UL><BR>\n");
					
			//affichage des sous-chapitres
			for($j = -1;$j < count($chap->sousChap) - 1; $j++) {
				$sc = $chap->sousChap[$j];
				//sommaire : lien vers les sous-chapitres
				fputs($file_toc,"<P><H4><A HREF=\"JavaScript:parent.contenuCR.refresh_js('toc','".$sc->num."')\"><B><FONT COLOR=\"#FFFFFF\">$sc->num $sc->nom</FONT></B></A><BR>\n");
			}
			fputs($file_toc,"</UL>\n");		
		}
	 }
	 
	 fputs($file_toc,"</BODY>\n");
	 fputs($file_toc,"</HTML>\n");
	 
	 fclose($file_toc);
	 
	 //*****
	  //fichier pour Ajuter FR
	 if (!$file_FR = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/FrSyg$ref_ctgp.html","w")) {
	 	die("$string8");
	 } else {
	 	fputs($file_FR,"<HTML>\n");
	 	fputs($file_FR,"<HEAD>\n");
	 	fputs($file_FR,"<TITLE>SYGAFE</TITLE>\n");
	 	fputs($file_FR,"</HEAD>\n");
	 	fputs($file_FR,"<BODY BGCOLOR=\"#05684d\">\n");
	 	fputs($file_FR,"</BODY>\n");
	 	fputs($file_FR,"</HTML>\n");
	 }
	 
	 //*****
	
	
	if (!$file_f = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/frameSyg$ref_ctgp.html","w")) {
	 	die("$string9");
	 } else {
	 	fputs($file_f,"<HTML>\n");
	 	fputs($file_f,"<HEAD>\n");
	 	fputs($file_toc,"<TITLE>SYGAFE</TITLE>\n");
		fputs($file_f,"</HEAD>\n");
	 	fputs($file_f,"<FRAMESET ROWS=\"13%,17%,69%\">\n");
	 	fputs($file_f,"<FRAME SRC=\"".$urlRacineSyg.$nameSygTemp."/".$repUtil."/step2/titreSyg$ref_ctgp.html\" NAME=\"titreCR\">\n");
		 	fputs($file_f,"<FRAME SRC=\"".$urlSygafe."sygMain.php?op=afi&rep=".$repUtil."\" NAME=\"ajoutFR\">\n");
	 		//fputs($file_f,"<FRAMESET COLS=\"25%,75%\">\n");
			fputs($file_f,"<FRAMESET COLS=\"30%,70%\">\n");
				//fputs($file_f,"<FRAMESET ROWS=\"15%,81%\">\n");
				fputs($file_f,"<FRAMESET ROWS=\"20%,76%\">\n");				
				fputs($file_f,"<FRAME SRC=\"".$urlRacineSyg.$nameSygTemp."/".$repUtil."/step2/valid_R_Syg$ref_ctgp.html\" NAME=\"validFrame\">\n");
	 			fputs($file_f,"<FRAME SRC=\"".$urlRacineSyg.$nameSygTemp."/".$repUtil."/step2/sommaireSyg$ref_ctgp.html\" NAME=\"sommaireCR\">\n");
	 			fputs($file_f,"</FRAMESET>\n");
	 		fputs($file_f,"<FRAME SRC=\"".$urlSygafe."sygMain.php?op=prt&rep=".$repUtil."&ref_cr=".$ref_cr."\" NAME=\"contenuCR\">\n");
	  	fputs($file_f,"</FRAMESET>\n");
		fputs($file_f,"</FRAMESET>\n");
	 	fputs($file_f,"</HTML>\n");
	}
	
	fclose($file_f);
}	 	

//********************************************************************************************	
//cette fonction permet d'afficher le cahier de recette avec pour les chapitres,
//sous-chapitres et FR des cases à cocher
function afficherCR()
{
	//code opération et nom du CR récupéré
	global $op, $nameCR;
	global $type; //$type vaut toc quand c'est un lien du sommaire qui est activé
		      //dans ce cas on n'affiche pas le sommaire (il n'a pas été effacé!)
	global $crRecupere, $comp3DR, $ref_cr, $mat, $index, $mated;
	global $urlSygafe;
	global $racineSyg, $racineSygTemp,$repUtil;
	global $path_download;
	
	global $langue_browser,$passwd,$login_user;
	global $nbfiche_select_max,$nbfiche_select,$nbfiche_total;
	
	

	$ref_ctgp = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc ;

	$ii = 1; 
	
	if ($langue_browser == "FR"){
		$string1=" Nombre total de fiches de recette : ";
		$string2=" Nombre de fiches non sélectionnées : ";
		$string3=" Nombre de fiches sélectionnées : ";
		$string4="!! Attention !! Nombre max de fiches sélectionnées pour générer une archive (ZIP) = ";
		$string5="Mettre à jour les compteurs";
	}else{
		$string1="Total number of acceptance sheets: ";
		$string2="Number of unselected acceptance sheets: ";
		$string3="Number of selected acceptance sheets: ";
		//$string4="!! warning !! Number of selected acceptance sheets max for an archive creation (zip) = 60";
		$string4="!! warning !! Number of selected acceptance sheets max for an archive creation (zip) = ";
		$string5="  update meters  ";
	}
	
	
						//JNG trace
	global $chap_select;
				//fin trace
	
	//construction du formulaire
	echo("<FORM method=post action=sygMain.php?op=ctg name=affichCR>\n");
	//champ avec referance Cahier	
	echo("<INPUT TYPE=\"hidden\" NAME=\"ref_ctgp\" VALUE=\"".$ref_ctgp."\">\n");	
	//champ caché pour le réperoire utilisateur
	echo("<INPUT TYPE=\"hidden\" NAME=\"rep\" VALUE=\"".$repUtil."\">\n");
	//champ caché pour savoir si c'est un chapitre (ch) ou un sous-chapitre (sch) qui est modifié
	//ou encore un lien du sommaire (toc)
	echo("<INPUT TYPE=\"hidden\" NAME=\"type\" VALUE=\"\">\n");
	//champ caché pour connaître l'indice du tableau du chapitre qui est modifié
	echo("<INPUT TYPE=\"hidden\" NAME=\"chindex\" VALUE=-2>\n");
	//champ caché pour connaître l'indice du tableau des sous-chapitre qui est modifié
	echo("<INPUT TYPE=\"hidden\" NAME=\"schindex\" VALUE=-2>\n");
	
		//JNG trace	champ caché pour passer la variable du chapitre sélectionné dans la sommaire à la rafraichie'affichage	
		//	echo " fonctionsStep2.afficherCR debut => ligne 376  : type=".$type.", operation =".$op.", index=".$index."<br>";
  //champ caché pour passer la variable du chapitre sélectionné dans la sommaire à la rafraichie'affichage
  echo("<INPUT TYPE=\"hidden\" NAME=\"chap_select\" VALUE=>\n");
		//fin trace
	
	$chapitres = $crRecupere->chapitres;
	for ($i = -1;$i < count($chapitres) - 1 ;$i++) {
		$chap = $chapitres[$i];
		if ($i < 8)
			$refchap = "0".($i + 2);
		else 
			$refchap = $i + 2;
		$refschap = "00";
		
				//JNG trace
			$refresh_ancre =	$chap->num;
			//echo " fonctionsStep2.afficherCR  appel à refresh_js => ligne 405  : chap selec=".$chap->selected.", operation = rfsh, ancre=".$refresh_ancre."<br>";
				//fin trace

		if($chap->selected == 0){
			
			echo("<H2><INPUT TYPE=\"checkbox\" NAME=\"tabChap[]\" VALUE=$chap->num onClick=\"document.affichCR.type.value = 'ch'; document.affichCR.chindex.value = ".$i."; refresh_js('rfsh',".$chap->num.")\"><A NAME=\"$chap->num\">$chap->num $chap->nom</A><BR>\n");
		
		} else {

			echo("<H2><INPUT TYPE=\"checkbox\" NAME=\"tabChap[]\" VALUE=$chap->num CHECKED onClick=\"document.affichCR.type.value = 'ch'; document.affichCR.chindex.value = ".$i."; refresh_js('rfsh',".$chap->num.")\"><A NAME=\"$chap->num\">$chap->num $chap->nom</A><BR>\n");
		
		}
		echo("<UL>\n");
		//affichage des Fr du chapitre sous la forme mnémonique - référence - gésignation
		//avec référence correspondant à un lien permettant l'ouverture d'une nouvelle fenêtre
		//pour afficher la FR (requête http vers GEDI)
		echo("<TABLE border=2>\n");
		
		
		foreach($chap->fr as $uneFr) {
			if($uneFr->selected == 0) {
					$cnto++;
					$cntonons++;
					$varjava = "form";
					$varjava = $varjava  .$cnto;
					$vared = "ed";
					$vared = $vared .$cnto;
					$uneFr->tdrmod =$uneFr->tdr;
					$uneFr->tdr[10] ="P";
					$uneFr->tdr[11] ="D";

				echo("<TR BGCOLOR=\"CCFFFF\"><TD><P><H4><INPUT TYPE=\"checkbox\" NAME=\"tabRefChap[]\" VALUE=$uneFr->ref$refchap$refschap></TD><TD><A HREF=\"JavaScript:parent.contenuCR.lireFiche()\" onClick=\"window.open('http://gedi.ln.cit.alcatel.fr/surespeed/gedi/S_docLink?F_scope=publi?&F_reference=$uneFr->ref','new','width=800,height=500,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')\">$uneFr->ref</A></TD><TD>$uneFr->des</TD><TD><INPUT type=\"text\" maxLength=\"2\" size=\"2\" name=\"$varjava\" value=\"$uneFr->ed\"></TD></TR>\n");
				echo("<TR BGCOLOR=\"AAAAAA\"><TD><P><H4><INPUT TYPE=\"hidden\" NAME=\"vide\" ></TD><TD><A HREF=\"JavaScript:parent.contenuCR.lireFiche()\" onClick=\"window.open('http://delta1.ln.cit.alcatel.fr/DELTA/FILES/IMGFILES1/$uneFr->tdr.PDF','new','width=800,height=500,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')\">$uneFr->tdrmod</A></TD><TD>$uneFr->ndr</TD></TR>\n");
				echo("<TD><INPUT TYPE=\"hidden\" NAME=\"$vared\"   value = \"$uneFr->ed\" ></TD>");
			} else {
					$cnto++;
					$varjava = "form";
					$varjava = $varjava  .$cnto;
					$vared = "ed";
					$vared = $vared .$cnto;
					$uneFr->tdrmod =$uneFr->tdr;
					$uneFr->tdr[10] ="P";
					$uneFr->tdr[11] ="D";

				echo("<TR BGCOLOR=\"CCFFFF\"><TD><P><H4><INPUT TYPE=\"checkbox\" NAME=\"tabRefChap[]\" VALUE=$uneFr->ref$refchap$refschap CHECKED></TD><TD><A HREF=\"JavaScript:parent.contenuCR.lireFiche()\" onClick=\"window.open('http://gedi.ln.cit.alcatel.fr/surespeed/gedi/S_docLink?F_scope=publi?&F_reference=$uneFr->ref','new','width=800,height=500,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')\">$uneFr->ref</A></TD><TD>$uneFr->des</TD><TD><INPUT type=\"text\" maxLength=\"2\" size=\"2\" name=\"$varjava\" value=\"$uneFr->ed\"></TD></TR>\n");
				echo("<TR BGCOLOR=\"AAAAAA\"><TD><P><H4><INPUT TYPE=\"hidden\" NAME=\"vide\" ></TD><TD><A HREF=\"JavaScript:parent.contenuCR.lireFiche()\" onClick=\"window.open('http://delta1.ln.cit.alcatel.fr/DELTA/FILES/IMGFILES1/$uneFr->tdr.PDF','new','width=800,height=500,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')\">$uneFr->tdrmod</A></TD><TD>$uneFr->ndr</TD></TR>\n");
				echo("<TD><INPUT TYPE=\"hidden\" NAME=\"$vared\"   value = \"$uneFr->ed\" ></TD>");
			} 
		} // fin du  foreach($chap->fr as $uneFr)
		
		echo("</TABLE>\n");
		//affichage des sous-chapitres
		//ne pas oublier de mettre les ancres pour l'affichage et de les fournir en paramètre
		//dans la fonction refresh_js()
		
		for($j = -1;$j < count($chap->sousChap) - 1; $j++) {
			$sc = $chap->sousChap[$j];
			if ($j < 8)
			$refschap = "0".($j + 2);
			else 
			$refschap = $j + 2;
			
			//JNG trace
			//$refresh_ancre =	$sc->num;
			//echo " fonctionsStep2  appel à refresh_js => ligne 469  : sous chap selec=".$sc->selected.", operation = rfsh, ancre=".$refresh_ancre."<br>";
			//fin trace
			
			if($sc->selected == 0) {

				echo("<P><H3><INPUT TYPE=\"checkbox\" NAME=\"tabSousChap[]\" VALUE=$sc->num onClick=\"document.affichCR.type.value = 'sch'; document.affichCR.chindex.value = ".$i."; document.affichCR.schindex.value = ".$j."; refresh_js('rfsh',".$sc->num.")\"><A NAME=\"$sc->num\">$sc->num $sc->nom</A><BR>\n");
			
      } else {

				echo("<P><H3><INPUT TYPE=\"checkbox\" NAME=\"tabSousChap[]\" VALUE=$sc->num CHECKED onClick=\"document.affichCR.type.value = 'sch'; document.affichCR.chindex.value = ".$i."; document.affichCR.schindex.value = ".$j."; refresh_js('rfsh',".$sc->num.")\"><A NAME=\"$sc->num\">$sc->num $sc->nom</A><BR>\n");
			}
			echo("<UL>\n");
			//affichage des Fr du sous-chapitre sous la forme mnémonique - référence - gésignation
			//avec référence correspondant à un lien permettant l'ouverture d'une nouvelle fenêtre
			//pour afficher la FR (requête http vers GEDI)
			echo("<TABLE border=2>\n");
			foreach($sc->fr as $uneFr) {
				if($uneFr->selected == 0) {
					$cnto++;
					$cntonons++;
					$varjava = "form";
					$varjava = $varjava  .$cnto;
					$uneFr->tdrmod =$uneFr->tdr;
					$uneFr->tdr[10] ="P";
					$uneFr->tdr[11] ="D";

					echo("<TR BGCOLOR=\"CCFFFF\"><TD><P><H4><INPUT TYPE=\"checkbox\" NAME=\"tabRefSousChap[]\" VALUE=$uneFr->ref$refchap$refschap ></TD><TD><A HREF=\"JavaScript:parent.contenuCR.lireFiche()\" onClick=\"window.open('http://gedi.ln.cit.alcatel.fr/surespeed/gedi/S_docLink?F_scope=publi?&F_reference=$uneFr->ref','new','width=800,height=500,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')\">$uneFr->ref</A></TD><TD>$uneFr->des</TD><TD><INPUT type=\"text\" maxLength=\"2\" size=\"2\" name=\"$varjava\" value=\"$uneFr->ed\"></TD></TR>\n");
					echo("<TR BGCOLOR=\"AAAAAA\"><TD><P><H4><INPUT TYPE=\"hidden\" NAME=\"vide\" ></TD><TD><A HREF=\"JavaScript:parent.contenuCR.lireFiche()\" onClick=\"window.open('http://delta1.ln.cit.alcatel.fr/DELTA/FILES/IMGFILES1/$uneFr->tdr.PDF','new','width=800,height=500,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')\">$uneFr->tdrmod</A></TD><TD>$uneFr->ndr</TD></TR>\n");
				} else {
					$cnto++;
					$varjava = "form";
					$varjava = $varjava  .$cnto;
					$uneFr->tdrmod =$uneFr->tdr;
					$uneFr->tdr[10] ="P";
					$uneFr->tdr[11] ="D";
					
					echo("<TR BGCOLOR=\"CCFFFF\"><TD><P><H4><INPUT TYPE=\"checkbox\" NAME=\"tabRefSousChap[]\" VALUE=$uneFr->ref$refchap$refschap CHECKED></TD><TD><A HREF=\"JavaScript:parent.contenuCR.lireFiche()\" onClick=\"open('http://gedi.ln.cit.alcatel.fr/surespeed/gedi/S_docLink?F_scope=publi?&F_reference=$uneFr->ref','new','width=800,height=500,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')\">$uneFr->ref</A></TD><TD>$uneFr->des</TD><TD><INPUT type=\"text\" maxLength=\"2\" size=\"2\" name=\"$varjava\"  value = \"$uneFr->ed\" ></TD></TR>\n");
					echo("<TR BGCOLOR=\"AAAAAA\"><TD><P><H4><INPUT TYPE=\"hidden\" NAME=\"vide\" ></TD><TD><A HREF=\"JavaScript:parent.contenuCR.lireFiche()\" onClick=\"window.open('http://delta1.ln.cit.alcatel.fr/DELTA/FILES/IMGFILES1/$uneFr->tdr.PDF','new','width=800,height=500,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')\">$uneFr->tdrmod</A></TD><TD>$uneFr->ndr</TD></TR>\n");
				}
			} // fin foreach($sc->fr as $uneFr)
			echo("</TABLE>\n");				
			echo("</UL>\n");
		}  // fin de la boucle for $j ===>  sur les sous chapitres
			echo("</UL>\n");
		
	} // fin de la boucle $i ===>  sur des chapitres
	$cntos = $cnto - $cntonons;

	if ($cntonons == "")
		$cntonons = 0;

	echo("<CENTER><FONT COLOR=\"#000FF\" SIZE=\"5\">\n");
	echo("<TD><INPUT TYPE=\"hidden\" NAME=\"index\"   value = \"$cnto\" ></TD>");	
	echo("<TD><B>$string1 $cnto<B></TD>");
	echo("<BR>\n");
	echo("<TD><B><FONT COLOR=\"#FF000\" SIZE=\"5\">$string2 $cntonons<B></TD>");
	echo("<BR>\n");
	echo("<TD><B><FONT COLOR=\"#000FF\" SIZE=\"5\">$string3 $cntos<B></TD>");
	//JNG ajout le 28_11_06
	if ($cntos > $nbfiche_select_max) {
		echo("<BR>\n");
		echo("<TD><B><FONT COLOR=\"#FF000\" SIZE=\"5\">$string4$nbfiche_select_max<B></TD>");
	}
	//fin
	echo("</CENTER><BR>\n");
	//JNG ajout le 31_01_2007
	echo("<CENTER>\n");
	echo("<INPUT type=button name=refresh value=\"$string5\" onClick=JavaScript:parent.contenuCR.refresh_js('toc','')>\n");
	echo("</CENTER><BR>\n");
	//fin
	
	echo("</CENTER><BR>\n");
	/**/
	//fin de page
	echo("</FORM>\n");
	echo("</BODY>\n");
	echo("</HTML>\n");
	
}	// fin fonction afficheCR



//fonctions pour la création du cahier de recette customisé

//********************************************************************************************	
//entete du CR customisé et du PV
function entete($file)
{
	global $crRecupere;
	
	fputs($file,"<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	fputs($file,"<!DOCTYPE genericdoc PUBLIC \"-//ALCATEL//GENERICDOC 2.0//EN\"\n");
	fputs($file," \"genericdoc.dtd\">\n");
	fputs($file,"<!--ArborText, Inc., 1988-2001, v.4002-->\n");
	fputs($file,"<?Pub EntList alpha bull copy rArr sect trade Omega?>\n");
	fputs($file,"<?Pub Inc?>\n");
	if ($crRecupere->lgueDoc == A) {
		$langue = EN;
	} elseif ($crRecupere->lgueDoc == B) {
		$langue = FR;
	} else {
		$langue = SP;
	}
	fputs($file,"<genericdoc template=\"other\" lang=\"".$langue."\"><docstatus>\n");

}

//********************************************************************************************	
//informations sur le type du document (référence, édition, ...)
//valable pour le CR customisé et le PV
//$typeDoc est le type de document Cahier de recette customisé ou Pv de recette
function InfosDoc($file,$typeDoc)
{
//$lgueCR vaut A, B ou D

	global $crRecupere;
	
	fputs($file,"<refdoc codif=\"new\"><prefix>".$crRecupere->prefixDoc."</prefix><number>".$crRecupere->numberDoc."</number><variant>".$crRecupere->variantDoc."</variant>\n");
	fputs($file,"<version>".$crRecupere->versionDoc."</version><documenttype>".$crRecupere->typeDoc."</documenttype><zz>".$crRecupere->zzDoc."</zz><language><?Pub Caret?>".$crRecupere->lgueDoc."</language>\n");
	fputs($file,"</refdoc>\n");
	fputs($file,"<titledoc><title1>".$crRecupere->titleDoc."</title1></titledoc>\n");
	fputs($file,"<heading><content></content></heading>\n");
	fputs($file,"<subheading><content></content></subheading>\n");
	fputs($file,"<mnemonicdoc>".$crRecupere->mnemonicDoc."</mnemonicdoc>\n");
/*	fputs($file,"<system>\n");
	fputs($file,"<systemcode></systemcode>\n");
	fputs($file,"<systemtitle></systemtitle>\n");
	fputs($file,"</system>\n");
	fputs($file,"<abstract><abstractitem></abstractitem>\n");
	fputs($file,"</abstract>\n");
*/	
  fputs($file,"<ednum>".$crRecupere->editionDoc."</ednum>\n");
	fputs($file,"<status statuscode=\"RL\"/>\n");
	fputs($file,"<confidentiality cf=\"no\"/>\n");

	$jour = date("j");
	$mois = date("m");
	$annee = date("Y");
	
	fputs($file,"<lastmodifdate><year>".$annee."</year><month>".$mois."</month><day>".$jour."</day></lastmodifdate>\n");
	fputs($file,"<descript><descriptitem>$crRecupere->description</descriptitem>\n");
	fputs($file,"</descript>\n");
	fputs($file,"<typedoc>".$typeDoc."</typedoc>\n");
	fputs($file,"</docstatus>\n");
	fputs($file,"<specific>\n");
	fputs($file,"<other>\n");
	fputs($file,"<systemtitlehdl/><systemcodehdl/>\n");
	fputs($file,"<doctitlehdl/>\n");
	fputs($file,"<typedochdl/>\n");
	fputs($file,"<statushdl/>\n");
	fputs($file,"<mnemonichdl/><copyrighthdl/></other>\n");
	fputs($file,"</specific>\n");
	fputs($file,"<doccontent>\n");
	fputs($file,"<toc/>\n");
	fputs($file,"<docbody>\n");
	
}

//********************************************************************************************	
//nouveau chapitre
function chapitre($file,$nom)
{
	fputs($file,"<h1><ht>".$nom."</ht>\n");	
}

//********************************************************************************************	
//début du corps d'un chapitre ou sous-chapitre pour CR customisé
function corpsCr($file)
{
	fputs($file,"<frame>\n");
	fputs($file,"<table>\n");
	fputs($file,"<tgroup cols=\"3\"><colspec colnum=\"1\" colname=\"col1\"\n");
	fputs($file,"colwidth=\"2.16*\"/><colspec\n");
	fputs($file,"colnum=\"2\" colname=\"COLSPEC0\" align=\"left\" charoff=\"50\" char=\"\"\n");
	fputs($file,"colwidth=\"1.36*\"/>\n");
	fputs($file,"<colspec colnum=\"3\" colname=\"COLSPEC2\" align=\"left\" charoff=\"50\" char=\"\"\n");
	fputs($file,"colwidth=\"0.32*\"/>\n");
	fputs($file,"<tbody>\n");
}


//********************************************************************************************	
//corps pour la partie déroulement des tests pour le PV
//on passe en paramètre la fiche de recette pour afficher les infos
//nécessaires la concernant
function corpsPvTests($file,$uneFr,$lgue)
{
	if ($lgue == "B") {
		$nameCol1 = "R&eacute;f&eacute;rence";
		$nameCol2 = "tests";
		$accepte = "Accept&eacute;";
		$nonAccepte = "Non Accept&eacute;";
		$nonTeste = "Non Test&eacute;";
		$deroulement = "D&eacute;roulement des tests";
		$title = "DEROULEMENT DES TESTS"; //nom du tableau
	} elseif ($lgue == "D") {
		$nameCol1 = "Referencia";
		$nameCol2 = "Pruebas";
		$accepte = "Aceptado";
		$nonAccepte = "No Aceptado";
		$nonTeste = "No Probado";
		$deroulement = "Desarrollo de las pruebas";
		$title = "DESARROLLO DE LAS PRUEBAS"; //nom du tableau
	} else {
		$nameCol1 = "Reference";
		$nameCol2 = "tests";
		$accepte = "Accepted";
		$nonAccepte = "Not Accepted";
		$nonTeste = "Not Tested";
		$deroulement = "Progress of tests";
		$title = "PROGRESS OF TESTS"; //nom du tableau
	}
	
	fputs($file,"<frame>\n");
	fputs($file,"<table>\n");
	fputs($file,"<caption>$title</caption>\n");
	fputs($file,"<tgroup cols=\"5\">\n");
	fputs($file,"<?PubTbl tgroup dispwid=\"8.02in\"?>\n");
	fputs($file,"<colspec colname=\"col1\" colwidth=\"1.39*\"/><colspec colname=\"col2\" colwidth=\"1.97*\"/>\n");
	fputs($file,"<colspec colname=\"COLSPEC0\" align=\"left\" charoff=\"50\" char=\"\" colwidth=\"0.73*\"/>\n");
	fputs($file,"<colspec colname=\"COLSPEC3\" align=\"left\" charoff=\"50\" char=\"\" colwidth=\"0.70*\"/>\n");
	fputs($file,"<colspec colname=\"COLSPEC2\" align=\"left\" charoff=\"50\" char=\"\" colwidth=\"0.68*\"/>\n");
	fputs($file,"<tbody>\n");
	fputs($file,"<row>\n");
	fputs($file,"<entry colname=\"col1\"><para>$nameCol1</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"col2\"><para>$nameCol2</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"COLSPEC0\"><para>$accepte</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"COLSPEC3\"><para>$nonAccepte</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"COLSPEC2\"><para>$nonTeste</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"</row>\n");
	fputs($file,"<row>\n");
	fputs($file,"<entry colname=\"col1\"><para><extref docnum=\"".$uneFr->ref."\" ednum=\"".$uneFr->ed."\"><ref-text>".$uneFr->ref."</ref-text>\n");
	fputs($file,"</extref></para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"col2\"><para>$deroulement</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry></entry>\n");
	fputs($file,"<entry colname=\"COLSPEC3\"></entry>\n");
	fputs($file,"<entry colname=\"COLSPEC2\"></entry>\n");
	fputs($file,"</row>\n");
	fputs($file,"</tbody>\n");
	fputs($file,"</tgroup>\n");
	fputs($file,"</table>\n");
	fputs($file,"</frame>\n");
}

//********************************************************************************************	
//corps pour la partie commentaires du PV
function corpsPvComment($file,$lgue)
{
	fputs($file,"<frame>\n");
	fputs($file,"<table>\n");
	if ($lgue == "B") {
		fputs($file,"<caption>COMMENTAIRES</caption>\n");
	} elseif ($lgue == "D") {
		fputs($file,"<caption>COMENTARIOS</caption>\n");
	} else {
		fputs($file,"<caption>COMMENTS</caption>\n");
	}
	
	fputs($file,"<tgroup cols=\"1\">\n");
	fputs($file,"<?PubTbl tgroup dispwid=\"7.82in\"?>\n");
	fputs($file,"<colspec colname=\"col1\" colwidth=\"1.00*\"/>\n");
	fputs($file,"<tbody>\n");
	fputs($file,"<row>\n");
	fputs($file,"<?PubTbl row rht=\"2.42in\"?>\n");
	fputs($file,"<entry colname=\"col1\"></entry>\n");
	fputs($file,"</row>\n");
	fputs($file,"</tbody>\n");
	fputs($file,"</tgroup>\n");
	fputs($file,"</table>\n");
	fputs($file,"</frame>\n");
}

//********************************************************************************************	
//corps pour la partie signatures du PV
function corpsPvSign($file,$lgue)
{
	if ($lgue == "B") {
		$repCli = "Repr&eacute;sentant du client"; //variable pour désigner représentant
							   //du client dans les différentes langues
		$repAlc = "Repr&eacute;sentant Alcatel"; //representant alcatel
		$name = "Nom"; //nom
		$date = "Date";	//date
		$signature = "Signature";
		$title = "SIGNATURES"; //nom du tableau
		
	} elseif ($lgue == "D") {
		$repCli = "Representante del cliente"; //variable pour désigner représentant
							    //du client dans les différentes langues
		$repAlc = "Representante del Alcatel"; //representant alcatel
		$name = "Appellido"; //nom
		$date = "Fecha";	//date
		$signature = "Firma";
		$title = "FIRMAS"; //nom du tableau
	} else {
		$repCli = "Customer representative"; //variable pour désigner représentant
							    //du client dans les différentes langues
		$repAlc = "Alcatel representative"; //representant alcatel
		$name = "Name"; //nom
		$date = "Date";	//date
		$signature = "Signature";
		$title = "SIGNATURES"; //nom du tableau
	}

	fputs($file,"<frame>\n");
	fputs($file,"<table>\n");
	fputs($file,"<caption>$title</caption>\n");
	fputs($file,"<tgroup cols=\"2\">\n");
	fputs($file,"<?PubTbl tgroup dispwid=\"7.47in\"?>\n");
	fputs($file,"<colspec colname=\"col1\" colwidth=\"0.97*\"/><colspec colname=\"col2\" colwidth=\"1.03*\"/>\n");
	fputs($file,"<tbody>\n");
	fputs($file,"<row>\n");
	fputs($file,"<entry colname=\"col1\"><para>$repCli</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"col2\"><para>$repAlc</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"</row>\n");
	fputs($file,"<row>\n");
	fputs($file,"<entry colname=\"col1\"><para>$name :</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"col2\"><para>$name :</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"</row>\n");
	fputs($file,"<row>\n");
	fputs($file,"<entry colname=\"col1\"><para>$date :</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"col2\"><para>$date :</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"</row>\n");
	fputs($file,"<row>\n");
	fputs($file,"<?PubTbl row rht=\"1.14in\"?>\n");
	fputs($file,"<entry colname=\"col1\"><para>$signature :</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"col2\"><para>$signature :</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"</row>\n");
	fputs($file,"</tbody>\n");
	fputs($file,"</tgroup>\n");
	fputs($file,"</table>\n");
	fputs($file,"</frame>\n");
}

//********************************************************************************************	
//fonction permettant la construction d'un chapitre du PV pour une FR
//on fournit la fiche de recette en paramètre
function corpsPv($file,$Fr,$lgue)
{

	// Suppression des carractères spéciaux dans le titre du FR
	$Fr->des = rempl_car_spe($Fr->des);
	// Ecrire l'entête du PV => le titre de la fiche dans le fichier 3BLxxxxxAAAAQZxxx
	fputs($file,"<h1><ht>".$Fr->des."</ht>\n");
	fputs($file,"<?Pub _newline?>\n");
	fputs($file,"<?Pub _newline?><?Pub Caret?>\n");
	//Ecrire le corps du PV  => Ref. de la fiche
	corpsPvTests($file,$Fr,$lgue);
	fputs($file,"<?Pub _newline?>\n");
	fputs($file,"<?Pub _newline?>\n");
	//Ecrire la commentaire du PV 
	corpsPvComment($file,$lgue);
	fputs($file,"<?Pub _newline?>\n");
	fputs($file,"<?Pub _newline?>\n");
	//Ecrire la signature du PV
	corpsPvSign($file,$lgue);	
	fputs($file,"</h1>\n");	
	
}
 
//********************************************************************************************	
//nouveau sous-chapitre du CR customisé
function sousChapitre($file,$nom)
{
	fputs($file,"<h2><ht>".$nom."</ht>\n");
}

//********************************************************************************************	
//nouvelle FR
function FR($file,$uneFR)
{
		
	$uneFR->des = rempl_car_spe($uneFR->des);

	fputs($file,"<row>\n");
	fputs($file,"<entry colname=\"col1\"><para><mnemoref></mnemoref>".$uneFR->des."</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"COLSPEC0\"><para><extref docnum=\"".$uneFR->ref."\"\n");
	fputs($file,"ednum=\"".$uneFR->ed."\">\n");
	fputs($file,"<ref-text>".$uneFR->ref."</ref-text></extref></para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"<entry colname=\"COLSPEC2\"><para>".$uneFR->ed."</para>\n");
	fputs($file,"</entry>\n");
	fputs($file,"</row>\n");
}

//********************************************************************************************	
//fin du corps d'un chapitre ou sous-chapitre du CR customisé
function finCorpsCr($file)
{
	fputs($file,"</tbody>\n");
	fputs($file,"</tgroup>\n");
	fputs($file,"</table>\n");
	fputs($file,"</frame>\n");
}

//********************************************************************************************	
//fin de chapitre
function finChapitre($file)
{
	fputs($file,"</h1>\n");
}

//********************************************************************************************	
//fin sous-chapitre
function finSousChapitre($file)
{
	fputs($file,"</h2>\n");
}	
	
//********************************************************************************************		
//fin du Cr customisé
function finCrCusto($file)
{
	fputs($file,"</docbody>\n");
	fputs($file,"</doccontent>\n");
	fputs($file,"</genericdoc>\n");
	fputs($file,"<?Pub *0000069712?>\n");
}

//********************************************************************************************	
//fin du PV
function finPv($file)
{
	fputs($file,"</docbody>\n");
	fputs($file,"</doccontent>\n");
	fputs($file,"</genericdoc>\n");
	fputs($file,"<?Pub *0000003462?>\n");
}

//********************************************************************************************	
//fonction principale: construction du cahier de recette customisé
function creerCrCusto($file_ctg)
{
	global $crRecupere;
	global $nbfiche_select; //JNG 
	
	entete($file_ctg);
	
	if ($crRecupere->lgueDoc == "A") {
		InfosDoc($file_ctg,"ACCEPTANCE BOOK");
	} elseif ($crRecupere->lgueDoc == "B") {
		InfosDoc($file_ctg,"CAHIER DE RECETTE");
	} else {
		InfosDoc($file_ctg,"PLIEGO DE RECEPCION");
	} 
	
	$chapitres = $crRecupere->chapitres;
	foreach($chapitres as $un_chapitre) {
		
		if (($un_chapitre->nbSousChapSelected() != 0) || ($un_chapitre->nbFrSelected() != 0)) {
			chapitre($file_ctg,$un_chapitre->nom);
								
			if ($un_chapitre->nbFrSelected() != 0) {
				corpsCr($file_ctg);
				$fr_chap = $un_chapitre->fr;
				foreach($fr_chap as $une_fr) {
						
					if ($une_fr->selected == 1) {
						FR($file_ctg,$une_fr);
					}	
				}
				finCorpsCr($file_ctg);
			}
			
			$sousChapitres = $un_chapitre->sousChap;
			foreach($sousChapitres as $un_sousChapitre) {
				
				if ($un_sousChapitre->nbFrSelected() != 0) {
					
					sousChapitre($file_ctg,$un_sousChapitre->nom);
					
					corpsCr($file_ctg);
					$fr_sousChap = $un_sousChapitre->fr;
					foreach($fr_sousChap as $une_fr) {
						if ($une_fr->selected == 1) {
							FR($file_ctg,$une_fr);
						}
					}
					finCorpsCr($file_ctg);					
					finSousChapitre($file_ctg);
				}
			}
			finChapitre($file_ctg);
		}
	}
	
	finCrCusto($file_ctg);

	
}

//********************************************************************************************	
//fonction principale permettant la construction du PV
function creerPv($file_pv)
{
	//un PV dispose d'un chapitre par FR
	global $crRecupere;
	
	entete($file_pv);
	
	if ($crRecupere->lgueDoc == "A") {
		InfosDoc($file_pv,"ACCEPTANCE REPORT");
	} elseif ($crRecupere->lgueDoc == "B") {
		InfosDoc($file_pv,"PV DE RECETTE");
	} else {
		InfosDoc($file_pv,"INFORME DE RECEPCION");
	} 
	
	//langue du cahier de recette customise A, B ou D
	$lgueCR = $crRecupere->lgueDoc;
	
	$chapitres = $crRecupere->chapitres;
	foreach($chapitres as $un_chapitre) {
		if (($un_chapitre->nbSousChapSelected() != 0) || ($un_chapitre->nbFrSelected() != 0)) {		
			if ($un_chapitre->nbFrSelected() != 0) {
				$fr_chap = $un_chapitre->fr;
				foreach($fr_chap as $une_fr) {	
					if ($une_fr->selected == 1) {
						corpsPv($file_pv,$une_fr,$lgueCR);
					}	
				}
			} 
			
			$sousChapitres = $un_chapitre->sousChap;
			foreach($sousChapitres as $un_sousChapitre) {
				
				if ($un_sousChapitre->nbFrSelected() != 0) {
					
					$fr_sousChap = $un_sousChapitre->fr;
					foreach($fr_sousChap as $une_fr) {
						if ($une_fr->selected == 1) {
							corpsPv($file_pv,$une_fr,$lgueCR);
						}
					}
				}
			}
		}
	}
		
	finPv($file_pv);	
}

//********************************************************************************************	
//fonction permettant la sérialisation des données du cahier de recette
function serialiser()
{
	global $urlSygafe;
	global $racineSyg, $racineSygTemp,$repUtil;
	global $nameSygTemp;
	global $crRecupere;
	$LessousChapitres = array();
	$LesFr = array();
	
	
	for ($i = -1;$i < count($crRecupere->chapitres) - 1;$i++) {
	
		if ($i < 8)
		$chap = "0".($i + 2);
		else 
		$chap = $i + 2;

		$chapitreTemp = &$crRecupere->chapitres[$i];
		for($j = -1;$j < count($chapitreTemp->sousChap) - 1; $j++) {
			$sousChapTemp = &$chapitreTemp->sousChap[$j];
			for ($k = -1;$k < count ($sousChapTemp->fr) - 1; $k++) {
				$frASerialiser = $sousChapTemp->fr[$k];	
				if ($j < 8)
					$schap = "0".($j + 2);
				else 
					$schap = $j + 2;
				//sérialisation de la fiche de recette
				$nomFic = "$racineSygTemp$nameSygTemp/$repUtil/step2/FR".$frASerialiser->ref.$chap.$schap.".syg";
				
				if ($frASerialiser->ref[18] != "")
					 $frASerialiser->ref = substr($frASerialiser->ref, 0, 17);
				/******************************** pour essai*/
				$fp = fopen($nomFic,"w");
				$fs = serialize($frASerialiser);

				fputs($fp,$fs);
				fclose($fp);	
				
				// 
				//cre_all_serialiser_file_syg ("$racineSygTemp$nameSygTemp/$repUtil/step2/fr_serialiser_syg","FR".$frASerialiser->ref.$chap.$schap.".syg",$fs);
				//
        /******************************** pour essai*/
			}  // fin du for k boucle sur les fiches du sous chapitre
			
			$sousChapASerialiser = $sousChapTemp;
			unset($sousChapASerialiser->fr);
			
			//sérialisation du sous-chapitre
			$nomFic = "$racineSygTemp$nameSygTemp/$repUtil/step2/SsChap".$sousChapASerialiser->num.".syg";
			/******************************** pour essai*/
      $fp = fopen($nomFic,"w");
			$fs = serialize($sousChapASerialiser);

			fputs($fp,$fs);
			fclose($fp);	
			
			// 
			//	cre_all_serialiser_file_syg ("$racineSygTemp$nameSygTemp/$repUtil/step2/sschap_serialiser_syg","SsChap".$sousChapASerialiser->num.".syg",$fs);
			//
			/******************************** pour essai*/
		} // fin du for j boucle sur les sous chapitres
		
		//recherche des fiches dans les chapitres
		for ($k = -1;$k < count ($chapitreTemp->fr) - 1; $k++) {
			$frASerialiser = $chapitreTemp->fr[$k];
			
			//sérialisation de la fiche de recette
			$nomFic = "$racineSygTemp$nameSygTemp/$repUtil/step2/FR".$frASerialiser->ref.$chap."00".".syg";
			
			if ($frASerialiser->ref[18] != "")
			$frASerialiser->ref = substr($frASerialiser->ref, 0, 17);

			/******************************** pour essai*/
			$fp = fopen($nomFic,"w");
			$fs = serialize($frASerialiser);
			/**/
			fputs($fp,$fs);
			fclose($fp);
			
		// 
		//		cre_all_serialiser_file_syg ("$racineSygTemp$nameSygTemp/$repUtil/step2/fr_serialiser_syg","FR".$frASerialiser->ref.$chap."00".".syg",$fs);
		//
  /******************************** pour essai*/
			
		}  // fin du for k boucle sur les fiches du chapitre
			
		$chapitreASerialiser = $chapitreTemp;

		unset($chapitreASerialiser->fr);
		unset($chapitreASerialiser->sousChap);
		
		//sérialisation du chapitre
		$nomFic = "$racineSygTemp$nameSygTemp/$repUtil/step2/Chap".$chapitreASerialiser->num.".syg";
		/******************************** pour essai*/
		$fp = fopen($nomFic,"w");
		$fs = serialize($chapitreASerialiser);
		/**/	

		fputs($fp,$fs);
		fclose($fp);	
	
		// 
		//		cre_all_serialiser_file_syg ("$racineSygTemp$nameSygTemp/$repUtil/step2/chap_serialiser_syg","Chap".$chapitreASerialiser->num.".syg",$fs);
		//
		/******************************** pour essai*/
	}  // fin du for de I  boucle sur les chapitres

	$Doc = $crRecupere;
	unset($Doc->chapitres);
	
	//sérialisation des infos du document
	$nomFic = "$racineSygTemp$nameSygTemp/$repUtil/step2/Document.syg";
	
	$fp = fopen($nomFic,"w");
	$fs = serialize($Doc);

	fputs($fp,$fs);
	fclose($fp);
	/**/
		
}  // fin de la function serialiser

//********************************************************************************************	
//fonction permettant la désérialisation des données du cahier de recette
function deserialiser()
{
	global $urlSygafe;
	global $crRecupere, $dataIsReady;
	global $racineSyg, $racineSygTemp,$repUtil;
	global $nameSygTemp;
	global $op; 

  
	
	$file_path = "$racineSygTemp$nameSygTemp/$repUtil/step2/Document.syg";
	$s = implode("", @file($file_path));
	$crRecupere = unserialize($s);
	 
	$chapitres = array();
	$sousChapitres = array();
	$lesFr = array();
	
	
	$fichiers = list_dir("$racineSygTemp$nameSygTemp/$repUtil/step2/");
	foreach ($fichiers as $fic) {
		if (ereg("FR.*",$fic)) {
			//Recherche les fichiers FR3BLxxxxxDAAAQPZZA0101.syg et désérialisation de la FR
			$file_path = "$racineSygTemp$nameSygTemp/$repUtil/step2/".$fic;
			$s = implode("", @file($file_path));
			
 			$Fr = unserialize($s);
 			$Fr->numParent = (string) $Fr->numParent;
 			$lesFr[count($lesFr) - 1] = $Fr;

		} elseif (ereg("SsChap.*",$fic)) {
			//Recherche les fichiers SsChapx.x.syg et désérialisation du sous-chapitre
			$file_path = "$racineSygTemp$nameSygTemp/$repUtil/step2/".$fic;
			$s = implode("", @file($file_path));
			
 			$sousChapitre = unserialize($s);
 			$sousChapitres[count($sousChapitres) - 1] = $sousChapitre;
 				
		} elseif (ereg("Chap.*",$fic)) {
			//Recherche les fichiers Chapx.syg et désérialisation du chapitre
			$file_path = "$racineSygTemp$nameSygTemp/$repUtil/step2/".$fic;
			$s = implode("", @file($file_path));
			
 			$un_chap = unserialize($s);
 			$chapitres[count($chapitres) - 1] = $un_chap;
 			 			 			 					//
		} elseif (ereg("Document.*",$fic)) {
			
			//désérialisation du document effectué précédemment
			
		}
	} // >>>>>>>>>>>>>  fin du foreach ancienne code
	
	// Mémoirisation des infos chap+souschap et des Fr dans la structure crRecupere
	
	for ($k = -1;$k < count($chapitres) - 1;$k++) {
		$crRecupere->ajouterChapitre($chapitres[$k]);
		$un_chap = &$crRecupere->chapitres[$k];	
		for ($j = -1;$j < count($sousChapitres) - 1;$j++) {
			if ($un_chap->num == $sousChapitres[$j]->numParent) {
				$un_chap->ajouterSousChap($sousChapitres[$j]);
				$nb = $un_chap->nbSousChap() - 1;
				$un_sousChap = &$un_chap->sousChap[$nb - 1];
					
				for ($i = -1;$i < count($lesFr) - 1;$i++) {
				  if (strcasecmp((string)$un_sousChap->num, (string) $lesFr[$i]->numParent) == 0) {
						$un_sousChap->ajouterFr($lesFr[$i]);
					}
				}
			}
		}
		for ($i = -1;$i < count($lesFr) - 1;$i++) {
			if ($un_chap->num == $lesFr[$i]->numParent) {
				$un_chap->ajouterFr($lesFr[$i]);
			}
		}
	}
}  // fin de la function deserialiser

//********************************************************************************************	
//création du formulaire pour la demande de saisie des informations concernant le 
//cahier de recette customisé et le PV
function saisieInfos($string_error)
{
	global $urlSygafe;
	global $crRecupere;
	global $racineSyg,$racineSygTemp,$repUtil;
	global $op; //il est nécessaire de connaître l'opération en cours ctgY ou ctgN
	global $titre_ctg, $pays_ctg, $ref_ctg, $edition_ctg, $mne_ctg; // ces infos sont utiles
		//dans le cas où l'utilisateur a remplit les champs pour le CR customisé et choisit
		//de génerer les PV - on peut alors récupérer les infos précédemment taper
	global $gen_arch;
	global $langue_browser;
	//JNG
	global $passwd, $login_user;
	global $nbfiche_select,$nbfiche_select_max; //nombre de fiches sélectionnées
	global $path_download;
	
	if ($langue_browser == "FR")
		{
			$stringTRUE="Oui";
			$stringFALSE="Non";
			$string1=" Veuillez saisir les informations demandées pour le cahier de recette customisé et le procès verbal";
			$string2="Pays (*):";
			$string3="Titre (*):";
			$string4="Référence (*):";
			$string5="Edition (**):";
			$string6="Référence (**):";
			$string7="Edition suivante(***):";
			$string8="Mnémonique :";
			$string9="(*) : champs à renseigner obligatoirement";
			$string10="(**) : champs non accessibles";
			$string11=" Fonction en restriction";
			$string12="Générer le procès verbal (PV)";
			$string13="Veuillez saisir désormais les informations pour le procès verbal de recette";
			$string14="Titre (**):";
			$string15="Référence (**):";
			$string16="Edition suivante(***):";
			$string17="Mnémonique :";
			$string18="(**) : champs non accessibles";
			$string19="Générer une archive (Zip) pour les fiches sélectionnées";
			$string20="(*) : Champs modifiables";
			$string70="(***) : nouvelle édition et si l'édition courante est validée, si non garder l'édition courante ";
			$string166="Edition (**):";
			$string21="  Valider  ";
			$string22="  (Ex: 3BL11111xxyy";
		}
	else
		{
			$stringTRUE="Yes";
			$stringFALSE="No";
			$string1="Please insert the data for the customized ACCEPTANCE BOOK and the acceptance form";
			$string2="Country (*):";
			$string3="Title (*):";
			$string4="Reference (*):";
			$string5="Edition (**):";
			$string6="Reference (**):";
			$string7="Next Edition (***):";
			$string8="Mnemonic:";
			$string9="(*): mandatory fields";
			$string10="(**): non accessibles fields";
			$string11=" Restricted function";
			$string12="Generate the acceptance form";
			$string13="Please insert the data for the acceptance form";
			$string14="Title (**):";
			$string15="Reference (**):";
			$string16="Next Edition (***):";
			$string17="Mnemonic:";
			$string18="(**): non accessibles fields"; 
			$string19="Create an archive (zip) for the selected acceptance sheets";
			$string20="(*) : Modifiable	fields";
			$string70="(***) : new edition and if the current version is validated, if not keep the current edition";
			$string166="Edition (**):";
			$string21="  Validate  ";
			$string22="  (Eg: 3BL11111xxyy";
		}
		
	echo("<HTML>\n");
	echo("<HEAD>\n");
	echo("</HEAD>\n");
	echo("<BODY BGCOLOR=\"#FFFFES\" ONLOAD=\"begin()\">\n");
	
	//affichage du message d'erreur fichier exist deja
	if ($string_error != ""){
				echo("<CENTER>\n");
				echo("<FONT COLOR=\"#FF0000\" SIZE=\"4\">\n");
				echo("$string_error\n");// "La ref. : $ref_ctg du cahier recette customisé demandée existe déjà"
				echo("</FONT>\n");
				echo("<HR>\n");
	}
	echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"4\">\n");
	echo("<B>$string1</B>\n");
	echo("</FONT>\n");
	echo("</CENTER>\n");
	
	//construction du formulaire
	echo("<FORM method=post action=sygMain.php?op=mkctg name=infosCtg>\n");
	
	//champ caché pour le répertoire utilisateur
	echo("<INPUT TYPE=\"hidden\" NAME=\"rep\" VALUE=\"".$repUtil."\">\n");
	
	// Le cahier de recette customisé
	
	//Remarque : on initialisera les valeurs des champs pour le CR customisé avec pays_ctg, ...
	//en effet par défaut ces variables ont pour valeur ""
	
	echo("<TABLE CELLPADDING=5>\n");
	
	//pays
	echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string2</FONT>\n");
	echo("<TD><INPUT TYPE=\"text\" SIZE=\"70\" NAME=\"pays_ctg\" VALUE=\"".$pays_ctg."\">\n");
	echo("<TR><TD COLSPAN=\"2\">\n");
		
	//titre
	echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string3</FONT>\n");
	echo("<TD><INPUT TYPE=\"text\" SIZE=\"70\" NAME=\"titre_ctg\" VALUE=\"".$titre_ctg."\">\n");
	echo("<TR><TD COLSPAN=\"2\">\n");
		
		//référence
		
		
		if ($ref_ctg == "")
		{
			//JNG 09_07_07
		  $ref_generic = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc ;
			$doc_langue = substr($ref_generic, -1);
			$norm_alcatel="QSZZ".$doc_langue;
			// fin JNG
			echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string4</FONT>\n");	//Référence (*): 
			$exemple=$string22.$norm_alcatel.")";
			echo("<TD><INPUT TYPE=\"text\"  maxLength=\"17\" SIZE=\"25\" NAME=\"ref_ctg\" VALUE=\"".$ref_ctg.$norm_alcatel."\"><FONT COLOR=\"#0000F\" SIZE=\"4\">$exemple</FONT>");
			
			echo("<TR><TD COLSPAN=\"2\">\n");
		
			//édition
			echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string5</FONT>\n");	//Edition
			echo("<TD><INPUT TYPE=\"text\" SIZE=\"4\" NAME=\"edition_ctg\" VALUE=\"".$edition_ctg."\">\n"); 
			echo("<TR><TD COLSPAN=\"2\">\n");
		}
		else
		{   // cas du modification $crRecupere->traitement_mode == MODIF
			//NGJ Vérifier si la fiche est en état validé
			// fin de la vérification
			echo("<TR><TD><FONT COLOR=\"#C00FF\" SIZE=\"4\">$string6</FONT>\n");	
			echo("<TD><INPUT TYPE=\"text\" maxLength=\"17\" READONLY = 1 SIZE=\"30\" NAME=\"ref_ctg\" VALUE=\"".$ref_ctg."\">\n"); 
			echo("<TR><TD COLSPAN=\"2\">\n");
			//édition
			echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string7</FONT>\n");
			echo("<TD><INPUT TYPE=\"text\"  SIZE=\"4\" NAME=\"edition_ctg\" VALUE=\"".$edition_ctg."\">\n");			
			echo("<TR><TD COLSPAN=\"2\">\n");
			
		}
		
		//mnémonique
		echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string8</FONT>\n");	
		echo("<TD><INPUT TYPE=\"text\" maxLength=\"10\" SIZE=\"10\" NAME=\"mne_ctg\" VALUE=\"".$mne_ctg."\">\n"); 
		echo("<TR><TD COLSPAN=\"2\">\n");
		
		echo("</TABLE>\n");
		
		echo("<BR><FONT COLOR=\"#00000\" SIZE=\"4\">$string9</FONT>\n");
		echo("<BR><FONT COLOR=\"#00000\" SIZE=\"4\">$string10</FONT>\n");
		
		if ($ref_ctg != "")
		{

				$edition_courant =  (int) $edition_ctg-1;
				if ($edition_courant == 1)
					$edition_courant = "01";
				elseif ($edition_courant == 2)
					$edition_courant = "02";
				elseif ($edition_courant == 3)
					$edition_courant = "03";
				elseif ($edition_courant == 4)
					$edition_courant = "04";
				elseif ($edition_courant == 5)
					$edition_courant = "05";
				elseif ($edition_courant == 6)
					$edition_courant = "06";
				elseif ($edition_courant == 7)
					$edition_courant = "07";
				elseif ($edition_courant == 8)
					$edition_courant = "08";
				elseif ($edition_courant == 9)
					$edition_courant = "09";
					
			$string700 = "$string70($edition_courant)";
			
			echo("<BR><FONT COLOR=\"#00000\" SIZE=\"4\">$string700</FONT /ALIGN=LEFT>\n");
		//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< NGJ 01_03_2007
		}
				
		echo("<BR><HR><BR>\n");
		
		//*****
		// génération ou non du zip avec les FRs du CR customise  
	
	//JNG trace
	/*  11_12_2006*/
	$nbfiche_select = nbficherecette_info();
	if ($nbfiche_select!= ""){
		$nameSygTemp="sygTemp";
		$path_download = $racineSygTemp.$nameSygTemp."/".$repUtil."/step3";
		
		if ($langue_browser == "FR")
		{
			if ($nbfiche_select <= $nbfiche_select_max){
				$temp_trait=$nbfiche_select/20;
				list($temp_trait,$reste) = explode(".",$temp_trait);
				$isodate = sprintf("%02d:%02d:%02d","00", $temp_trait, $reste);
				//$string11=" Vous avez sélectionné $nbfiche_select fiches à compresser, le traitement va prendre environ de $isodate minute(s).";
				$string11=" Vous avez sélectionné $nbfiche_select fiches à compresser, le traitement va prendre un certain temps.";
      }else{
				$string11=" Vous avez sélectionné $nbfiche_select fiches à compresser qui sont supérieures à $nbfiche_select_max fiches au maximum.";
			}
		}else{
			if ($nbfiche_select <= $nbfiche_select_max){
				$temp_trait=$nbfiche_select/20;
				list($temp_trait,$reste) = explode(".",$temp_trait);
				$isotemp = sprintf("%02d:%02d:%02d","00", $temp_trait, $reste);
				//$string11="You have selected $nbfiche_select acceptance sheets to be compressed, the treatment will take around $isotemp minute.";
				$string11="You have selected $nbfiche_select acceptance sheets to be compressed, the treatment will take a certain time.";
      }else{
				$string11="You have selected $nbfiche_select acceptance sheets to be compressed that are greater than $nbfiche_select_max  sheets at the maximum.";
			}
		}
	}	
		
		if ($gen_arch == "on")
		{
		
			echo("<CENTER>\n");
			echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
			echo("<INPUT TYPE=\"checkbox\" NAME=\"gen_arch\" CHECKED onClick=\"alert('$string11')\"> <B>$string19</B><BR><BR>\n");
			echo("</FONT>\n");
			echo("<HR>\n");
		}else{
		
			echo("<CENTER>\n");
			echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
			echo("<INPUT TYPE=\"checkbox\" NAME=\"gen_arch\" onClick=\"alert('$string11')\"><B>$string19</B><BR><BR>\n");
			echo("</FONT>\n");
			echo("<HR>\n");
			
		}
		
		
		//*****
		//radio-bouton pour la génération ou non du PV 	
		echo("<CENTER>\n");
		echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
		echo("<B>$string12</B><BR><BR>\n"); // Générer le procès verbal (PV)
		echo("</FONT>\n");
		echo("</CENTER>\n");
		// 
		
		if ($op == ctgY) {
		
			//echo("<INPUT type=\"radio\" name=\"genPv\" value=\"oui\" checked>$stringTRUE  
			//<INPUT type=\"radio\" name=\"genPv\" value=\"non\">$stringFALSE  
			//<INPUT type=\"button\" value=$string21 onClick=testerRadio(this.form.genPv)>\n");
			
			echo("<INPUT type=\"radio\" name=\"genPv\" value=\"oui\" checked>$stringTRUE  
			<INPUT type=\"radio\" name=\"genPv\" value=\"non\">$stringFALSE \n");
			
		} elseif ($op == ctgN) {
			echo("<INPUT type=\"radio\" name=\"genPv\" value=\"oui\">$stringTRUE  
			<INPUT type=\"radio\" name=\"genPv\" value=\"non\" checked>$stringFALSE  
			<INPUT type=\"button\" value=$string21 onClick=testerRadio(this.form.genPv)>\n");
		} else {	
			echo("<INPUT type=\"radio\" name=\"genPv\" value=\"oui\">$stringTRUE  
			<INPUT type=\"radio\" name=\"genPv\" value=\"non\" checked>$stringFALSE  
			<INPUT type=\"button\" value=$string21 onClick=testerRadio(this.form.genPv)>\n");
		}

		
		echo("<BR><HR><BR>\n");  // permettre de tracer le trait dans la page
		

		
	if ($op == ctgY) {

		if($ref_ctg != "")
		{
			echo("<CENTER>\n");
			echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
			echo("<B>$string13</B><BR><BR>\n");//$string13="Veuillez saisir désormais les informations pour le procès verbal de recette";
			echo("</FONT>\n");
			echo("</CENTER><BR>\n");
		
			echo("<TABLE CELLPADDING=5>\n");
		
		//titre
			echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string14</FONT>\n");//$string14="Titre (**):";
			echo("<TD><INPUT TYPE=\"text\"  SIZE=\"70\" NAME=\"titre_pv\" VALUE=\"".$titre_ctg."\">\n");
			echo("<TR><TD COLSPAN=\"2\">\n");
		
		//référence
		//déduction de la référence en prenant comme base celle attribuée au cahier de recette
		//générique
			$prefixPv = $ref_ctg[0].$ref_ctg[1].$ref_ctg[2];
			$nombrePv = "";
			for ($i = 3;$i < 8 ;$i++) $nombrePv .= $ref_ctg[$i];
			$variantPv = $ref_ctg[8].$ref_ctg[9];
			$versionPv = $ref_ctg[10].$ref_ctg[11];
			$typePv = "QZ";
			$zzPv = $ref_ctg[14].$ref_ctg[15];
			$languePv = $ref_ctg[16];
		
			$ref = $prefixPv.$nombrePv.$variantPv.$versionPv.$typePv.$zzPv.$languePv;
			
			echo("<TR><TD><FONT COLOR=\"#C00FF\" SIZE=\"4\">$string15</FONT>\n");//$string15="Référence (**):";
			echo("<TD><INPUT TYPE=\"text\" maxLength=\"17\" READONLY = 1 SIZE=\"25\" NAME=\"ref_pv\" VALUE=\"".$ref."\">\n"); 
			echo("<TR><TD COLSPAN=\"2\">\n");

			// fin JNG
			// appel à gediget pour vérifier le fichier PV le mode de traitement = modif
			if($crRecupere->traitement_mode == "MODIF")
			{
				if ($languePv == "A") {
					$desLgue = "EN";
				} elseif ($languePv == "B") {
					$desLgue = "FR";
				} elseif ($languePv == "D") {
					$desLgue = "SP";
				}
				$dir_courant=getcwd(); // sauvegarde le directory courant
				chdir("$racineSygTemp$nameSygTemp/$repUtil");// se mettre dans le répertoire d'utilisateur
				//$motdepasse = relance_gediget($passwd);
				$motdepasse = "\"$passwd\"";
				$identificationCIl = "-cn $login_user -pass $motdepasse";
				$ref_pv="";
				$edit_pv="01";
				$langue_pv="";
				/**/
				system("gediget ".$ref." ".$desLgue." $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/resCdes", $resCde);
				
        //Appeler gediget avec l'option -edition -title -status
				//system("gediget ".$ref." ".$desLgue." $identificationCIl -edition -title -status > $racineSygTemp$nameSygTemp/$repUtil/resCdes", $resCde);
				//$file_resCdes = "$racineSygTemp$nameSygTemp/$repUtil/resCdes";
        // si le retour est ok appel … la fonction $title_status_edition_ref=get_mess_gedi ("$racineSygTemp$nameSygTemp/$repUtil/resCdes");
        
        if($resCde == 0){
        /*
          $element_tab = explode( "><",$title_status_edition_ref);//recherche le car :
			    $nbcartab= count($element_tab); // mettre les ‚l‚ment de la ligne en tableau
			    if ($nbcartab > 1) {
			       $title_ref=$element_tab[0];
			       $status_ref=$element_tab[1];
			       $edition_ctg=$element_tab[2];
          }
        
        */
        
				//fichier trouvé alors récupérer son édition
				$files_root = list_dir($racineSygTemp.$nameSygTemp."/".$repUtil."/");
				foreach($files_root as $file) {
				//recherche le fichier de type 3BL48849TTAAQSAGB_03_FR.xzip
					if (ereg(".xzip$",$file)){
						$tab_car_spe = explode( "_",$file);
						list($ref_pv,$edit_pv ,$langue_pv) = explode ("_", $file);
						if (strcmp($ref_pv,$ref) == 0)
						{	
							system("rm -fR $racineSygTemp$nameSygTemp/$repUtil/$file");			
						}
					}
				}
				// fin de r‚cup‚ration de l'‚dition
				
				$edition_ctg =  (int) $edit_pv+1;
				if ($edition_ctg == 1)
					$edition_ctg = "01";
				elseif ($edition_ctg == 2)
					$edition_ctg = "02";
				elseif ($edition_ctg == 3)
					$edition_ctg = "03";
				elseif ($edition_ctg == 4)
					$edition_ctg = "04";
				elseif ($edition_ctg == 5)
					$edition_ctg = "05";
				elseif ($edition_ctg == 6)
					$edition_ctg = "06";
				elseif ($edition_ctg == 7)
					$edition_ctg = "07";
				elseif ($edition_ctg == 8)
					$edition_ctg = "08";
				elseif ($edition_ctg == 9)
					$edition_ctg = "09";
          
				}else{
				//fichier non trouvé alors if faut créer le fichier à l'édition 01
					$edition_ctg = "01";
				}
				$string700 = "$string70($edit_pv)";
				echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string16</FONT>\n");//$string16="Edition (*):";
				
				chdir("$dir_courant");//revient à la repertoire courant = = /home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe
					
			}else
				echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string166</FONT>\n");//$string16="Edition (*):";	
		//édition
			//echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string16</FONT>\n");//$string16="Edition (*):";	
			echo("<TD><INPUT TYPE=\"text\"  SIZE=\"4\" NAME=\"edition_pv\" VALUE=\"".$edition_ctg."\">\n"); 
			echo("<TR><TD COLSPAN=\"2\">\n");
		
		//mnémonique
			echo("<TR><TD><FONT COLOR=\"#0000F\" SIZE=\"4\">$string17</FONT>\n");	
			echo("<TD><INPUT TYPE=\"text\" maxLength=\"10\" SIZE=\"10\" NAME=\"mne_pv\" VALUE=\"\">\n"); 
			echo("<TR><TD COLSPAN=\"2\">\n");
			echo("</TABLE>\n");
		
		//ancre pour les infos du PV ou le bas de page (acces direct au bouton Valider
		
			echo("<A NAME=\"PV\"></A>\n");
			//echo("<BR><FONT ALIGN=LEFT COLOR=\"#00000\" SIZE=\"4\">$string20</FONT>\n");
			if($crRecupere->traitement_mode == "MODIF")
				echo("<BR><FONT COLOR=\"#00000\" SIZE=\"4\">$string700</FONT>\n");
		
			echo("<BR><FONT COLOR=\"#00000\" SIZE=\"4\">$string18</FONT /ALIGN=LEFT>\n");
			echo("<BR><HR><BR>\n");
		}
		
	} elseif ($op == ctgN) {
	
		//ancre pour les infos du PV ou le bas de page (acces direct au bouton Valider
		echo("<A NAME=\"PV\"></A>\n");
	}
	
	//fin de page
	echo("</FORM>\n");
	echo("</BODY>\n");
	echo("</HTML>\n");
	
}   // fin saisieInfos


//*******************************************************************************************
//dernier formulaire - Précise la fin de génération et crée des liens 
//pour télécharger les fichiers générés
function lastWindow()
{

	global $urlSygafe;
	global $racineSyg, $repUtil, $nameCrCusto;
	global $nameSygTemp,$racineSygTemp;
	global $genPv; //pour savoir si l'utilisateur a demandé
			//lagénération du PV
	global $namePv;
	global $cahier, $procvb, $link1, $rep1, $nameFile1, $nameFile2, $consign, $calePV, $cale;
	global $reponseC, $reponseP;
	
	global $gen_arch, $path_stp2, $pr;
	global $langue_browser, $nb_tt_zippee,$nbfiche_select_max ;
	
	if ($langue_browser == "FR")
		{
			$string1=" Téléchargement des fichiers générés";
			$string2="Cliquez sur les liens ci-dessous pour télécharger les fichiers";
			$string3="Ouvrir ou Exporter l'archive (ZIP) du cahier de recette customisé";
			$string4="Le cahier de recette customisé";
			$string5="Le procès verbal pour le cahier de recette customisé";
			$string6="Consignation dans GEDI";
			$string7="Ouvrir ou Exporter le fichier (ZIP) du cahier de recette customisé est indisponible : nombre max = $nbfiche_select_max et sélectionné = $nb_tt_zippee";
		}
	else
		{
			$string1="Download the generated files";
			$string2="Hereunder,click on the link to download the files";
			$string3="Open or Export the archive (ZIP) of the customised acceptance book";
			$string4="Customised acceptance book";
			$string5="The acceptance form of the customised acceptance book";
			$string6="GEDI consignment";
			$string7="Open or Export the archive (ZIP) of the customised acceptance book is unavailable: number max = $nbfiche_select_max and select = $nb_tt_zippee ";

		}
	
	echo("<HTML>\n");	
	echo("<HEAD>\n");
	echo("</HEAD>\n");
	echo("<BODY BGCOLOR=\"#FFFFES\" BACKGROUND=\"images/fond.jpg\" ONLOAD=closePopup()>\n");
	echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"5\">\n");
	echo("<B>$string1</B>\n");
	echo("</FONT>\n");
	echo("</CENTER><BR>\n");
	
	//construction du formulaire
	/**/
	echo("<FORM method=post action=step2/sygDownload.php?op=end name=lastForm>\n");
	//echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
	//echo("<B>$string2</B><BR>\n");
	//echo("</FONT>\n");
	/**/
	echo("<CENTER>\n");
	
	//Affiche le fichier zip à  ouvrir ou télécharger
	if ($gen_arch == "on")
	{
		if (($nb_tt_zippee <= $nbfiche_select_max) and ($nb_tt_zippee != 0)) {
			echo("<BR><HR><BR>\n");//affiche le trait-------------------------------------------------------------------sur la page
			if (($cahier == 0) and ($procvb == 0))
				$pr = $racineSygTemp.$nameSygTemp."/".$repUtil;
			$col = "CollectionFR.zip";
			echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
			echo("<B><CENTER>$string3</B><BR>\n");
			echo("<A HREF=".$urlSygafe."step2/sygDownload.php?rep=$pr&nameFile=$col onClick=submit();><FONT COLOR=\"#0000F\"><B>$col</B></FONT></A>\n");
			echo("<INPUT TYPE=\"hidden\" NAME=\"gen_arch\" VALUE=\"$gen_arch\">\n");
			echo("<INPUT TYPE=\"hidden\" NAME=\"pr\" VALUE=\"$pr\">\n");
			echo("</FONT>\n");
			echo("</CENTER>\n");
		}else{  // JNG nb de fiches est dépassé 60
			if ($nb_tt_zippee!=0){
				echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
				echo("<B><CENTER>$string7</B><BR>\n");
				echo("<A HREF=".$urlSygafe."step2/sygDownload.php?rep=$pr&nameFile=$col onClick=submit();><FONT COLOR=\"#0000F\"><B>$col</B></FONT></A>\n");
				echo("<INPUT TYPE=\"hidden\" NAME=\"gen_arch\" VALUE=\"$gen_arch\">\n");
				echo("<INPUT TYPE=\"hidden\" NAME=\"pr\" VALUE=\"$pr\">\n");
				echo("</FONT>\n");
				echo("</CENTER>\n");
			}
		}
	}
	echo("<BR><HR><BR>\n");//affiche le trait-------------------------------------------------------------------sur la page
	
	echo("<CENTER>\n");
	echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
	echo("<B>$string4</B><BR>\n");  // Le cahier de recette customisé
	echo("</FONT>\n");
	echo("</CENTER><BR>\n");
	
	//préciser chemin absolu ici pour le répertoire utilisateur
	if (($cahier == 0) and ($procvb == 0)){
		$link1 = $racineSygTemp.$nameSygTemp."/".$repUtil;
		echo("<INPUT TYPE=\"hidden\" NAME=\"rep1\" VALUE=\"$link1\">\n");
		$sf = $racineSygTemp.$nameSygTemp."/".$repUtil."/step2/".$nameCrCusto.".xzip";
		$sf1 = $sf;
		$nrchar = strlen ($sf);
		$sf1[$nrchar - 16] = "Q";
		$sf1[$nrchar - 15] = "Z";	
		echo("<INPUT TYPE=\"hidden\" NAME=\"cale\" VALUE=\"$sf\">\n");
		echo("<INPUT TYPE=\"hidden\" NAME=\"calePV\" VALUE=\"$sf1\">\n");
	}else{
		echo("<INPUT TYPE=\"hidden\" NAME=\"rep1\" VALUE=\"$rep1\">\n");
		echo("<INPUT TYPE=\"hidden\" NAME=\"cale\" VALUE=\"$cale\">\n");
		echo("<INPUT TYPE=\"hidden\" NAME=\"calePV\" VALUE=\"$calePV\">\n");
	}

	//*****
	echo("<INPUT TYPE=\"hidden\" NAME=\"cahier\" VALUE=\"$cahier\">\n");
	echo("<INPUT TYPE=\"hidden\" NAME=\"procvb\" VALUE=\"$procvb\">\n");
	echo("<INPUT TYPE=\"hidden\" NAME=\"nameFile1\" VALUE=\"$nameCrCusto\">\n");
	echo("<INPUT TYPE=\"hidden\" NAME=\"nameFile2\" VALUE=\"$namePv\">\n");
	echo("<INPUT TYPE=\"hidden\" NAME=\"consign\" VALUE=\"$consign\">\n");

	//*****	
	if ($cahier != 1){
		if ( $procvb == 1 )	$link1 = $rep1;
		$nameCrCusto = $nameCrCusto.".xzip";
		echo("<A HREF=".$urlSygafe."step2/sygDownload.php?rep=$link1&nameFile=$nameCrCusto onClick=submit();><FONT COLOR=\"#0000F\"><B>$nameCrCusto</B></FONT></A>\n");
		//echo("<BR><HR><BR>\n");  JNG pour enlever le trait
		echo("<BR><BR>\n");
	}else{
		$nameFile1 = $nameFile1.".xzip";
		echo("<A HREF=".$urlSygafe."step2/sygDownload.php?rep=$rep1&nameFile=$nameFile1 onClick=submit();><FONT COLOR=\"#0000F\"><B>$nameFile1</B></FONT></A>\n");
		//echo("<BR><HR><BR>\n");  JNG pour enlever le trait horizontal
		echo("<BR><BR>\n");
	}
	
	echo("<INPUT TYPE=\"hidden\" NAME=\"nomfic\" VALUE=\"$nameCrCusto\">\n");

	if ($cahier != 1){
		echo("<TR BGCOLOR=\"CCFFFF\"><TD><P><H4><INPUT TYPE=\"button\" onClick=consignC_js() NAME=numec VALUE= \"$string6\"></TD></TR>\n");
		echo("<BR><HR><BR>\n");
	}else {
		echo("<FONT COLOR=\"#FF0000\" SIZE=\"4\">\n");
		echo("<B><BLINK>\"$reponseC\"</BLINK></B><BR>\n");
		echo("<INPUT TYPE=\"hidden\" NAME=\"reponseC\" VALUE=\"$reponseC\">\n");
		echo("<BR><HR><BR>\n");
		//$string100 = "Retour à saisieinfos";
		//echo("<TR BGCOLOR=\"CCFFFF\"><TD><P><H4><INPUT type=\"button\" onClick=JavaScript:parent.contenuCR.validCtg_js()NAME=reconsign VALUE= \"$string100\"></TD></TR>\n");
		//echo("<BR><HR><BR>\n"); 
	}
	if ($genPv == oui) {
		echo("<CENTER>\n");
		echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
		echo("<B>$string5</B><BR>\n");
		echo("</FONT>\n");
		echo("</CENTER><BR>\n");
		echo("<INPUT TYPE=\"hidden\" NAME=\"genPv\" VALUE=\"oui\">\n");
		if ($procvb != 1){
			if ( $cahier == 1 )	$link1 = $rep1;
			$namePv = $namePv.".xzip";
			echo("<A HREF=".$urlSygafe."step2/sygDownload.php?rep=$link1&nameFile=$namePv onClick=submit();><FONT COLOR=\"#0000F\"><B>$namePv</B></FONT></A>\n");
			echo("<BR><BR>\n");
			echo("<TR BGCOLOR=\"CCFFFF\"><TD><P><H4><INPUT TYPE=\"button\" onClick=consignP_js() NAME=numep VALUE= \"$string6\"></TD></TR>\n");
			echo("<BR><HR><BR>\n");
		}else{
			echo("<CENTER>\n");
			$nameFile2 = $nameFile2.".xzip";
			echo("<A HREF=".$urlSygafe."step2/sygDownload.php?rep=$rep1&nameFile=$nameFile2 onClick=submit();><FONT COLOR=\"#0000F\"><B>$nameFile2</B></FONT></A>\n");
			echo("<BR><HR><BR>\n");
			
			echo("<FONT COLOR=\"#FF0000\" SIZE=\"4\">\n");
			echo("<B><BLINK>\"$reponseP\"</BLINK></B><BR>\n");
			echo("<INPUT TYPE=\"hidden\" NAME=\"reponseP\" VALUE=\"$reponseP\">\n");
			echo("</CENTER><BR>\n");
		}
	}

	/********lien pour relancer une session - a voir ultérieurement
	
	 //on doit igonorer /sygTemp de $repUtil pour ne garder que le nom d'utilisateur ou identifiant saisi
	$pos = strpos($repUtil,"/") + 1; //$repUtil est de la forme sygTemp/identifiant
	$identifiant = substr($repUtil,$pos);
	//echo("<CENTER><A HREF=\"JavaScript:reload(".$identifiant.")\"><FONT COLOR=\"#0000F\"><B>Obtenir un autre cahier de recette</B></FONT></A><BR>\n");	
	
	*********/
	
	//fin de page
	echo("</FORM>\n");
	echo("</BODY>\n");
	echo("</HTML>\n");
	
}  // fin lastwindows
	
//********************************************************************************************	

function compareFR ($uneFRD)
{
	global $comp3DR;
	$res = array();
	
	foreach ($comp3DR as $mat){
		
		if ($mat->referenceFR3DR == $uneFRD){	
				$res = array ($mat->reference3DR, $mat->name3DR);
				return  $res;
		}
	}
}

//********************************************************************************************	
//fonction pour acctualiser les information 3DR d'une FR
function info3DR()
{
	global $crRecupere;
	   
	for ($i = -1;($i < count ($crRecupere->chapitres) - 1);$i++) {
		$chapCourant = &$crRecupere->chapitres[$i];
		// Récupèrer les fiches directement sous chapitre
		for ($j = -1;$j < count($chapCourant->fr) - 1;$j++)	{
			$fr = &$chapCourant->fr[$j];
			list ($fr->tdr, $fr->ndr) = compareFR($fr->ref);
		}
		//Récupèrer les fiches dans sous chapitre 
		for ($l = -1;$l < count($chapCourant->sousChap) - 1;$l++){
			$ssChap = &$chapCourant->sousChap[$l];
			for ($k = -1;$k < count($ssChap->fr) - 1;$k++){ 
				$fr = &$ssChap->fr[$k];
				list ($fr->tdr, $fr->ndr) = compareFR($fr->ref);
			}
		}
		
	}
}

//********************************************************************************************	
//fonction pour mettre a jour la reference du CR
// traitement du CR en mode modification
function updateref()
{

	global $crRecupere, $ref_cr;
	global $traitement_mode;
	
	$ref_ctgp = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc ;
	

	
	
	if ( $ref_cr == $ref_ctgp )
	{
		//NGJ 05_01_2007
		// l'edition+1 seulement si le doc est valide 
		$crRecupere->traitement_mode="MODIF";
		//$crRecupere->editionDoc =  (int) $crRecupere->editionDoc;
		

		$crRecupere->editionDoc =  (int) $crRecupere->editionDoc+1;
		if ($crRecupere->editionDoc == 1)
			$crRecupere->editionDoc = "01";
		elseif ($crRecupere->editionDoc == 2)
			$crRecupere->editionDoc = "02";
		elseif ($crRecupere->editionDoc == 3)
		$crRecupere->editionDoc = "03";
		elseif ($crRecupere->editionDoc == 4)
		$crRecupere->editionDoc = "04";
		elseif ($crRecupere->editionDoc == 5)
		$crRecupere->editionDoc = "05";
		elseif ($crRecupere->editionDoc == 6)
		$crRecupere->editionDoc = "06";
		elseif ($crRecupere->editionDoc == 7)
		$crRecupere->editionDoc = "07";
		elseif ($crRecupere->editionDoc == 8)
		$crRecupere->editionDoc = "08";
		elseif ($crRecupere->editionDoc == 9)
		$crRecupere->editionDoc = "09";		
		
	}
	else
	{
		$crRecupere->traitement_mode="CREATE";
		$crRecupere->editionDoc = "01";
	}

}


//********************************************************************************************	
function consign()
{
	//consignation sur gedi
	global $rep, $referenceC, $edition, $langueC, $nomfic, $cale, $identificationCIl, $cahier, $procvb, $nameFile1, $nameFile2, $rep1;
	global $namePv, $nameCrCusto, $consign, $calePV;
	global $reponseC, $reponseP;
	//global $langue_browser;
	global $langue_browser, $passwd, $login_user;
	if ($langue_browser == "FR")
		{
			$string1="Le cahier de recette est consigné dans GEDI";
			$string2="Problème d'accès à GEDI";
			$string3="Le procès verbal est consigné dans GEDI";
			$string4="est en validation!";

		}
	else
		{
			$string1="The acceptance book is consigned in GEDI";
			$string2="Access GEDI problem";
			$string3="The acceptance form is consigned in GEDI";
			$string4="Problem of state of the edition!";
		}
	$nameCrCusto = $nameFile1;
	$namePv = $nameFile2;
	$link1 = $rep1;
	
	$referenceP = $referenceC;
	$referenceP[12] ="Q" ;
	$referenceP[13] ="Z" ;

	
	$cale1 = "_resltC-extract";
	$cale2 = "_resltC-consign";
	$cale3 = "_resltP-extract";
	$cale4 = "_resltP-consign";

	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 28_02_2007
		$nameCrExtract = $nameCrCusto.".xzip".$cale1;
		$rep_step2=$rep1."/step2/";
		list($ref_CrCusto,$lang_CrCusto,$ed_CrCusto)=explode("_",$nameCrCusto);
		$ed_CrCusto_ant = (int)$ed_CrCusto - 1;
		if ($ed_CrCusto_ant == 1)
			$ed_CrCusto_ant = "01";
		elseif ($ed_CrCusto_ant == 2)
			$ed_CrCusto_ant = "02";
		elseif ($ed_CrCusto_ant == 3)
			$ed_CrCusto_ant = "03";
		elseif ($ed_CrCusto_ant == 4)
			$ed_CrCusto_ant = "04";
		elseif ($ed_CrCusto_ant == 5)
			$ed_CrCusto_ant = "05";
		elseif ($ed_CrCusto_ant == 6)
			$ed_CrCusto_ant = "06";
		elseif ($ed_CrCusto_ant == 7)
			$ed_CrCusto_ant = "07";
		elseif ($ed_CrCusto_ant == 8)
			$ed_CrCusto_ant = "08";
		elseif ($ed_CrCusto_ant == 9)
			$ed_CrCusto_ant = "09";	

			//recherche pour la premiere fois si le fichier 3BLxxxxxTTAAQSAGB_FR_07.xzip._resltC-extract existe déja dans le $rep_step2 
			$extrac_deja = 0;
			$extracVP_deja = 0;
			$files_root = list_dir($rep_step2);
			foreach($files_root as $file) {			
				if (ereg("._resltC-extract$",$file)){
					$extrac_deja = 1;
				}
				if (ereg("._resltP-extract$",$file)){
					$extracVP_deja = 1;
				}
			}
		//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< NGJ 02_03_2007

	if ($cahier == 1 and $procvb != 1)
	{
		// consign cahier
		$consign = 1;
			
			//$motdepasse = relance_gediget($passwd);
			
			$motdepasse = "\"$passwd\"";
			
			$identificationCIl = "-cn $login_user -pass $motdepasse";	
			system("gediget ".$referenceC." ".$langueC." $identificationCIl  -extract  -nosrc > $cale.$cale1", $resltCE);
			
			  	/**/
			//	$string100 =  " fonctionsStep2.consign ligne 2023 : appel gediget identificationCIl = $identificationCIl ,code retour = $resltCE<br>";
			//	$string1000 =  "ref = $referenceC, langueC=$langueC, extrac_deja=$extrac_deja, cale.cale1 = $cale.$cale1 <br>";
			//	erori ($string100,$string1000);
	    //	echo " fonctionsStep2.consign ligne 2017  : ident_user=$ident_user <br>";
      //echo " fonctionsStep2.consign ligne 2018  : identificationCIl = $identificationCIl <br>";
    	//echo "ref = $referenceC, langueC=$langueC, extrac_deja=$extrac_deja, cale.cale1 = $cale.$cale1, resltCE=$resltCE <br>";			
        /**/	
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> NGJ 02_03_2007
			if (($resltCE == 1)or ($resltCE == 2)){
				if (($extrac_deja == 1) and ($resltCE == 2))
					$resltCE = 0;
				if ($resltCE == 1) 
					$resltCE = 0;
					
			}
			//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< NGJ 02_03_2007
		if ($resltCE == 0 ) 
		{
			system("gediput $cale  $identificationCIl -type CAB > $cale.$cale2", $resltCC); 
			
			  	/**/
				//$string100 =  " fonctionsStep2.consign ligne 1971 : appel gediput code retour = $resltCC<br>";
				//$string1000 =  "cale = $cale, cale2 = $cale.$cale2 <br>";
				//erori ($string100,$string1000);
	     //echo " fonctionsStep2.consign ligne 2030  : identificationCIl = $identificationCIl, gediput =>  cale.cale2  = $cale.$cale2  , resltCC = $resltCC <br>";
				/**/	
				         	
			if ($resltCC == 0 )
			{
				$reponseC = $string1;//Le cahier de recette est consigné dans GEDI
				//Création du fichier pdf
				//system("gediprint ".$referenceC." ".$langueC." $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/resCdes", $resltCC);
			 //if ($resltCC != 0 )
			 //{
			 	//>>>>>>>>>>>>>>>  NEW Code >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		  	// formatage du message d'erreur selon le code de retour du gediprint
				//$file_resCdes = "$racineSygTemp$nameSygTemp/$repUtil/resCdes";
				//$reponseC = edit_mess_gedi ($file_resCdes,$referenceC,$identificationCIl,$lgue);
		    //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		    //}
      
      
      }
			else{
			
			  //>>>>>>>>>>>>>>>  NEW Code >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		  	// formatage du message d'erreur selon le code de retour du gediget
				//$file_resCdes = $cale.$cale2;
				//$reponseC = edit_mess_gedi ($file_resCdes,$referenceC,$identificationCIl,$lgue);
		  //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
			
				if ($resltCC == 5 ){						
					if ($langue_browser == "FR")
						//$reponseC = "$ref_CrCusto édition $ed_CrCusto : consignation impossible, utiliser le bouton précédente pour modifier l'édition en $ed_CrCusto_ant !!!";
						$reponseC = "$ref_CrCusto Ed $ed_CrCusto : consignation interdite. Utiliser le bouton précédente du navigateur et modifier l'édition du document à $ed_CrCusto_ant ";
					else
						//$reponseC = "$ref_CrCusto of the edition: $ed_CrCusto to record forbidden, Use the button Previous for to modify the edition in $ed_CrCusto_ant !!!";
						$reponseC = "Document $ref_CrCusto Ed $ed_CrCusto : consignment forbidden, Use button Back of your browser and modify doc edition to $ed_CrCusto_ant ";
				}else
					$reponseC = $string2;
			}
		}else{
		  //>>>>>>>>>>>>>>>  NEW Code >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		  	// formatage du message d'erreur selon le code de retour du gediget
				//$file_resCdes = $cale.$cale1;
				//$reponseC = edit_mess_gedi ($file_resCdes,$referenceC,$identificationCIl,$lgue);
		  //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
			if ($langue_browser == "FR"){
				if ($resltCE == 1){								
					$string10 =  " Problème d'accès à GEDI, référence = $referenceC inexistante<br>";
				}elseif ($resltCE == 2){
					$string10 = " Problème d'accès à GEDI, référence = $referenceC est en undo modify<br>";
				}elseif ($resltCE == 3){
					$string10 = " Problème d'accès à GEDI, mot de passe incorrect <br>";
				}elseif ($resltCE == 6){
					$string10 =  " Problème d'accès à GEDI, référence = $referenceC longueur incorrecte <br>";
				}
			}else{
				if ($resltCE == 1){
								$string10 =  " Access GEDI problem having the reference = $referenceC not existent <br>";
				}elseif ($resltCE == 2){
								$string10 = " Access GEDI problem having the reference = $referenceC is undo modify <br>";
				}elseif ($resltCE == 3){
								$string10 = " GEDI problem cannot connect to the LDAP , Wrong password entered <br>";
				}elseif ($resltCE == 6){
								$string10 =  " Access GEDI problem having the reference = $referenceC incorrect length<br>";
				}
			}
			
				$reponseC = $string10;
		}
	}  //  ($cahier == 1 and $procvb != 1)
	
	elseif ($cahier != 1 and $procvb == 1)
	{
		// consign proc.Vb
		$consign = 2;

			$motdepasse = "\"$passwd\"";
			$identificationCIl = "-cn $login_user -pass $motdepasse";	
		//cahier = 0 et procvb = 1
		system("gediget ".$referenceP." ".$langueC." $identificationCIl  -extract  -nosrc > $cale$cale3", $resltPE);
		
				/**/
				//$string100 =  " fonctionsStep2.consign ligne 2096 : consign=$consign ,appel gediget identificationCIl=$identificationCIl,code retour = $resltPE<br>";
				//$string1000 =  " referenceC=$referenceP, cale.cale3 = $cale.$cale3 <br>";
				//erori($string100,$string1000);
				/**/
					//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> NGJ 02_03_2007
			if (($resltPE == 1)or ($resltPE == 2)){
				if (($extracVP_deja == 1) and($resltPE == 2))
					$resltPE = 0;
				if ($resltPE == 1)
					$resltPE = 0;
			}
			//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< NGJ 02_03_2007
		if ($resltPE == 0 )
		{
			
			system("gediput $calePV  $identificationCIl -type CAB > $cale$cale4", $resltPC);
			

			if ($resltPC == 0 )
			{
				$reponseP = $string3;
			}
			else{
				if ($resltCC == 5 ){						
					if ($langue_browser == "FR")
						//$reponseP = "$referenceP édition $ed_CrCusto : consignation impossible, utiliser le bouton précédente pour modifier l'édition en $ed_CrCusto_ant !!!";
					$reponseP = "$referenceP Ed $ed_CrCusto : consignation interdite. Utiliser le bouton précédente du navigateur et modifier l'édition du document à $ed_CrCusto_ant ";
					else
						//$reponseP = "$referenceP of the edition: $ed_CrCusto to record forbidden, Use the button Previous for to modify the edition in $ed_CrCusto_ant !!!";
					$reponseP = "Document $referenceP Ed $ed_CrCusto : consignment forbidden, Use button Back of your browser and modify doc edition to $ed_CrCusto_ant ";
				}else
				  $reponseP = $string2;
			}
		}else{
				$reponseC = $string2;
		}
	}  // ($cahier != 1 and $procvb == 1)
	elseif ($cahier == 1 and $procvb == 1)
	{
		// consign proc.Vb
			$motdepasse = "\"$passwd\"";
			$identificationCIl = "-cn $login_user -pass $motdepasse";	

		if ($consign == 1)
		{
			system("gediget ".$referenceP." ".$langueC." $identificationCIl  -extract  -nosrc > $cale$cale3", $resltPE);
			

			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> NGJ 02_03_2007
			if (($resltPE == 1)or ($resltPE == 2)){
				if (($resltPE == 2) and ($extracVP_deja == 1)) 
					$resltPE = 0;
				if ($resltPE == 1)
					$resltPE = 0;
			}
			//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< NGJ 02_03_2007
			if ($resltPE == 0 )
			{
				system("gediput $calePV  $identificationCIl -type CAB > $cale$cale4", $resltPC);
				

				if ($resltPC == 0 )
				{
					$reponseP = $string3;
				}
				else{
					if ($resltCC == 5 ){						
						if ($langue_browser == "FR")
							//$reponseP = "$referenceP édition $ed_CrCusto : consignation impossible, utiliser le bouton précédente pour modifier l'édition en $ed_CrCusto_ant !!!";
						$reponseP = "$referenceP Ed $ed_CrCusto : consignation interdite. Utiliser le bouton précédente du navigateur et modifier l'édition du document à $ed_CrCusto_ant ";
						else
							//$reponseP = "$referenceP of the edition: $ed_CrCusto to record forbidden, Use the button Previous for to modify the edition in $ed_CrCusto_ant !!!";
						$reponseP = "Document $referenceP Ed $ed_CrCusto : consignment forbidden, Use button Back of your browser and modify doc edition to $ed_CrCusto_ant ";
					}else
						$reponseP = $string2;
				}
			}else{
				if ($langue_browser == "FR"){
					if ($resltPE == 1){								
						$string10 =  " Problème d'accès à GEDI, référence = $referenceP inexistante<br>";
					}elseif ($resltPE == 2){
						$string10 = " Problème d'accès à GEDI, référence = $referenceP est en undo modify<br>";
					}elseif ($resltPE == 3){
						$string10 = " Problème d'accès à GEDI, mot de passe incorrect <br>";
					}elseif ($resltPE == 6){
						$string10 =  " Problème d'accès à GEDI, référence = $referenceP longueur incorrecte <br>";
					}
				}else{
					if ($resltPE == 1){
						$string10 =  " Access GEDI problem having the reference = $referenceP not existent <br>";
					}elseif ($resltPE == 2){
						$string10 = " Access GEDI problem having the reference = $referenceP is undo modify <br>";
					}elseif ($resltPE == 3){
						$string10 = " GEDI problem cannot connect to the LDAP , Wrong password entered <br>";
					}elseif ($resltPE == 6){
						$string10 =  " Access GEDI problem having the reference = $referenceP incorrect length<br>";
					}
				}
				$reponseC = $string10;
			}
		}
		else
		{
		
			// consign cahier
			system("gediget ".$referenceC." ".$langueC." $identificationCIl  -extract  -nosrc > $cale$cale1", $resltCE);
			

			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> NGJ 02_03_2007
			if (($resltCE == 1)or ($resltCE == 2)){
				if (($resltCE == 2)and($extrac_deja == 1))
					$resltCE = 0;
				if ($resltCE == 1) 
					$resltCE = 0;
			}
			//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< NGJ 02_03_2007
			if ($resltCE == 0 )
			{
				system("gediput $cale  $identificationCIl -type CAB > $cale.$cale2", $resltCC);
				
				if ($resltCC == 0 )
				{
					$reponseC = $string1;
				}
				else{
					if ($resltCC == 5 ){
						if ($langue_browser == "FR")
							//$reponseC = "$ref_CrCusto édition $ed_CrCusto : consignation impossible, utiliser le bouton précédente pour modifier l'édition en $ed_CrCusto_ant !!!";
						$reponseC = "$ref_CrCusto Ed $ed_CrCusto : consignation interdite. Utiliser le bouton précédente du navigateur et modifier l'édition du document à $ed_CrCusto_ant ";
						else
						//	$reponseC = "$ref_CrCusto of the edition: $ed_CrCusto to record forbidden, Use the button Previous for to modify the edition in $ed_CrCusto_ant !!!";
						$reponseC = "Document $ref_CrCusto Ed $ed_CrCusto : consignment forbidden, Use button Back of your browser and modify doc edition to $ed_CrCusto_ant ";
					}
				}
			}else{
				if ($langue_browser == "FR"){
					if ($resltCE == 1){	
						$string10 =  " Problème d'accès à GEDI, référence = $referenceC inexistante<br>";		
					}elseif ($resltCE == 2){
								$string10 = " Problème d'accès à GEDI, référence = $referenceC est en undo modify<br>";
					}elseif ($resltCE == 3){
								$string10 = " Problème d'accès à GEDI, mot de passe incorrect <br>";
					}elseif ($resltCE == 6){
								$string10 =  " Problème d'accès à GEDI, référence = $referenceC longueur incorrecte <br>";
					}
				}else{
					if ($resltCE == 1){
								$string10 =  " Access GEDI problem having the reference = $referenceC not existent <br>";
					}elseif ($resltCE == 2){
								$string10 = " Access GEDI problem having the reference = $referenceC is undo modify <br>";
					}elseif ($resltCE == 3){
								$string10 = " GEDI problem cannot connect to the LDAP , Wrong password entered <br>";
					}elseif ($resltCE == 6){
								$string10 =  " Access GEDI problem having the reference = $referenceC incorrect length <br>";
					}
				}
				$reponseC = $string10;
			}
		}
		
	}  //  ($cahier == 1 and $procvb == 1)
	
}		// fin consign


//********************************************************************************************	
//cette fonction construit une archive avec tout les FR en format pdf d'une CR customise
function saveFR()
{
	global $identificationCIl, $crRecupere, $gen_arch;
	global $path_download;
	global $langue_browser;
	global $passwd,$login_user;//JNG 
	
	//JNG trace
			  	/* 
				$string1000 =  " fonctionsStep2.saveFR ligne 2464 : debut saveFR<br>";
				$string1001 =  "identificationCIl = $identificationCIl <br>";
				erori ($string1000,$string1001);
				*/	
	// fin trace
	
	if ($langue_browser == "FR")
	{
			$string1="Compte rendu du téléchargement";
			$string2="Numéro total de fiches recette existantes dans le cahier recette customisé  = ";
			$string3="NR FR";
			$string4="REFERENCE FR";
			$string5="EDITION FR";
			$string6="Impossible de creer le fichier body";
			$string7="Impossible de creer le fichier body";
	}else{
			$string1="Report of Download";
			$string2="Total number of acceptance sheets existing in the customised acceptance book = ";
			$string3="FR NUMBER";
			$string4="FR REFERENCE";
			$string5="FR EDITION";
			$string6="Can not create body file";
			$string7="Can not create head file";
	}

	$res = $path_download."/res";
	$nl ="   \n";
	$color = "CCFFFF";
	$ii = 0;
	chdir($path_download);
	$path_stp2 = $path_download;
	$nr = strlen ($path_stp2);
	$path_stp2 [$nr-1] = "2";
	$fic = "CollectionFR.zip";
	
	if (!$file_title = fopen("$path_download/body","w")) 
	 	die("$string6");
	for ($i = -1;$i < count ($crRecupere->chapitres) - 1;$i++) 
	{
		$chapCourant = &$crRecupere->chapitres[$i];

		for ($j = -1;$j < count($chapCourant->fr) - 1;$j++) 
		{
			$fr = &$chapCourant->fr[$j];
			if ($fr->selected == 1)
			{
				$ii++;
				system("gediget ".$fr->ref." ".$fr->lgue." ".$fr->ed." $identificationCIl -pdf > $res", $res);	
				
				
				if ($res == 0)
				{
					$color = "CCFFFF";
					$status ="OK ";
				}else{
					//JNG trace
					//$motdepasse = relance_gediget($passwd);
					$motdepasse = "\"$passwd\"";
					$identificationCIl = "-cn $login_user -pass $motdepasse";
					//echo " fonctionStep2 ligne 1843 relance_gediget: iden = $identificationCIl <br>";
					system("gediget ".$fr->ref." ".$fr->lgue." ".$fr->ed." $identificationCIl -pdf > $res", $res);	
					
					//fin trace			
					
					if ($res != 0){
						$color = "EFED8F";
						$status ="NOK";
					}
				}
				/*	fwrite($file_title, $ii."   ");
				fwrite($file_title, "          ".$status."  ");
				fwrite($file_title, "     ".$fr->ref."  ");
				fwrite($file_title, $fr->ed."  ");
				fwrite($file_title, $nl);			
				*/
				fwrite($file_title,"<TR BGCOLOR=\"$color\" ><TD>$ii</TD><TD>$status</TD><TD><CENTER>$fr->ref</CENTER></TD><TD><CENTER>$fr->ed</CENTER></TD></TR>\n");	
			}
		} // fin de la boucle for de j

		for ($l = -1;$l < count($chapCourant->sousChap) - 1;$l++)
		{
			$ssChap = &$chapCourant->sousChap[$l];
			for ($k = -1;$k < count($ssChap->fr) - 1;$k++)
			{
				$fr = &$ssChap->fr[$k];
				if ($fr->selected == 1){
					$ii++;
					system("gediget ".$fr->ref." ".$fr->lgue." ".$fr->ed." $identificationCIl -pdf > $res", $res);
				
					
					if ($res == 0){
						$color = "CCFFFF";
						$status ="OK ";
					}else{
							//JNG trace
						//$motdepasse = relance_gediget($passwd);
						$motdepasse = "\"$passwd\"";
						$identificationCIl = "-cn $login_user -pass $motdepasse";
						//echo " fonctionStep2 ligne 1873 relance_gediget: iden = $identificationCIl <br>";
						system("gediget ".$fr->ref." ".$fr->lgue." ".$fr->ed." $identificationCIl -pdf > $res", $res);	
						
						//fin trace		
							
						if ($res != 0){
							$color = "EFED8F";
							$status ="NOK";
						}
						//$color = "EFED8F";
						//$status ="NOK";
					}
					/*	fwrite($file_title, $ii."   ");
					fwrite($file_title, "          ".$status."  ");
					fwrite($file_title, "     ".$fr->ref."  ");
					fwrite($file_title, $fr->ed."  ");
					fwrite($file_title, $nl);
					*/
					fwrite($file_title,"<TR BGCOLOR=\"$color\" ><TD>$ii</TD><TD>$status</TD><TD><CENTER>$fr->ref</CENTER></TD><TD><CENTER>$fr->ed</CENTER></TD></TR>\n");
				}
			}  // fin de la boucle for k
		}  // fin de la boucle for l
	} // fin de la boucle for de i
	fwrite($file_title,"</TBODY>\n");
	fwrite($file_title,"</TABLE></CENTER>\n");
	fclose ($file_title);
	if (!$file_head = fopen("$path_download/head","w")) 
	 	die("$string7");
	else 
		{	

		fwrite($file_head, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"6\"><B>$string1</B><BR><BR></FONT></CENTER><HR><BR>\n");
		fwrite($file_head, "<FONT COLOR=\"000FFF\" SIZE=\"5\"> <CENTER>	<B>$string2 $ii</B></CENTER><BR><BR></FONT></CENTER><BR>\n");
		fwrite($file_head, "<CENTER><TABLE BORDER = 1 BORDERCOLOR = 000FFF  FONT COLOR=\"#000FF\">\n");
		fwrite($file_head, "<THEAD><B><TR BGCOLOR=\"FFF000\" ><TD><CENTER>$string3</CENTER></TD><TD><CENTER>STATUS</CENTER></TD><TD><CENTER>$string4</CENTER></TD><TD><CENTER>$string5</CENTER></TD></TR></B></THEAD>\n");
		fwrite($file_head, "<TBODY>\n");
		fwrite($file_head, "\n");
		fclose ($file_head);
		}
	$chDirOk = chdir($path_download);
 	if (!$chDirOk) {
 			die("pb chdir script step3!!");
 	}else{
		if ($ii  > 0){	
				system("cat head body > readme.html");
				system("zip $fic *pdf* readme.html > $res");
				system("cp CollectionFR.zip $path_stp2");
				$erase = $path_download."/*"; //*//
				system("rm -f $erase ");
		}else{
				$gen_arch = "off";
				//echo("<INPUT TYPE=\"hidden\" NAME=\"gen_arch\" VALUE=\"".$gen_arch."\">\n");
		}
				
	}
}   // fin function saveFR

//********************************************************************************************	
//cette fonction crée un select dynamiquement en y insérant les chapitre
function creerSelectChap($tab)
{
	
	global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1="------Sélectionner un chapitre------";
			$string2="Chapitre :    ";
		}
	else
		{
			$string1="------Select a chapter-------------";
			$string2="Chapter:     ";
		}

	echo("<TR><CENTER><TD COLSPAN=2><FONT COLOR=\"#0000F\" SIZE=\"4\">$string2</FONT></TD>\n");
	echo("<TD><SELECT name=selection_chap onChange=afficheSC_js()>\n");
	
	//le premier element du tableau des systemes (systemes ou systemesRetenus) se trouve
	// à l'index 0 du tableau après la désérialisation
	
	echo("<OPTION value=-1 SELECTED>$string1\n");
	
	//on affiche tous les systèmes retenus du tableau correspondant.
	//si aucune recherche n'a été effectuée, l'ensemble des systèmes sont affichés
	
	while(list($key, $val) = each($tab)) {
	
		//la valeur attribuée à selection_sys est l'indice du tableau $systemesRetenus
			
			echo("<OPTION value=$key>".$val->nom."\n");
	}	
	echo("</SELECT></TD></TR>\n");
}


//********************************************************************************************	
//cette fonction crée un select dynamiquement en y insérant les souschapitre
function creerSelectSChap($tab)
{
	global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1="------Sélectionner un sous chapitre------";
			$string2="   Sous chapitre :";
		}
	else
		{
			$string1="------Select a subchapter-------------";
			$string2="   Subchapter:  ";
			
		}
	
	echo("<TR><TD COLSPAN=2><FONT COLOR=\"#0000F\" SIZE=\"4\">$string2</FONT></TD>\n");
	echo("<TD><SELECT name=selection_schap onChange=afficheSSC_js()>\n");
	
	//le premier element du tableau des systemes (systemes ou systemesRetenus) se trouve
	// à l'index 0 du tableau après la désérialisation
	
	echo("<OPTION value=-1 SELECTED>$string1\n");
	
	//on affiche tous les systèmes retenus du tableau correspondant.
	//si aucune recherche n'a été effectuée, l'ensemble des systèmes sont affichés
	
	while(list($key, $val) = each($tab)) {
	
		//la valeur attribuée à selection_sys est l'indice du tableau $systemesRetenus
			
			echo("<OPTION value=$key>".$val->nom."\n");
	}	
	echo("</SELECT></TD></TR>\n");
}

//********************************************************************************************	
// fonction qui fournise form name
function formname()
{

	global $rep, $repUtil;

//echo("<B> Ajout FR </B>\n");
echo("<FORM method=\"post\" action=\"sygMain.php\" name=\"formname\">\n");
echo("<INPUT TYPE=\"hidden\" NAME=\"rep\" VALUE=\"$repUtil\">\n");

}


//********************************************************************************************	
//fonction qui ajout une FR dans une  CR customise dans une Chapitre 
function ajoutFrChap($chap)
{
global $selection_chap;
global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1=" INTRODUIRE LA REFERENCE DE LA FICHE ";
			$string2=" Ajouter une fiche de recette ";
		}
	else
		{
			$string1="REFERENCE OF THE ACCEPTANCE SHEET";
			$string2=" Insert acceptance sheet ";
		}
echo("<BR> <CENTER><B>$string1</B>\n");
echo("<TD><INPUT TYPE=\"text\" onkeypress=\"return noenter()\" maxLength=\"17\" SIZE=\"25\" NAME=\"ref_fr\" VALUE=\"\"></TD>\n");
//echo("<TD><INPUT TYPE=\"text\" onkeypress=\"return enter(document.formname.ajFrc)\" maxLength=\"17\" SIZE=\"20\" NAME=\"ref_fr\" VALUE=\"\"></TD>\n");
echo("<BR>\n");
echo("<INPUT type=button name=ajFrc value=\"$string2\" onClick=ajFrc_js()></CENTER>\n");
echo("<INPUT TYPE=\"hidden\" name=\"chap\" value=\"$selection_chap\">\n");

} 


//********************************************************************************************	
//fonction qui ajout une FR dans une  CR customise dans une sousChapitre 
function ajoutFrSChap($chap)
{

global $selection_schap, $selection_chap;
global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1=" INTRODUIRE LA REFERENCE DE LA FICHE ";
			$string2=" Ajouter une fiche de recette ";
		}
	else
		{
			$string1="REFERENCE OF THE ACCEPTANCE SHEET";
			$string2=" Insert acceptance sheet ";
		}

echo("<BR><B> <CENTER>$string1</B>\n");
echo("<TD><INPUT TYPE=\"text\" onkeypress=\"return noenter()\" maxLength=\"17\" SIZE=\"25\" NAME=\"ref_fr\" VALUE=\"\"></TD>\n");
//echo("<TD><INPUT TYPE=\"text\" onkeypress=\"return enter(document.formname.ajFrsc)\" maxLength=\"17\" SIZE=\"20\" NAME=\"ref_fr\" VALUE=\"\"></TD>\n");
echo("<BR><INPUT type=button name=ajFrsc value=\"$string2\" onClick=ajFrsc_js()></CENTER>\n");


//echo("<TD><INPUT type=button name=ajFrsc value=\"Ajout FR\" onClick=ajFrsc_js()></TD></TR>\n");
//echo("<INPUT TYPE=\"hidden\" name=\"targetsc\" value=\"$selection_schap\">\n");
//echo("<INPUT TYPE=\"hidden\" name=\"chap\" value=\"$selection_chap\">\n");

}

//********************************************************************************************	
//fonction qui permet de savoir si une CR contien une FR avec la reference ref_fr
function frpresent($ref_fr, $chap, $schap)
{


	global $crRecupere;
	
	if ($schap == "FALSE")
	{	
		if ($chap == -1)
		$chap = -1;
		$chapCourant = &$crRecupere->chapitres[$chap];

		for ($j = -1;$j < count($chapCourant->fr) - 1;$j++) 
		{
			$fr = &$chapCourant->fr[$j];

			if ($fr->ref == $ref_fr)
			return 1;

		}	
	}
	else
	{	
		if ($chap == -1)
		$chap = -1;
		if ($schap == -1)
		$schap = -1;
		$ssChap = &$crRecupere->chapitres[$chap]->sousChap[$schap];
		for ($k = -1;$k < count($ssChap->fr) - 1;$k++) 
			{
				$fr = &$ssChap->fr[$k];

				if ($fr->ref == $ref_fr)
				return 1;
			}		
	}
	
	return 0;

}

//********************************************************************************************	
//fonction qui permet de serialiser une fiche
function serialiser_fiche($reference, $location)

{
	global $racineSyg, $racineSygTemp,$repUtil;
	global $nameSygTemp;

	
	//sérialisation de la fiche de recette
		$nomFic = "$racineSygTemp$nameSygTemp/$repUtil/step2/FR".$reference->ref.$location.".syg";

		
		$fp = fopen($nomFic,"w");
		$fs = serialize($reference);
		
		// 
//cre_all_serialiser_file_syg ("$racineSygTemp$nameSygTemp/$repUtil/step2/fr_serialiser_syg","FR".$reference->ref.$location.".syg",$fs);
		//
    /******************************** pour essai*/

		fputs($fp,$fs);
			fclose($fp);	
			
}

//********************************************************************************************	
// fonction qui affiche le resultat du comparaison entre deux CR
function affichDiff()
{
	global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1=" DECONNEXION ";
			$string2="Comparaison entre deux CAHIERS de RECETTE ";
			$string3="Référence de la fiche de recette";
			$string4="Titre de la fiche de recette";
			$string5="[édition]";
			
		}
	else
		{
			$string1="LOGOUT";
			$string2="COMPARE TWO ACCEPTANCE BOOKS";
			$string3="Acceptance sheet Reference";
			$string4="Acceptance sheet Title";
			$string5="[edition]";
		}

	echo("<BODY  ONLOAD=closePopup()>\n");
//	echo("<INPUT type=button name=home  value=\" HOME \" onClick=\"parent.window.location='$urlSygafe'\">\n");
	echo("<FORM method=\"post\" action=\"sygMain.php\" name=\"del\">\n");
	echo("<INPUT type=button name=hom  value=\" HOME \" onClick=\"home_js('del')\">\n");
	echo("<INPUT type=button name=dec  value=\"$string1\" onClick=\"deconnex_js('del')\">\n");	
	echo("<CENTER>\n");
	echo("<FONT COLOR=\"#0000FF\" SIZE=\"6\">\n");
	echo("<B>SYGAFE</B>\n");
	echo("<BR><FONT COLOR=\"#FF0000\" SIZE=\"5\">\n");
	echo("<B>$string2</B><BR>\n");
	echo("</CENTER><HR>\n");
	
	global $cr1, $cr2;
	
	
	$delta = new Delta();

	for ($i = -1;$i < count ($cr1->chapitres) - 1;$i++) 
	{
		$chapCourant = &$cr1->chapitres[$i];
		for ($j = -1;$j < count($chapCourant->fr) - 1;$j++) 
		{
			$fr = &$chapCourant->fr[$j];
			$frDelta = new frDelta ($fr->ref, $fr->des, $fr->ed, 0);
			$frDelta->cr2 = searchCr2($fr->ref);
			$delta->ajouterFr($frDelta);
		}
		for ($l = -1;$l < count($chapCourant->sousChap) - 1;$l++) 
		{
			$ssChap = &$chapCourant->sousChap[$l];
			for ($k = -1;$k < count($ssChap->fr) - 1;$k++) 
			{
				$fr = &$ssChap->fr[$k];
				$frDelta = new frDelta ($fr->ref, $fr->des, $fr->ed, 0);
				$frDelta->cr2 = searchCr2($fr->ref);
				$delta->ajouterFr($frDelta);
			}
		}
	}

	for ($i = -1;$i < count ($cr2->chapitres) - 1;$i++) 
	{
		$chapCourant = &$cr2->chapitres[$i];
		for ($j = -1;$j < count($chapCourant->fr) - 1;$j++) 
		{
			$fr = &$chapCourant->fr[$j];
			if ($fr->selected != 2)
			{
				$fr->selected = 2;
				$frDelta = new frDelta ($fr->ref, $fr->des,"", $fr->ed);
				$delta->ajouterFr($frDelta);
			}
		}
		for ($l = -1;$l < count($chapCourant->sousChap) - 1;$l++) 
		{
			$ssChap = &$chapCourant->sousChap[$l];
			for ($k = -1;$k < count($ssChap->fr) - 1;$k++) 
			{
				$fr = &$ssChap->fr[$k];
				if ($fr->selected != 2)
				{
					$fr->selected = 2;
					$frDelta = new frDelta ($fr->ref, $fr->des, "", $fr->ed);
					$delta->ajouterFr($frDelta);
				}
			}
		}
	}

	$color = "CCFFFF";// bleu ciel par defaut
	
	
	$ref1 = $cr1->prefixDoc .$cr1->numberDoc.$cr1->variantDoc.$cr1->versionDoc.$cr1->typeDoc.$cr1->zzDoc.$cr1->lgueDoc ;
	$ref2 = $cr2->prefixDoc .$cr2->numberDoc.$cr2->variantDoc.$cr2->versionDoc.$cr2->typeDoc.$cr2->zzDoc.$cr2->lgueDoc ;
	//echo("<BODY BGCOLOR=\"CCFFFF\" FONT COLOR=\"#00F0F\" SIZE=\"5\">");
	echo("<TABLE BORDER = 1 BORDERCOLOR = 000FFF  FONT COLOR=\"#000FF\">");
	echo("<THEAD><B><TR BGCOLOR=\"00FF00\" ><TD><CENTER>$string3</CENTER></TD><TD><CENTER>$string4</CENTER></TD><TD><CENTER>$cr1->titleDoc<BR>$ref1<BR>$string5</CENTER></TD><TD><CENTER>$cr2->titleDoc<BR>$ref2<BR>$string5</CENTER></TD></TR></B></THEAD>");
	echo("<TBODY>");
	foreach($delta->frDelta as $uneFr) 
	{
		if ($uneFr->cr1 == "")
			$uneFr->cr1 = "--";
		if ($uneFr->cr2 == "")
			$uneFr->cr2 = "--";
	
		if ($uneFr->cr1 != $uneFr->cr2)
		{	
			if (($uneFr->cr1 != "--" and $uneFr->cr2 != "--"))
				$color = "00FF00"; // couleur vert 
			else
			//$color = "F06D71";
				$color = "DD1111"; // 
		}
		else
			$color = "CCFFFF";// bleu ciel
		if ($color == "DD1111")
			$font = "FFFFFF"; // blanc
		else
			$font = "000000"; // noir
		echo("<TR BGCOLOR=\"$color\"> <TD><A HREF=\"JavaScript:parent.contenuCR.lireFiche()\" onClick=\"window.open('http://gedi.ln.cit.alcatel.fr/surespeed/gedi/S_docLink?F_scope=publi?&F_reference=$uneFr->ref','new','width=800,height=500,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')\">$uneFr->ref</A></TD><TD><font color = $font>$uneFr->nom</TD><TD><font color = $font><CENTER>$uneFr->cr1</CENTER></TD><TD><font color = $font><CENTER>$uneFr->cr2</CENTER></font></TD></TR>\n");
	}
	echo("</TBODY>");
	echo("</TABLE>");
	echo("<BR><CENTER>");
	echo("<FONT COLOR=\"#000000\" SIZE=\"3\">\n");

	echo("<INPUT type=button name=home  value=\" HOME \" onClick=\"home_js('del')\">\n");
	echo("<INPUT type=button name=dec  value=\"$string1\" onClick=\"deconnex_js('del')\">\n");	
	
}  // fin de la  function affichDiff()


//********************************************************************************************	
// fonction qui cherche une FR dans le 2eme CR compare 
// si FR existe le champ selected = 2 et le fonction return l'edition FR dans le CR2
function searchCr2($ref)
{
	global $cr2;
	$reference = substr($ref, 0, 8);  

	
	for ($i = -1;$i < count ($cr2->chapitres) - 1;$i++) 
	{
		$chapCourant = &$cr2->chapitres[$i];

		for ($j = -1;$j < count($chapCourant->fr) - 1;$j++) 
		{
			$fr = &$chapCourant->fr[$j];
			$reftmp =  substr($fr->ref, 0, 8);
			if ($reference == $reftmp)
			{
				$fr->selected = 2;
				return $fr->ed;
			}
		}
		for ($l = -1;$l < count($chapCourant->sousChap) - 1;$l++) 
		{
			$ssChap = &$chapCourant->sousChap[$l];

			for ($k = -1;$k < count($ssChap->fr) - 1;$k++) 
			{
				$fr = &$ssChap->fr[$k];
				$reftmp =  substr($fr->ref, 0, 8);
				if ($reference == $reftmp)
				{
					$fr->selected = 2;
					return $fr->ed;
				}
			}
		}
	}

}

//********************************************************************************************	
//fonction pour mettre ajour les edition des FRs en deux cas:
// 1. le CR custo est construite a partir du CR generique
// 2. le CR custo est construite a partir du reference GEDI d'un autre CR deja existente
// si 1. les edition des FRs peuvent etre <= edition change
// si 2.  les edition des FRs peuvent etre totalment change
function edts ()
{
	global $mat;
	global $crRecupere, $fiche_insert;
	$ii = 1;
	
	$fchap = substr($fiche_insert, 17,2);
	$fschap = substr($fiche_insert, 19,2);
	
		// ngj trace
	//		echo " fonctionsStep2.edts ligne 3020 : fiche_chap = $fchap, fiche_schap =  $fschap, fiche_insert=$fiche_insert<br>";
	//

	
	$fchap = $fchap - 2;
	$fschap = $fschap - 2;
	
	$fiche_insert = substr($fiche_insert, 0,17);
	


	if ($crRecupere->editionDoc == "01")
	$ednul = 1;
	
	for ($i = -1;$i < count ($crRecupere->chapitres) - 1;$i++) 
{
	$chapCourant = &$crRecupere->chapitres[$i];

for ($j = -1;$j < count($chapCourant->fr) - 1;$j++) 
{
	$fr = &$chapCourant->fr[$j];
	if ($ednul == 1)
	{
		if ( $mat [$ii] > $fr->edtmp )
		$mat [$ii] = $fr->edtmp;
	}
	
	$fr->ed = $mat[$ii];
	if (($fr->ref == $fiche_insert)  and ($i == $fchap))
	{
	$fr->ed = "";
	$ii--;
	
	}
	$ii++;
}
for ($l = -1;$l < count($chapCourant->sousChap) - 1;$l++) 
{
	$ssChap = &$chapCourant->sousChap[$l];

for ($k = -1;$k < count($ssChap->fr) - 1;$k++) 
{
	$fr = &$ssChap->fr[$k];
	if ($ednul == 1)
	{
		if ( $mat [$ii] > $fr->edtmp )
		$mat [$ii] = $fr->edtmp;
	}
	
	$fr->ed = $mat[$ii];
	
	if (($fr->ref == $fiche_insert) and ($l == $fschap) and ($i == $fchap))
	{
	$fr->ed = "";
	$ii--;
	
	}
	$ii++;
}
}
}
	

}

//********************************************************************************************	

function newpv()
{
	global $racineSyg, $racineSygTemp,$nameSygTemp, $repUtil, $rep, $nameCR, $ref_cr, $file, $crRecupere, $namePv;
	
	echo("<FORM method=\"post\" action=\"sygMain.php\" name=\"newpv\">\n");
	
	
	$repUtil = $rep;

	//on récupère dans l'url le nom du CR recupéré
	$file = "$racineSygTemp$nameSygTemp/$repUtil/step1/$nameCR";
	
	lireFichierP();
	
	$crRecupere->typeDoc = "QZ";
	$namePv = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc ;

	if ($crRecupere->lgueDoc == "A") {
	 		$desLgue = "EN";
	 	} elseif ($crRecupere->lgueDoc == "B") {
	 		$desLgue = "FR";
	 	} elseif ($crRecupere->lgueDoc == "D") {
	 		$desLgue = "SP";
	 	}

	

	$namePv = $namePv."_".$desLgue."_".$crRecupere->editionDoc;

		 	
		 	if (!$Pv = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/$namePv.xml","w")) {
		 		die("Can not create the acceptance form");
		 	} else { 	
		 	
		 		creerPv($Pv);	
		 		fclose($Pv);
		 	}
		 	
			 chdir("$racineSygTemp$nameSygTemp/$repUtil/step2");
			//on compresse le PV avec la commande zip 
			system("zip $namePv.xzip $namePv.xml >> $racineSygTemp$nameSygTemp/$repUtil/resCdes");
			//on supprime le fichier xml pour libérer de la place sur le disque
			system("rm -f $racineSygTemp$nameSygTemp/$repUtil/step2/$namePv.xml"); 
	
	
	pv_window();
	
	
	

}


//********************************************************************************************	
function pv_window()
{
	
	global $repUtil, $namePv, $racineSyg, $racineSygTemp,$nameSygTemp, $urlSygafe, $urlASTRID, $pv, $identificationCIl, $ph;
	global $langue_browser;
	global $passwd,$login_user;//JNG 

	if ($langue_browser == "FR")
		{
			$string1=" Téléchargement des fichiers générés";
			$string2="Cliquez sur les liens ci dessous pour télécharger vos fichiers";
			$string3="Le procès verbal du cahier de recette customisé";
			$string4="Consignation dans GEDI";
			$string5="Le procès verbal dans GEDI";
			$string6="Problème d'accès à GEDI";
			$string7="DECONNEXION";
			$string8="Problème de la connexion de GEDI, Veuillez relancer GEDI et le fermer par le bouton Logout sur la barre de menu";
		}
	else
		{
			$string1="Download generated files";
			$string2="Hereunder, click on the link to download the files";
			$string3="The acceptance form of the customised acceptance book";
			$string4="GEDI Consignement ";
			$string5="The acceptance form is consigned in GEDI";
			$string6="GEDI Access Problem";
			$string7="LOGOUT";
			$string8="Problem with the connection to GEDI, Please relaunch GEDI and then close it using the button Logout on the menu bar";
		}
	$path = "$racineSygTemp$nameSygTemp/$repUtil";
	
	echo("<HTML>\n");	
	echo("<HEAD>\n");
	echo("</HEAD>\n");
	echo("<BODY BGCOLOR=\"#FFFFES\" BACKGROUND=\"images/fond.jpg\" ONLOAD=closePopup()>\n");
	echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"5\">\n");
	echo("<B>$string1</B>\n");
	echo("</FONT>\n");
	echo("</CENTER><BR>\n");
	
	//construction du formulaire
	echo("<FORM method=post action=step2/sygDownload.php?op=end name=lastForm>\n");
	
	echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
	echo("<B>$string2</B><BR>\n");
	echo("</FONT>\n");
	echo("<CENTER>\n");
	echo("<BR><HR><BR>\n");
	
	echo("<CENTER>\n");
	echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
	echo("<B>$string3</B><BR>\n");
	echo("</FONT>\n");
	echo("</CENTER><BR>\n");
	
	
	echo("<FONT COLOR=\"#000000\" SIZE=\"4\">\n");
	echo("<B><CENTER></B><BR>\n");
	if ($pv != 1){
		echo("<A HREF=".$urlSygafe."step2/sygDownload.php?rep=$path&nameFile=$namePv.xzip onClick=submit();><FONT COLOR=\"#0000F\"><B>$namePv.xzip</B></FONT></A>\n");
		echo("<BR><BR>\n");
	}else{
		echo("<A HREF=".$urlSygafe."step2/sygDownload.php?rep=$ph&nameFile=$namePv.xzip onClick=submit();><FONT COLOR=\"#0000F\"><B>$namePv.xzip</B></FONT></A>\n");
		echo("<BR><BR>\n");
	}
	
	if ($pv != 1){
		echo("<TR BGCOLOR=\"CCFFFF\"><TD><P><H4><INPUT TYPE=\"button\" onClick=pvcs_js() NAME=numec VALUE= \"$string4\"></TD></TR>\n");
		echo("<BR><HR><BR>\n");
		$pv = 1;
		echo("<INPUT TYPE=\"hidden\" name=\"path\" value=\"$path\">\n");
		echo("<INPUT TYPE=\"hidden\" name=\"namePv\" value=\"$namePv\">\n");
		echo("<INPUT TYPE=\"hidden\" name=\"pv\" value=\"$pv\">\n");
		echo("<INPUT TYPE=\"hidden\" name=\"ph\" value=\"$path\">\n");
	}else{

		$referenceP = substr($namePv,0,17);
		$langueC = substr($namePv,18,2);
		$ref_cr = $namePv;
		$ref_cr[12] = "Q";
		$ref_cr[13] = "S";
		//$ref_cr[13] = "Z";
		//$cale1 = "/step2/$referenceP.extr"; old code
		$cale1 = "/step2/$ref_cr.xzip._resltP-extract";
		$cale2 = "/step2/$ref_cr.xzip._resltP-consign";
		
		$namePv[12] = "Q";
		$namePv[13] = "Z";
		
					// consign proc.Vb
													//JNG trace
			//$motdepasse = relance_gediget($passwd);
			$motdepasse = "\"$passwd\"";
			$identificationCIl = "-cn $login_user -pass $motdepasse";	
			
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
					//cahier = 0 et procvb = 1
		//system("gediget ".$referenceP." ".$langueC." $identificationCIl  -extract  -nosrc > $cale$cale3", $resltPE);
		//	echo " fonctionsStep2.consign ligne 1866  :  gediget => referenceP=$referenceP, cale.cale3 = $cale.$cale3, resltPE =  $resltPE   <br>";
		//$referenceP = 3BL48849TTAAQZZZA
		//$cale = /home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe_V2.05/sygTemp/nguyen6/step2/3BL48849TTAAQSZZA_EN_01.xzip
		//$cale3 = _resltP-extract
		
		//system("gediput $calePV  $identificationCIl -type CAB > $cale$cale4", $resltPC);			
		//	echo " fonctionsStep2.consign ligne 1869  : gediput =>  calePV=$calePV,cale.cale4  = $cale.$cale4  , resltPC = $resltPC <br>";
		//$calePV = /home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe_V2.05/sygTemp/nguyen6/step2/3BL48849TTAAQZZZA_EN_01.xzip
		//$cale = /home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe_V2.05/sygTemp/nguyen6/step2/3BL48849TTAAQSZZA_EN_01.xzip
		//$cale4 = _resltP-consign
			
			//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

				//fin trace

		system("gediget ".$referenceP." ".$langueC." $identificationCIl  -extract  -nosrc > $ph$cale1", $resltPE);
		
		system("gediput $ph/step2/$namePv.xzip  $identificationCIl -type CAB > $ph$cale2", $resltPC);
		

		if ($resltPC == 0 )
			$reponseP = "$string5";
		else
			$reponseP = "$string6";
			
	
	
		echo("<FONT COLOR=\"#FF0000\" SIZE=\"4\">\n");
		echo("<B><BLINK>$reponseP</BLINK></B><BR>\n");
		echo("<BR><HR><BR>\n");
	}
	echo("</CENTER>\n");
	echo("<TABLE width=100%>\n");

	//image pour le mail
	echo("<TD width=50% align=left><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	echo("<A HREF=\"mailto:jean-francois.treyssat@alcatel.fr\"><IMG SRC=\"images/mail.jpg\" name=\"mailImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	echo("</FONT></TD>\n");
	
	//création du lien vers le fichier d'aide de SYGAFE
	echo("<TD width=50% align=left><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	

	echo("<A HREF=".$urlSygafe."A_propos.htm TARGET=\"_blank\"><IMG SRC=\"images/paper.gif\" name=\"aboutImg\" width=\"89\" height=\"89\" border=\"0\"></A>\n");
	echo("<TD width=80% align=right><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	echo("<INPUT type=button name=home  value=\" HOME \" onClick=\"home_js('newpv');\">\n");
	echo("</TABLE>\n");
	echo("<CENTER><BR><BR>\n");
	//echo("<INPUT type=button name=homea  value=\"$string7\" onClick=\"deconnex_js('newpv')\">\n"); ancienne code
	echo("<INPUT type=button name=dec  value=\"$string7\" onClick=\"deconnex_js('newpv')\">\n");	
	echo("</TABLE>\n");
	
} //fin fonction pv_windows

//********************************************************************************************	
//cette fonction construit une archive avec tout les FR en format pdf d'une CR customise
//fichier CollectionFR.zip
function cre_zip_CR()
{
	global $identificationCIl, $crRecupere, $gen_arch;
	global $path_download;
	global $langue_browser, $edition_ctg;
	global $passwd,$login_user;//JNG 
	

	
	
	if ($langue_browser == "FR")
	{
			$string1="Compte rendu du téléchargement";
			$string2="Nombre de fichiers (ZIP) par chapitre : ";
			$string3="NR";
			$string4="Fichier zip par chapitre";
			$string5="Titre du chapitre ";
			$string6="Impossible de créer le fichier body";
			$string7="Impossible de créer le fichier head";
	}else{
			$string1="Report of the downloading";
			$string2="Total number of (ZIP) files by chapter: ";
			$string3="FR NUMBER";
			$string4="File zip by chapter";
			$string5="Title of the chapter";
			$string6="Can not create body file";
			$string7="Can not create head file";
	}
	
	//$ref_ctgp = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc.' .'.$crRecupere->editionDoctmp ;
	$ref_ctgp = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc.' .'.$edition_ctg ;
	$titre_doc_RC =	$crRecupere->titleDoc;
	


	$res = $path_download."/res";
	$nl ="   \n";
	$color = "CCFFFF";
	$ii = 0;

	$path_stp2 = $path_download;
	$nr = strlen ($path_stp2);
	$path_stp2 [$nr-1] = "2";
	$fic = "CollectionFR.zip";
	chdir($path_download);
	
	
		//JNG trace
			  	/* 
				$string1000 =  " fonctionsStep2.cre_zip_CR ligne 3351 : debut cre_zip_CR<br>";
				$string1001 =  "fichier zip = $fic <br>";
				erori ($string1000,$string1001);
				*/	
	// fin trace

  //
  				$file_zip_step2 = $path_stp2."/*.zip";		
				system("cp $file_zip_step2 $path_download");
				sleep(1);

				system("rm -f $file_zip_step2 ");
				sleep(1);
  //	

	if (!$file_title = fopen("$path_download/body","w")) {
	 	die("$string6");
	}else{
	$needle=".zip";
	//JNG 
	if ($handle = opendir('.')) {
	$needle=".zip";
	$i=0;
    while (false !== ($file = readdir($handle))) {

      if ($file != "." && $file != ".." ) {
			$type = strstr($file, ".zip");
			if ($type == $needle) {
				list($file_name,$type) = explode(".",$file);
				list($name,$num_chp) = explode("_",$file_name);
				if ($num_chp==1){
					$num_chp=-1;
				}else{
					$num_chp=$num_chp-2;
				}
				$chapCourant = &$crRecupere->chapitres[$num_chp];
				$nom_chapitre=$chapCourant->nom;

				$i++;
				//ecrire le fichier zip du chapitre
				fwrite($file_title,"<TR BGCOLOR=\"$color\" ><TD><CENTER>$file</CENTER></TD><TD><CENTER>$nom_chapitre</CENTER></TD></TR>\n");
				
			}
        }
    }
    closedir($handle);
	}
	//
	

	fwrite($file_title,"</TBODY>\n");
	fwrite($file_title,"</TABLE></CENTER>\n");
	fclose ($file_title);
	
	if (!$file_head = fopen("$path_download/head","w")) 
	 	die("$string7");
	else 
	{	
		fwrite($file_head, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"6\"><B>$string1</B><BR></FONT></CENTER><BR>\n");
		fwrite($file_head, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"4\"><B>$titre_doc_RC</B><BR></FONT></CENTER><BR>\n");
		fwrite($file_head, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"4\"><B>$ref_ctgp</B><BR><BR></FONT></CENTER><HR><BR>\n");

		fwrite($file_head, "<FONT COLOR=\"000FFF\" SIZE=\"5\"> <CENTER>	<B>$string2 $i</B></CENTER><BR><BR></FONT></CENTER><BR>\n");
		fwrite($file_head, "<CENTER><TABLE BORDER = 1 BORDERCOLOR = 000FFF  FONT COLOR=\"#000FF\">\n");
		fwrite($file_head, "<THEAD><B><TR BGCOLOR=\"FFF000\" ><TD><CENTER>$string4</CENTER></TD><TD><CENTER>$string5</CENTER></TD></TR></B></THEAD>\n");
		fwrite($file_head, "<TBODY>\n");
		fwrite($file_head, "\n");
		fclose ($file_head);
	} // fin d'écriture dans le fichier head 
	
	$chDirOk = chdir($path_download);
 	if (!$chDirOk) {
 			die("pb chdir script step3!!");
 	}else{
		if ($i  > 0){	
				//echo " Creation du fichier zip global : $fic <br>";
				system("cat head body > readme.html");
				system("zip $fic *zip* readme.html > $res");
				system("cp $fic $path_stp2");
				$erase = $path_download."/*"; 				
				//nettoyer le rep step3 mettre en commentaire provisoirement			
				system("rm -f $erase ");
				
				
		//}else{
	//			$gen_arch = "off";
		}	
	}
	}// fin d'écriture dans body
	
}	//fin function cre_f_zip



//********************************************************************************************
//
//
//********************************************************************************************
//**********************************************
function cre_liste_file_zip($path_Value,$file_name)
{

	$filename = $path_Value."/liste_file_compress";
  $somecontent = $path_Value."/".$file_name."\n"; //Ajout de chaîne dans le fichier

  // Assurons nous que le fichier est accessible en écriture
    if (!$handle = fopen($filename, 'a')) {
         die("Can not create liste_file_zip file.");
    }else{

      if (is_writable($filename)) {
        if (fwrite($handle, $somecontent) === FALSE) {
            die("Can not write liste_file_zip file.");
      }  
          //  echo "L'écriture de ($somecontent) dans le fichier ($filename) a réussi";
      } else {
            echo "Le fichier $filename n'est pas accessible en écriture.";
      }
      fclose($handle);
    }

}// fin fonction

//**********************************************
function del_liste_file_zip($path_Value)
{

	$filename = $path_Value."/liste_file_compress";
	
  // Ouvre en lecture seule, et place le pointeur de fichier au début du fichier
    if (!$handle = fopen($filename, 'r')) {
         die("Can not create liste_file_compress file.");
    }else{
      
	     $ref_rech=0;
	      while (!feof ($handle)) 
		      {
			       $erase_file = fgets($handle, 4096);
			       //supprimer les fichiers zip sous chapitre
             system("rm -f $erase_file "); 		       
          }
      fclose($handle);
      //sleep(1);
      //supprimer le fichier contient la liste des fichiers zip
      system("rm -f $filename ");
    }
    
}// fin fonction

//********************************************************************************************	
//cette fonction construit une archive avec tout les FR en format pdf d'une CR customise
//fichier CollectionFR_2.zip 
function cre_f_zip_chap()
{
	global $identificationCIl, $crRecupere, $gen_arch;
	global $path_download;
	global $langue_browser, $edition_ctg;
	global $passwd,$login_user,$nb_tt_zippee,$nbfiche_select_max;//JNG 
	
	
		//$ref_ctgp = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc.' .'.$crRecupere->editionDoctmp ;
		$ref_ctgp = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc.' .'.$edition_ctg ;
		$titre_doc_RC =	$crRecupere->titleDoc;


		
	if ($langue_browser == "FR")
	{
			$string1="Compte rendu du téléchargement";
			$string2=" fiches de recette sélectionnées pour le chapitre ";
      $string2_bis=" fiches de recette sélectionnées pour le sous chapitre ";		
			$string3=" NR ";
			$string4=" REFERENCE ";
			$string5=" EDITION ";
			$string6="Impossible de creer le fichier body";
			$string7="Impossible de creer le fichier head";
			//new 28_01_2008
			$string2_ter="Nombre de fichiers (ZIP) par sous chapitre : ";
			//$string3="NR";
			$string4_bis="Fichier zip par sous chapitre";
			$string5_bis="Titre du sous chapitre ";
			$string8="Chapitre ";
			$string9="Nombre de fiches de recette sélectionnées pour le chapitre : ";
			// fin new
	}else{
			$string1="Report of the downloading";
			$string2="selected acceptance sheets for the chapter ";
			$string2_bis="selected acceptance sheets for the under chapter ";
			$string3="NUMBER";
			$string4="REFERENCE";
			$string5="EDITION";
			$string6="Can not create body file";
			$string7="Can not create head file";
			
			//new 28_01_2008
			$string2_ter="Total number of (ZIP) files by under chapter: ";
			//$string3="FR NUMBER";
			$string4_bis="File zip by under chapter";
			$string5_bis="Title of the under chapter";
			$string8="Chapter ";
			$string9="Total number of selected acceptance sheets for the chapter: ";
			//fin new
	}

	$res = $path_download."/res";
	$nl ="   \n";
	$color = "CCFFFF";
	$ii = 0;
	chdir($path_download);
	$path_stp3 = $path_download;
	$path_stp2 = $path_stp3;
	$nr = strlen ($path_stp2);
	$path_stp2 [$nr-1] = "2";
	$num_chap=0;
	$nb_tt_zippee=0;
	$nb_fr_tt_selec=0;

	$nb_fr_tt_selec = nbficherecette_info();
		
	//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	if (($nb_fr_tt_selec!=0)and($nb_fr_tt_selec<=$nbfiche_select_max)) {
	
		for ($i = -1;$i < count ($crRecupere->chapitres) - 1;$i++) 
		{
			// récupérer le chapitre courant
			$chapCourant = &$crRecupere->chapitres[$i];
			// création du numéro de chapitre courant
			$fic_select=0;
			
			if ($chapCourant->selected ==1){

				if (!$file_title = fopen("$path_stp3/body","w")) {
					die("$string6");
				}else{
					
					$ii=0;
					$nb_ftt_sschap=0;
					$nb_ftt_chap=0;
					$fr_chap_selec=0;
					$fr_sschap_selec=0;
					$num_chap = $chapCourant->num;
					$chap_name=$chapCourant->nom;
					$fic_chap_zip = "CollectionFR_"."$num_chap".".zip";
				
					// Recherche des fiches dans les chapitres sélectionnées
					//***********************************************************
					//
					if (!$file_title_chap = fopen("$path_stp3/bodychap","w")) {
	               die("$string6");
				  }else{
					
					
					for ($j = -1;$j < count($chapCourant->fr) - 1;$j++) 
					{	

					 //récupérer la fiche sélectionnée
						$fr = &$chapCourant->fr[$j];
					
						if ($fr->selected == 1)
						{
							$ii++;
						  $fr_chap_selec++;
						  
							$fic_ref_zip="CollectionFR_"."$chapCourant->num".".0.zip";
							
							if($fr_chap_selec < 30){
                    sleep(1);
              }else{
                    sleep(2);
                  }
							//récupérer la fiche sélectionnée dans le chapitre
							system("gediget ".$fr->ref." ".$fr->lgue." ".$fr->ed." $identificationCIl -pdf > $res", $res);
						
            	if ($res != 0){
								$motdepasse = "\"$passwd\"";
								$identificationCIl = "-cn $login_user -pass $motdepasse";
								sleep(1);
								system("gediget ".$fr->ref." ".$fr->lgue." ".$fr->ed." $identificationCIl -pdf > $res", $res);
							}
							if ($res == 0){
								$color = "CCFFFF";
								$status ="OK ";
								$fic_select++;
							}else{
								$color = "EFED8F";
								$status ="NOK";
							}
						  // écrire la fiche sélectionnée dans le fichier body
							fwrite($file_title_chap,"<TR BGCOLOR=\"$color\" ><TD>$ii</TD><TD>$status</TD><TD><CENTER>$fr->ref</CENTER></TD><TD><CENTER>$fr->ed</CENTER></TD></TR>\n");
							
							
						}// fin si la fiche est sélectionnée
						
					}// fin du for j nombre de fiches dans le chapitre
					
					fwrite($file_title_chap,"</TBODY>\n");
					fwrite($file_title_chap,"</TABLE></CENTER>\n");
					fclose($file_title_chap);
	        }//
	         	if ($fic_select!=0){
	         	
	         	//création du fichier html
	         			//création du fichier html pour les fiches sous chapitre
	         			if (!$file_head_chap = fopen("$path_stp3/headchap","w")) 
						      die("$string7");
					      else 
					       {	
						        fwrite($file_head_chap, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"6\"><B>$string1</B><BR></FONT></CENTER><BR>\n");
						        fwrite($file_head_chap, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"4\"><B>$titre_doc_RC</B><BR></FONT></CENTER><BR>\n");
						        fwrite($file_head_chap, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"4\"><B>$ref_ctgp</B><BR><BR></FONT></CENTER><HR><BR>\n");

						        fwrite($file_head_chap, "<FONT COLOR=\"000FFF\" SIZE=\"5\"> <CENTER>	<B> $fr_chap_selec $string2_bis</B></CENTER><BR></FONT></CENTER><BR>\n");
						        fwrite($file_head_chap, "<FONT COLOR=\"000FFF\" SIZE=\"5\"> <CENTER>	<B> $num_chap.$chap_name</B></CENTER><BR><BR></FONT></CENTER><BR>\n");
						        fwrite($file_head_chap, "<CENTER><TABLE BORDER = 1 BORDERCOLOR = 000FFF  FONT COLOR=\"#000FF\">\n");
						        fwrite($file_head_chap, "<THEAD><B><TR BGCOLOR=\"FFF000\" ><TD><CENTER>$string3</CENTER></TD><TD><CENTER>STATUS</CENTER></TD><TD><CENTER>$string4</CENTER></TD><TD><CENTER>$string5</CENTER></TD></TR></B></THEAD>\n");
						        fwrite($file_head_chap, "<TBODY>\n");
						        fwrite($file_head_chap, "\n");
						        fclose ($file_head_chap);
					       } // fin d'écriture dans le fichier head 
					       					       
					       $readmechap="readme.".$num_chap.".html";
					       system("cat headchap bodychap > $readmechap");
					       
					    // NGJ trace
					    /*
							 $edition_etat = "fonctionsStep2.cre_f_zip_chap => ligne 3535 ";
	             $string1000 = "N°chap: $chapCourant->num, fr_chap_selec=$fr_chap_selec, fichier readme = $readmechap";
	             erori ($edition_etat,$string1000);
							*/
	         	
	         	//fin de la création
	         	
							 system("zip $fic_ref_zip *pdf* $readmechap > $res");
							 sleep(2);
							   //supprimer des fichiers pdf déjà zipper						
						    // $erase = $path_download."/*.pdf";
						     $erase = $path_stp3."/*.pdf";
						      system("rm -f $erase ");
						      //cre_liste_file_zip($path_download,$fic_ref_zip);
						      cre_liste_file_zip($path_stp3,$fic_ref_zip);
						      
						      // NGJ trace
						      $nb_ftt_chap=$nb_ftt_chap+$fr_chap_selec;
						      /*
									$edition_etat = "fonctionsStep2.cre_f_zip_chap => ligne 3800 ";
	                $string1000 = "création du fichier zip = $fic_ref_zip , avec $fr_chap_selec fiches sélectionnées \n";
	                erori ($edition_etat,$string1000);
									*/
              }
          // fin de Recherche des fiches dans les chapitres sélectionnées
					//******************************************************************  
              
            
					// Recherche des fiches dans les sous chapitres sélectionnées
					//***********************************************************
					$nb_ssChap = (count($chapCourant->sousChap)) ;// nombre de sous chapitres	
					for ($l = -1;$l < count($chapCourant->sousChap) - 1;$l++)
					{
						$ssChap = &$chapCourant->sousChap[$l];
					   $fic_select=0;
					   
						if ($ssChap->selected == 1){
						  $fr_sschap_selec=0;
						  // traitement des fiches dans le sous chapitre
						  $num_sschap = $ssChap->num;
						  $nom_sschap = $ssChap->nom;
			        $fic_sschap_zip = "CollectionFR_"."$num_sschap".".zip";
						  
						  // new 28_01_2008
						  $ii++;
						  $nb_ftt_sschap++;
								//ecrire le fichier zip du chapitre
				      fwrite($file_title,"<TR BGCOLOR=\"$color\" ><TD><CENTER>$fic_sschap_zip</CENTER></TD><TD><CENTER>$nom_sschap</CENTER></TD></TR>\n");
		  
						  if (!$file_title_sschap = fopen("$path_stp3/bodysschap","w")) {
	               die("$string6");
				      }else{
						  // fin new
							for ($k = -1;$k < count($ssChap->fr) - 1;$k++)
							{
								$fr = &$ssChap->fr[$k];
								if ($fr->selected == 1){
								  $fr_sschap_selec++;
									if($fr_sschap_selec < 30){
                    sleep(1);
                  }else{
                    sleep(2);
                  }
									//récupérer la fiche sélectionnée dans le sschapitre
									system("gediget ".$fr->ref." ".$fr->lgue." ".$fr->ed." $identificationCIl -pdf > $res", $res);	
									
                  if ($res != 0){
										$motdepasse = "\"$passwd\"";
										$identificationCIl = "-cn $login_user -pass $motdepasse";
										system("gediget ".$fr->ref." ".$fr->lgue." ".$fr->ed." $identificationCIl -pdf > $res", $res);
									}
									if ($res == 0){
										$color = "CCFFFF";
										$status ="OK ";
										$fic_select++;
									}else{
										$color = "EFED8F";
										$status ="NOK";
									}
									// écrire la fiche sélectionnée dans le fichier body
									fwrite($file_title_sschap,"<TR BGCOLOR=\"$color\" ><TD>$fic_select</TD><TD>$status</TD><TD><CENTER>$fr->ref</CENTER></TD><TD><CENTER>$fr->ed</CENTER></TD></TR>\n");
									
                  // NGJ trace
                  /**/
									$edition_etat = "fonctionsStep2.cre_f_zip_chap => ligne 3846 ";
	                $string1000 = "N°sschap: $num_sschap, N°fiche_selec: $fr_sschap_selec;";
	                erori ($edition_etat,$string1000);
									/**/
									
								}		// fin du fichier sélectionner
							}  // fin de la boucle for k des fiches dans les sous chapitres
							fwrite($file_title_sschap,"</TBODY>\n");
					    fwrite($file_title_sschap,"</TABLE></CENTER>\n");
					    fclose($file_title_sschap);
					    
							if ($fic_select!=0){
							   	//création du fichier html pour les fiches sous chapitre
	         			if (!$file_head_sschap = fopen("$path_stp3/headsschap","w")) 
						      die("$string7");
					      else 
					       {	
						        fwrite($file_head_sschap, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"6\"><B>$string1</B><BR></FONT></CENTER><BR>\n");
						        fwrite($file_head_sschap, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"4\"><B>$titre_doc_RC</B><BR></FONT></CENTER><BR>\n");
						        fwrite($file_head_sschap, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"4\"><B>$ref_ctgp</B><BR><BR></FONT></CENTER><HR><BR>\n");

						        fwrite($file_head_sschap, "<FONT COLOR=\"000FFF\" SIZE=\"5\"> <CENTER>	<B> $fr_sschap_selec $string2_bis</B></CENTER><BR></FONT></CENTER><BR>\n");
						        fwrite($file_head_sschap, "<FONT COLOR=\"000FFF\" SIZE=\"5\"> <CENTER>	<B> $num_sschap.$nom_sschap</B></CENTER><BR><BR></FONT></CENTER><BR>\n");
						        fwrite($file_head_sschap, "<CENTER><TABLE BORDER = 1 BORDERCOLOR = 000FFF  FONT COLOR=\"#000FF\">\n");
						        fwrite($file_head_sschap, "<THEAD><B><TR BGCOLOR=\"FFF000\" ><TD><CENTER>$string3</CENTER></TD><TD><CENTER>STATUS</CENTER></TD><TD><CENTER>$string4</CENTER></TD><TD><CENTER>$string5</CENTER></TD></TR></B></THEAD>\n");
						        fwrite($file_head_sschap, "<TBODY>\n");
						        fwrite($file_head_sschap, "\n");
						        fclose ($file_head_sschap);
					       } // fin d'écriture dans le fichier head 
					       					       
					       $readmesschap="readme.".$num_sschap.".html";
					       
					      // NGJ trace
					      /*
							 $edition_etat = "fonctionsStep2.cre_f_zip_chap => ligne 3790 ";
	             $string1000 = "N°sous chap: $chapCourant->num, fichier readme = $readmesschap";
	             erori ($edition_etat,$string1000);
							*/
					       
					       system("cat headsschap bodysschap > $readmesschap");     
	         	     system("zip $fic_sschap_zip *pdf* $readmesschap > $res");
	         	     //fin de la création du fichier zip
	         	     
							   sleep(2);
							   //supprimer des fichiers pdf déjà zipper						
						     $erase = $path_stp3."/*.pdf";
						      system("rm -f $erase ");

						      cre_liste_file_zip($path_stp3,$fic_sschap_zip);
						      
						      // NGJ trace
						      /*
						      //$nb_ftt_sschap=$nb_ftt_sschap+$fr_sschap_selec;
									$edition_etat = "fonctionsStep2.cre_f_zip_chap => ligne 3864 ";
	                 $string1000 = "création du sous chapitre $nom_sschap = $fic_sschap_zip , avec $fr_sschap_selec fiches sélectionnées \n";
	                 erori ($edition_etat,$string1000);
									*/
              }
              //new 28_01_2008
              }
              //fin new
              
						}  // fin si le sous chapitre sélectionné
					}  // fin de la boucle for l sur les sous chapitre
					// fin de Recherche des fiches dans les sous chapitres sélectionnées
					//******************************************************************
					
					fwrite($file_title,"</TBODY>\n");
					fwrite($file_title,"</TABLE></CENTER>\n");
					fclose ($file_title);
					
					//if (!$file_head = fopen("$path_download/head","w")) 
					if (!$file_head = fopen("$path_stp3/head","w")) 
						die("$string7");
					else 
					{	
						fwrite($file_head, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"6\"><B>$string1</B><BR></FONT></CENTER><BR>\n");
		        fwrite($file_head, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"4\"><B>$titre_doc_RC</B><BR></FONT></CENTER><BR>\n");
		        fwrite($file_head, "<CENTER><FONT COLOR=\"FF0000\" SIZE=\"4\"><B>$ref_ctgp</B><BR><BR></FONT></CENTER><HR><BR>\n");
            // à ajouter une ligne d'affichage d'info du chapitre
            $infochapitre = $string8." - ".$num_chap.".".$chap_name;
            fwrite($file_head, "<FONT COLOR=\"000FFF\" SIZE=\"5\"> <CENTER>	<B>$infochapitre</B></CENTER><BR><BR></FONT></CENTER><BR>\n");
            if($fr_sschap_selec!=0){
              // nombre total des sous chapitres sélectionnées
            	fwrite($file_head, "<FONT COLOR=\"000FFF\" SIZE=\"5\"> <CENTER>	<B>$string2_ter $nb_ftt_sschap</B></CENTER><BR><BR></FONT></CENTER><BR>\n");
            }
            if($fr_chap_selec!=0){
               // nombre total des fiches sélectionnées
            		fwrite($file_head, "<FONT COLOR=\"000FFF\" SIZE=\"5\"> <CENTER>	<B>$string9 $fr_chap_selec</B></CENTER><BR><BR></FONT></CENTER><BR>\n");
            }
            // fin d'ajout
		        
            fwrite($file_head, "<CENTER><TABLE BORDER = 1 BORDERCOLOR = 000FFF  FONT COLOR=\"#000FF\">\n");
		        fwrite($file_head, "<THEAD><B><TR BGCOLOR=\"FFF000\" ><TD><CENTER>$string4_bis</CENTER></TD><TD><CENTER>$string5_bis</CENTER></TD></TR></B></THEAD>\n");
		        fwrite($file_head, "<TBODY>\n");
		        fwrite($file_head, "\n");
		        fclose ($file_head);
						
					} // fin d'écriture dans le fichier head 
					
					// création du fichier zip du chapitre
					
					$chDirOk = chdir($path_stp3);
					if (!$chDirOk) {
						die("pb chdir script step3!!");
					}else{
					
					 //18_03_08 ajout le n°chap dans le nom readme
					 	$readme_chap="readme.".$num_chap.".html";
		        system("cat head body > $readme_chap");
          	//system("cat head body > readme.html"); //ok
						sleep(1);
						//system("zip $fic_chap_zip *zip* readme.html > $res");//ok
						system("zip $fic_chap_zip *zip* $readme_chap > $res");
						// fin d'ajout
            sleep(1);
						/**/
						system("cp $fic_chap_zip $path_stp2");

						$erase = $path_download."/".$fic_chap_zip;

						system("rm -f $erase ");

						del_liste_file_zip($path_stp3);
						
									// NGJ trace
							 $nb_tt_zippee=$nb_tt_zippee+$nb_ftt_sschap;
							 /*
							 $edition_etat = "fonctionsStep2.cre_f_zip_chap => ligne 3877 ";
	             $string1000 = "création du fichier zip = $fic_chap_zip avec total = $nb_ftt_sschap fiches";
	             erori ($edition_etat,$string1000);
									*/
					}
				}
			}// fin si le chapitre courant sélectionné
		} // fin de la boucle for i nombre de chapitres
	}
                /*
								// NGJ trace
							 $edition_etat = "fonctionsStep2.cre_f_zip_chap => ligne 3935 ";
	             $string1000 = "Nombre total des fiches zippées = $nb_tt_zippee sur $nb_fr_tt_selec demandées ";
	             erori ($edition_etat,$string1000);
									*/
	
	return $nb_fr_tt_selec;
}	//fin function cre_f_zip_chap



//********************************************************************************************	
//cette fonction permet de selectionner tout les FR pour l'affichage en mode modification
function select_cr_modif()
{
	global $crRecupere;
	
	for ($i = -1;$i < count ($crRecupere->chapitres) - 1;$i++) 
	{
		// récupérer le chapitre courant
		$chapCourant = &$crRecupere->chapitres[$i];
		//sélectionner tous les chapitres, sous chapitres et fiches de recettes
		$chapCourant->select();
	} // fin de la boucle for de i
}	//fin function select_cr_modif

//********************************************************************************************	
//cette fonction permet de créer la liste des references FR du catalogue général dans un fichier listerefctg
//et qu'il se trouve dans le répetoire sygtemp
function verf_liste_ref_ctg($refValue)
{
	global $racineSyg,$racineSygTemp,$nameSygTemp;
	global $motdepasse,$login_user,$repUtil;
	
	$listerefctg = $racineSygTemp.$nameSygTemp."/".$repUtil."/listerefctg";

	$files_root = list_dir($racineSygTemp.$nameSygTemp."/".$repUtil);
	$cnts = 0;
	$ref_rech=0;	// cas pas d'erreur fichier à rechercher non trouvé
	
	foreach($files_root as $file)
	{
		if (ereg("listerefctg", $file)) 
		{
			$cnts = 1;
		}
	}
	if ($cnts == 0)
	{
		// absent du fichier listerefctg
		$ref_rech=1;
	}else{
		$lgrefValue = strlen($refValue);
		$fic = fopen($listerefctg, "r");
		
		while (!feof ($fic)) 
		{
			$buffer = fgets($fic, 4096);					
			
			if (strncmp($buffer, $refValue,$lgrefValue) == 0){ 
				$ref_rech=1;
			}
		}
		
		fclose ($fic);
	}
	
	
	return $ref_rech;
}
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

function verf_FC_valide($referenceC,$langueC,$edition)
{
	//consignation sur gedi
	global $identificationCIl;
	global $langue_browser, $passwd, $login_user;
	global $racineSyg,$nameSygTemp,$repUtil;
	global $racineSygTemp;
	
	$step2="step2";

	if ($langueC=="B")
		$langueC="FR";
	else
		$langueC="EN";
		
	$etat_edit = 0;
	$type=".xzip";
    $cale="$racineSygTemp$nameSygTemp/$repUtil/$step2/$referenceC"."_".$langueC."_".$edition.$type;
	$cale1 = "_resltC-extract";
	$cale2 = "_resltC-consign";

			$motdepasse = "\"$passwd\"";
			$identificationCIl = "-cn $login_user -pass $motdepasse";	

		system("gediget ".$referenceC." ".$langueC." $identificationCIl  -extract  -nosrc > $cale.$cale1", $resltCE);

		if ($resltCE != 0 )
		{
				// cas la fiche est en etat validé				
			$etat_edit = 1;	
		}else{
			$etat_edit = 0;
		}
		system("gediput $cale  $identificationCIl -type CAB > $cale.$cale2", $resltCC); 
		
		$etat_edit;
		  
		return $etat_edit;
}

/********************************************************************************************/	
//cette fonction permet de chercher un fichier puis le supprime
//si $remove = 1  trouve et supprime 
// si $Res_del = 1 trouve , 0 = non trouve
function Search_Remove_file($rep_travail,$file_SR,$remove)
{
	$Res_del = 0;

	//suppression le fichier ref existé 
	$files_root = list_dir($rep_travail);
	foreach($files_root as $file) {
		//recherche le fichier de type 3BL48849TTAAQSAGB_03_FR.xzip
		if (ereg("._resltC-extract$",$file)){
			list($ref_CR,$file_type,$option_extrac) = explode (".", $file);
			$ref_ctg=$ref_CR.".".$file_type;
			if (strcmp($file_SR,$ref_ctg) == 0){// return 1; //true
				$file_del = "$rep_travail$file_SR._resltC-extract";

				if ($remove == 1){
					system("rm -fR $file_del");	
				}
				$Res_del = 1 ;					
			}
		}
	}
	return $Res_del;//1 = truove  , 0 = non trouve
}	//fin function Search_Remove_file
?>
