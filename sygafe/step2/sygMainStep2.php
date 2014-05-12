<?php

/****************************************************************************************/
/********************************** sygMainStep2.php ************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: construction du formulaire et avec affichage du CR et construction du	*/
/* catalogue 										*/
/*											*/
/* fonctions:										*/
/*  											*/
/****************************************************************************************/

echo("<HTML>\n");
echo("<HEAD>\n");


global $urlSygafe, $urlRacineSyg;
//formulaire pour transmettre les variables php en javascript
echo("<FORM  name=variables>\n");
echo("<INPUT TYPE=\"hidden\" NAME=\"urlSygafe\" VALUE=\"".$urlSygafe."\">\n");
echo("<INPUT TYPE=\"hidden\" NAME=\"urlRacineSyg\" VALUE=\"".$urlRacineSyg."\">\n");
echo("<INPUT TYPE=\"hidden\" NAME=\"sygTemp\" VALUE=\"".$nameSygTemp."\">\n");

echo("</FORM>\n");

$langue_browser = $HTTP_COOKIE_VARS[Langue];



?>

<!- fonctions JavaScript ->
<SCRIPT LANGUAGE=JavaScript>

/***************** variables de config JavaScript à configurer ************************/
//url de Sygafe
var urlSygafe_js = document.variables.urlSygafe.value;

//nom du répertoire permettant le stockage des répertoires utilisateurs
//Ce répertoire a pour racine le répertoire $racineSyg
var nameSygTemp_js = document.variables.sygTemp.value;

//url internet pour accéder à la racine du répertoire sygTemp
var urlRacineSyg_js =  document.variables.urlRacineSyg.value;

/**************************************************************************************/





//variable permettant d'accéder à la fenêtre popup
var w;

//permet de savoir si une action est déjà en cours
var action = 0;

//cette variable permet de savoir si le bouton Valider
//doit faire referenec au formulaire d'affichage du CR ou
//pour générer le CR et le PV (formulaire infosCtg)
var formInfosCtg = 0;



function getcookie(cookiename)
{
	var index = document.cookie.indexOf(cookiename);
	
	namestart = (document.cookie.indexOf("=", index) + 1);
	nameend = document.cookie.indexOf(";", index);
	thecookie = document.cookie.substring(namestart, nameend);
	return thecookie;
}

//fonctions executées quand un évènement est déclenché

//cette fonction met le focus sur la première zone de saisie qd le formulaire infosCtg
//est chargé
function begin()
{

	//on va recuperer la valeur de op pour savoir si c'est ctgY ou non
	//si c'est ctgY alors mettre le focus dans la zone adéquate
	var url = document.location.href;
		
	//index est l'indice du tableau de caractères qui suit op=??
	var index = url.indexOf("op=",0);
	 
	//on extrait la valeur de op
	action = url.substring(index + 3,url.length); 
	
	//on précise qu'il s'agit du formulaire infosCtg pour le bouton Valider
	formInfosCtg = 1;
	
	//on ferme le popup d'attente
	closePopup();
	
	if ((action == "ctg") || (action == "ctgN#PV")) {
		document.infosCtg.ref_ctg.focus();
	} else {
		document.infosCtg.ref_pv.focus();
	}
}

//exécutée quand un lien pour consulter une FR est activé
function lireFiche()
{
	//cette fonction ne fait rien
	//on fera appel à cette fonction pour le lien permettant la consultation d'une 
	//fiche de recette à cause d'un bug Netscape - A HREF=# provoque quand même le 
	//rechargement de la page sous Netscape sous IE non ??
}


//exécutée quand un chapitre ou un sous-chapitre est coché ou décoché
function refresh_js(operation,ancre)
{
	if (action != 1) {
		action = 1;
		//operation vaut toc pour une activation d'n lien du sommaire et rfsh sinon
		//ancre permet d'accéder à l'ancre de la page (1.1, 2.1, ...)
		//on va extraire le chemin relatif pour avoir une url internet correct : pas de /home/...
		var cheminAbsolu = document.affichCR.rep.value;
		//index est l'indice du tableau de caractères où commence la chaine /sygTemp/
		var index = cheminAbsolu.indexOf("/sygTemp/",0);
		//on extrait du chemin absolu que la chaine commençant par /sygTemp/ cad le chemin relatif
		var cheminRelatif = cheminAbsolu.substring(index + 1,cheminAbsolu.length); 
		//JNG trace
		//alert(" ds refresh_js du sygMainStep2 ligne 126 : operation="+operation+", ancre="+ancre+"");
		//fin trace
		//popup d'attente
		openPopup(20, 12, 'waitForm', 'resizable=0');
		
		
		var fiche_insert;
			
		if ((operation == 'toc')  && (ancre.length > 10 ))
			{
				fiche_insert = ancre;		
				chap = fiche_insert.substr(21, 2);
				chap = parseInt(chap, 10);
				schap = fiche_insert.substr(23, 2);
				schap = parseInt(schap, 10);
				ancre = chap+"."+schap;
			}
				

		//appel de sygMain.php avec op=rfsh et on fourni un ancre correspondant au chiffre passé en paramètre
		//de la fonction. Ainsi quand la page sera rechargée, l'utilisateur reviendra au chapitre ou 
		//sous-chapitre qu'il vient de cocher/décocher
		if (operation == 'toc') {
		  	//JNG trace
		  //	HTTP_POST_VARS['chap_selec'];
	    // 	alert(" ds refresh_js du sygMainStep2 ligne 155 : fiche_insert ="+ancre+"");
	   parent.contenuCR.document.affichCR.chap_select.value = ancre;
	      //fin trace
			parent.contenuCR.document.affichCR.type.value = 'toc';			
			parent.contenuCR.document.affichCR.action = urlSygafe_js+"sygMain.php?fiche_insert="+fiche_insert+"&op=rfsh#"+ancre;
      parent.contenuCR.document.affichCR.submit();
      
      
		} else {
		
				  	//JNG trace
		//alert(" ds refresh_js du sygMainStep2 ligne 160 : action = "+action+", operation ="+operation+", ancre ="+chap_selec+"");
	      //fin trace
		
			//repositionnement sur le dernier chapitre/sous-chapitre modifié
			document.affichCR.action = "sygMain.php?op=rfsh#"+ancre;
			document.affichCR.submit();
		}
	}

}

//exécutée quand l'utilisateur a fini la constitution du catalogue
//mais aussi quand il a rentré les infos pour le CR customisé et le PV
//et que la génération est demandée
function validCtg_js()
{
	var Langue = getcookie('Langue');
	//JNG trace
	//	alert(" ds validCtg_js du sygMainStep2 ligne 162 : action="+action+"");
	//fin trace
	if (action != 1) {
	
		action = 1;
		//fenêtre d'attente
		openPopup(20, 12, 'waitForm', 'resizable=0');
			
		if (formInfosCtg == 0) {
		    //Traitement sur le bouton VALIDER appuyé
			//répertoire utilisateur
			var nameRepUtil = document.affichCR.rep.value;
			var ref_ctgp = document.affichCR.ref_ctgp.value;
			//on efface le sommaire en changeant l'url de la page grâce au chemin relatif 
			//déduit précédemment
			parent.sommaireCR.location = urlRacineSyg_js+nameSygTemp_js+"/"+nameRepUtil+"/step2/videSyg.html";
			parent.ajoutFR.location = urlRacineSyg_js+nameSygTemp_js+"/"+nameRepUtil+"/step2/videSyg.html";
		//	parent.validFrame.location = urlRacineSyg_js+nameSygTemp_js+"/"+nameRepUtil+"/step2/validSyg.html";
			parent.validFrame.location = urlRacineSyg_js+nameSygTemp_js+"/"+nameRepUtil+"/step2/videSyg.html";
			
			document.affichCR.submit();
		
		} else {
		    //Traitement sur la génération Génération le procès verbal radio button			
			//répertoire utilisateur
			var nameRepUtil = document.infosCtg.rep.value;
			
			parent.validFrame.location = urlRacineSyg_js+nameSygTemp_js+"/"+nameRepUtil+"/step2/validSyg.html";
			
			//c'est la génération du CR et du PV qui est demande
			//submit du formulaire infosCtg
			var valide = false; 
					
			if (!document.infosCtg.genPv[0].checked && !document.infosCtg.genPv[1].checked) {
				if (Langue == "FR") 
					alert('Sélectionnez Oui ou Non pour la génération du PV SVP !');
				else
					alert('Please select Yes or No for acceptance form generation !');

				action = 0;
			} else {
				if ((document.infosCtg.titre_ctg.value.length == 0) || (document.infosCtg.ref_ctg.value.length == 0) || 
					(document.infosCtg.edition_ctg.value.length == 0)) {
					if (Langue == "FR") 
					alert('Renseignez tous les champs obligatoires pour le cahier de recette!');
					else
					alert('Fill in the mandatory fields for the acceptance book!');					
					action = 0;
				} else {
					if(document.infosCtg.genPv[0].checked) {
						//si l'utilisateur a coché le bouton-radio
						//Oui pour la génération du PV
						if ((document.infosCtg.titre_pv.value.length == 0) || (document.infosCtg.ref_pv.value.length == 0) || 
							(document.infosCtg.edition_pv.value.length == 0)) {
								if (Langue == "FR") 
									alert('Renseignez tous les champs obligatoires pour le procès verbal!');
								else
									alert('Fill in the mandatory fields for the acceptance form!');
							
							action = 0;
						} else {
							valide = true;
						}
					} else {
						valide = true;
					}
				}
				if (valide) {

					//on efface la frame du bouton valider en changeant l'url de la page grâce au chemin relatif 
					//déduit précédemment
				//	openPopup(20, 12, 'waitForm', 'resizable=0');
					parent.validFrame.location = urlRacineSyg_js+nameSygTemp_js+"/"+nameRepUtil+"/step2/videSyg.html";
					parent.ajoutFR.location = urlRacineSyg_js+nameSygTemp_js+"/"+nameRepUtil+"/step2/videSyg.html";
					document.infosCtg.submit();
				}
			}
		}
			
	}

}

//fonction appelée quand on sélectionne oui ou non pour la génération du PV
function genPv_js()
{
	var Langue = getcookie('Langue');
	var nameRepUtil = document.infosCtg.rep.value;
	var reg = new RegExp("^[0-9]{1}$","i");
	var car = 0;
	//JNG trace
	//	alert(" ds genPv_js du sygMainStep2 ligne 261 : action="+action+"");
	//fin trace
	if (action != 1) {
		refvalue = document.infosCtg.ref_ctg.value;
		refvalue = refvalue.toUpperCase();
		if(refvalue.length == 17) 
		{
			if ((reg.test(refvalue.substr(3, 1))) &&
				(reg.test(refvalue.substr(4, 1))) && 
				(reg.test(refvalue.substr(5, 1))) && 
				(reg.test(refvalue.substr(6, 1))) &&
				(reg.test(refvalue.substr(7, 1)))){
				car = 0;
			}else{
				car = 1;
			}
			if(car == 0){
			//if(refvalue.length == 17) 
			//{
				if(document.infosCtg.genPv[0].checked) {
				//Oui
					action = 1;
				//fenêtre d'attente
					openPopup(20, 12, 'waitForm', 'resizable=0');
					parent.validFrame.location = urlRacineSyg_js+nameSygTemp_js+"/"+nameRepUtil+"/step2/validSyg.html";
					document.infosCtg.action = "sygMain.php?op=ctgY#PV";
					document.infosCtg.submit();
				} else {
					validCtg_js();
				}
			//}else{
			//	if (Langue == "FR") 
			//		alert("La référence est erronée !! Vérifier:  \n La longueur doit être 17 caractères");
			//	else
			//		alert("Bad reference !! Check the followings: \n Lenght must be 17 characters");
			//}
			}else{
				if (Langue == "FR") 
					alert(" La référence est erronée !! Vérifier:  \n la tranche de code doit être des chiffres ");
				else
					alert(" Bad reference !! Check the followings: \n the section of code  must be in figures ");			
			} // fin  if(car == 0)
		}else{
			if (Langue == "FR") 
				alert("La référence est erronée !! Vérifier:  \n La longueur doit être 17 caractères");
			else
				alert("Bad reference !! Check the followings: \n Lenght must be 17 characters");
		}
	} // fin if (action != 1)
}

//*************************************************************************************************
function consignC_js()
{


	//popup d'attente
	openPopup(20, 12, 'waitForm', 'resizable=0');
	document.lastForm.action = "sygMain.php?op=cons";
	document.lastForm.cahier.value = 1;
	document.lastForm.submit();

}

function consignP_js()
{	

	//popup d'attente
	openPopup(20, 12, 'waitForm', 'resizable=0');
	document.lastForm.action = "sygMain.php?op=cons";
	document.lastForm.procvb.value = 1;
	document.lastForm.submit();

	
}

function pvcs_js()
{	


	//popup d'attente
	openPopup(20, 12, 'waitForm', 'resizable=0');
	document.newpv.action = "sygMain.php?op=pvcs";
	document.newpv.submit();
}

function afficheSC_js()
{


	document.formname.action = "sygMain.php?op=scf";
	document.formname.submit();

}

function afficheSSC_js()
{


	document.formname.action = "sygMain.php?op=sscf";
	document.formname.submit();

}

function ajFrc_js()
{
	var Langue = getcookie('Langue');
	if (document.formname.ref_fr.value.length != 17) 
	{
	if (Langue == "FR") 
		alert("La référence est erronée !!");
	else
		alert("Bad reference !!");
	
	}
	else
	{
	//	openPopup(20, 12, 'waitForm', 'resizable=0');
		document.formname.action = "sygMain.php?op=ajfc";
		document.formname.submit();
	}

		
}

function ajFrsc_js()
{
	var Langue = getcookie('Langue');
	if (document.formname.ref_fr.value.length != 17) 
	{
		if (Langue == "FR") 
			alert("La référence est erronée !!");
		else
			alert("Bad reference !!");
	}
	else
	{
	//	openPopup(20, 12, 'waitForm', 'resizable=0');
		document.formname.action = "sygMain.php?op=ajfsc";
		document.formname.submit();
	}

		
}

function nfr_js()
{


	document.formname.action = "sygMain.php?op=afi";
	document.formname.submit();
		
}

function archives()
{
	
//	openPopup(20, 12, 'waitForm', 'resizable=0');
	document.archiv.action = "sygMain.php?op=arch";
	document.archiv.submit();
}	

function deconnex_js(nomform)
{
	document[nomform].action = "sygMain.php?op=DCX";
	document[nomform].submit();
}

function home_js(nomform)
{
	document[nomform].action = "sygMain.php?op=DCX&home=1";
	document[nomform].submit();
}

function noenter() 
{
  return !(window.event && window.event.keyCode == 13); 
}
	
function enter(nextfield) 
{
	if(window.event && window.event.keyCode == 13) 
		{
 			nextfield.focus();
  			return false; 
  		}
	else
  	return true; 
}
	
		
//*************************************************************************************************

//cette fonction affiche un Popup centré
//pour nous il permet de signaler à l'utilisateur de patienter un peu
function openPopup(width, height, windowName, featureString)
{

	if (!windowName)
		windowname = '';
	if (!featureString)
		featureString = '';
	else
		featureString = ',' + featureString;
	var x = Math.round((screen.availWidth - width) / 2);
	var y = Math.round((screen.availHeight - height) / 2);
	featureString = 'left=' + x + ',top=' + y + ',width=' + width + ',height=' + height + featureString;
	
	w = open('about:blank', 'waitForm', featureString);
	var html = '';
	html = '<HTML><BODY><CENTER><IMG SRC=\"'+urlSygafe_js+'images/timer.jpg\" name=\"horloge\" width=\"56\" height=\"80\" border=\"0\"></CENTER></BODY><\/HTML>';
	w.document.open();
	w.document.write(html);
	w.document.close();
}

//cette fonction permet de fermer la fenêtre popup
//on appelle à nouveau waitForm() pour obtenir une référence
//sur la fenêtre afin de la fermer
function closePopup()
{
	w = open('about:blank', 'waitForm', 'resizable=0');
	w.close();
//	w.close();

}

//permet de relancer une session SYGAFE : attention aux frames, les supprimer
function reload(val)
{
	alert(val);
	parent.parent.document.location = urlSygafe_js+"sygMain.php?log_name=$identifiant&op=RAS";
}


// Jacob Nguyen 20_06_2007
//permet de valider l'un des 2 bouton radio oui ou non pour générer les fichiers d'archivage zip
function testerRadio(radio) 
{

    for (var i=0; i<radio.length;i++) {

        if (radio[i].checked) {
			if (radio[i].value == "oui"){
			//alert("appel genPv_js = "+radio[i].value+"");
				document.infosCtg.genPv[0].checked=true;
				genPv_js();
			}else{
			//alert("Système = "+radio[i].value+"");
				document.infosCtg.genPv[0].checked=false;
				genPv_js();
			}

        }

    }

}



</SCRIPT>
<?php
$langue_browser = $HTTP_COOKIE_VARS[Langue];

	//$cil = $HTTP_COOKIE_VARS[Cil];
	//$passwd = $HTTP_COOKIE_VARS[Cip];
	
	$cil = lire_gediInit("CN");
	$passwd = lire_gediInit("PASSWD");
	$login_user = "\"$cil\"";
	
	$csl=$HTTP_COOKIE_VARS[Csl];
	
//	echo " sygMainStep2 appel à lire_gediinit ligne 532: cil = $cil, passwd = $passwd, csl = $csl <br>";
	
	// sauvegarde le passeword pour le retour à la page d'accueil SYGAFE
	setcookie ("Cip", $passwd);
	
	global $sav_nbfiche_select, $nb_tt_zippee, $nbfiche_select_max;
	global $traitement_mode;


	if ($langue_browser == "FR")
		{
			$string1="Insérer une fiche de recette ";
			$string2="Fiche de recette ajoutée";
			$string3="Insérer une nouvelle fiche";
			$string4="FICHE EXISTE DEJA DANS LE CAHIER";
			$string5="Erreur : FICHE inexistante ou problème avec le serveur FTP de GEDI";
			$string6="Cliquez sur les liens pour télécharger les fichiers: ";
			$string7="Impossible de créer le PV";
			$string8="Modification de cahier de recette générique interdite";
			
		}
	else
		{
			$string1="Insert an acceptance sheet ";
			$string2="Acceptance sheet inserted";
			$string3="Insert a new acceptance sheet";
			$string4="ACCEPTANCE SHEET ALREADY EXISTS";
			$string5="Error : Acceptance sheet not found or GEDI FTP server problem";
			$string6="Click on the link to download the files";
			$string7="Can not create the acceptance form";
			$string8="Generic acceptance book modification forbidden";
			
		}
	
if ($op == stp2) {


	//on récupère la valeur du répertoire utilisateur contenu dans le champ caché du formulaire
 	//Attention il s'agit du chemin absolu on va donc découper la chaine en deux pour avoir
 	//d'un côté la racine et de l'autre le répertoire utilisateur mais le chemin relatif
	
	$repUtil = $rep;
	

	
	//on récupère dans l'url le nom du CR recupéré
	$file = "$racineSygTemp$nameSygTemp/$repUtil/step1/$nameCR";

	
	//création les palises pour le fiche de recette en xml
	///home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe_V2.06/sygTemp/nguyen6/step1/3BL78589BBAAADZZA_04_EN.xml
	lireFichierP();
	//*****
	
	//création les palises pour le fichier Correspondances_FR-3DR.xml
	lireFichierD();

	if ($crRecupere->prefixDoc == "3BW"){
		info3DR(); // à appeler seulement si le préfix du document = 3BW
	}
	updateref();
	
	$ref_ctgp = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc ;
	
	//*****
	//entete de la page
	//sérialisation
	

		/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 602 \n";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_debut=aff_temp_ecoule(0);
	

	serialiser();
	

		/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 613 \n ";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_ecoule=aff_temp_ecoule($t_debut);
	
	//le fiche à afficher dans frame :  3BL78589BBAAADZZA
	//Construction de la page  frameSyg3BL78589BBAAADZZA.html
	frame();
	
	//libération de la variable - désallocation mémoire plus rapide
	unset($crRecupere);
	
	
	//redirection vers les fichiers html du répertoire sygTemp	
	//Location : http://las43310.ln.cit.alcatel.fr:8888/sygafe_V2.05/sygTemp/nguyen6/step2/frameSyg3BL78589BBAAADZZA.html <br>
	/**/
	//$string1000 =  " sygMainStep2 : op = $op ligne 596 avant d'appel header <br>";
	//$string1001=  " Location: $urlRacineSyg$nameSygTemp/$repUtil/step2/frameSyg$ref_ctgp.html<br>";
	//erori ($string1000,$string1001);
	/**/

	header("Location: ".$urlRacineSyg.$nameSygTemp."/".$repUtil."/step2/frameSyg$ref_ctgp.html");
	
	exit();
}	
//*************************************************************************************************
 elseif (($op == com))
{

	$repUtil = $rep;
	$file = "$racineSygTemp$nameSygTemp/$repUtil/delta/$nomRef1";

	lireFichierP();
	$cr1 = $crRecupere;
	/**/
	//$string1000 =  " sygMainStep2 : op = $op ligne 621 d'appel lireFichierP <br>";
	//$string1001=  " fichier ref1 à passer lireFichierP = $file, fichier ref1 à récupérer lireFichierP = $cr1<br>";
	//erori ($string1000,$string1001);
	/**/
	unset ($crRecupere); // Détruit la variable $crRecupere
	$prefixCtg = "";
	$numberCtg = "";
	$variantCtg = "";
	$versionCtg = "";
	$doctypeCtg = "";
	$zzCtg = "";
	$lgueCtg = "";
	$titleCtg = "";
	$mnemonicCtg = "";
	$editionCtg = "";
	$file = "$racineSygTemp$nameSygTemp/$repUtil/delta/$nomRef2";
	
	lireFichierP();
	$cr2 = $crRecupere;
	
	unset ($crRecupere); // Détruit la variable $crRecupere
	
	affichDiff();	
 
}

 elseif (($op == cons))
 {
 
  	/*
	$string100 =  " sygMainStep2 : op = $op ligne 576 avant d'appel consign et lastWindow, traitement_mode = $crRecupere->traitement_mode<br>";
	erori ("",$string100);
	*/
	
 	//consign GEDI
 	
 	list($referenceC, $langueC) = explode ("_", $nomfic);

 	consign();
	
 	lastWindow();
 }
 
 elseif (($op == pvcs))
 {
 	//consign pv dans GEDI
	pv_window();
 }
 
 elseif (($op == arch))
 {
	$repUtil = $rep;
 	formname();	
	//désérialisation
	//deserialiser();
 	echo("<TR><TD><FONT COLOR=\"#C00FF\" SIZE=\"4\">$string6</FONT>\n");
// 	saveFR();
 }
 

elseif ( $op == afi )
{
	$repUtil = $rep;
	formname();	
	//désérialisation
	deserialiser();

	echo("<BODY BGCOLOR=\"#FFFFES\">\n");
	echo("<CENTER><FONT COLOR=\"#000000\" SIZE=\"6\">\n");
	echo("<B>$string1<B></FONT>");
	echo("<BR><FONT COLOR=\"#000000\" SIZE=\"4\">\n");

	creerSelectChap ($crRecupere->chapitres);

}

elseif ( $op == scf )
{	
	$repUtil = $rep;
	formname();	
		
	//désérialisation
	deserialiser();
	
	echo("<BODY BGCOLOR=\"#FFFFES\">\n");
	creerSelectChap ($crRecupere->chapitres);
	echo("<RIGHT>\n");
	echo("</TABLE>\n");
	echo("<SCRIPT>document.formname.selection_chap.selectedIndex = ($selection_chap + 2);</SCRIPT>\n");
	$chap = &$crRecupere->chapitres[$selection_chap];
	$comp = $chap -> sousChap[-1] -> nom;

	if ($selection_chap == -1){
		$b = $crRecupere->chapitres[-1]->sousChap[-1]->num;
		$comp = $b;
	}
	if ($comp == ""){
		ajoutFrChap($chap);
	}else{
		if ($selection_chap == -1){
			$chap = &$crRecupere->chapitres[-1];
			creerSelectSChap($chap->sousChap);
		}else
			creerSelectSChap($chap->sousChap);
		echo("<INPUT TYPE=\"hidden\" name=\"sss\" value=\"$selection_schap\">\n");
	}

}

elseif ( $op == sscf )
{	
	$repUtil = $rep;
	formname();	
	//désérialisation
	deserialiser();
	echo("<BODY BGCOLOR=\"#FFFFES\">\n");
		
	if ($selection_chap == -1)
	{
		creerSelectChap ($crRecupere->chapitres);
		echo("</TABLE>\n");
		creerSelectSChap($crRecupere->chapitres[-1]->sousChap);
	}
	else
	{
		creerSelectChap ($crRecupere->chapitres);
		echo("</TABLE>\n");
		creerSelectSChap($crRecupere->chapitres[$selection_chap]->sousChap);
	}
	echo("<SCRIPT>document.formname.selection_chap.selectedIndex = ($selection_chap + 2);</SCRIPT>\n");
	echo("<SCRIPT>document.formname.selection_schap.selectedIndex = ($selection_schap + 2);</SCRIPT>\n");
	$chap = &$crRecupere->chapitres[$selection_chap];
	$schap = &$chap->sousChap;

	echo("<INPUT TYPE=\"hidden\" name=\"chap\" value=\"$selection_chap\">\n");
	echo("<INPUT TYPE=\"hidden\" name=\"schap\" value=\"$selection_schap\">\n");
	
	ajoutFrSChap($crRecupere->chapitres[$selection_chap]->sousChap);

}

elseif ($op == ajfc)
{
	$repUtil = $rep;
	$mnemonicValue = "ZGA01";
	$refValue  = $ref_fr;

	formname();	
	//désérialisation
	deserialiser();
	$frpres = frpresent($refValue, $chap, "FALSE");
	
	if ($frpres != 1){
	
		$lastLetter = $ref_fr[16];      //premier élément à l'indice 0 !!
	
		if (strcmp($lastLetter,"A") == 0){
			$lgue = "EN";
		}elseif (strcmp($lastLetter,"B") == 0){
			$lgue = "FR";
		}elseif (strcmp($lastLetter,"D") == 0){
			$lgue = "SP";
		}
		$chDirOk = chdir("$racineSygTemp$nameSygTemp/$repUtil/step2");
		if (!$chDirOk){
			die("pb chdir script sygMainStep2!!");
 		}else{	
			system("gediget ".$ref_fr." ".$lgue." $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/step2/ficheRes", $ficheRes);	
		
		//JNG 28-09-2006
		if ($ficheRes != 0) 
		{
			//$motdepasse = relance_gediget($passwd);
			$motdepasse = "\"$passwd\"";
			$identificationCIl = "-cn $login_user -pass $motdepasse";

			system("gediget ".$ref_fr." ".$lgue." $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/step2/ficheRes", $ficheRes);
			
		}
		//fin trace
		}
		if ($ficheRes != 0){
			echo("<BODY ONLOAD=closePopup() >\n");
			echo("<FONT COLOR=\"#000000\" SIZE=\"5\">\n");
			$string = $string5;
			echo("<B><CENTER>$string</B><BR><BR>\n");
			echo("<TD><INPUT type=button name=nfr value=\"$string3\" onClick=nfr_js()></TD></TR>\n");
			echo("</FONT>\n"); 	
	  	}else{
	  	
		 	 //on récupère le nom du fichier xzip (un seul) pour le dézipper
			$fiche = list_dir("$racineSygTemp$nameSygTemp/$repUtil/step2/");
			//21_08_2008 version 2.06.01
			
			//foreach ($fiche as $file) 
			//	{
			//		if (ereg(".xzip$",$file)) 
			// 		$nomFileXzip = $file;
		 	//	} 
			 	
			//system("unzip $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileXzip >> $racineSygTemp$nameSygTemp/$repUtil/step2/fichezip");
			//system("rm -f $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileXzip");
			
			//$nomFileXML = $nomFileXzip;
			//$cnt= strlen ($nomFileXML);
			//$nomFileXML [$cnt - 1] = "";
			//$nomFileXML [$cnt - 2] = "l";
			//$nomFileXML [$cnt - 3] = "m";
			//$nomFileXML [$cnt - 4] = "x";		
			//$fic = $racineSygTemp.$nameSygTemp."/".$repUtil."/step2/$nomFileXML";
			//JNG trace	
			
			
			foreach ($fiche as $file) 
			{
				if (ereg(".xzip$",$file))
				{
					$nomFileXzip = $file;
					$filexmltype=1;
				} 		
				if (ereg(".wzip$",$file))
				{
					$nomFileWzip = $file;
					$filexmltype=0;
				}
			}
			if ($filexmltype==1)
			{
				system("unzip $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileXzip >> $racineSygTemp$nameSygTemp/$repUtil/step2/fichezip");
				system("rm -f $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileXzip");
		  		$nomFileXML = $nomFileXzip;
		  		$cnt= strlen ($nomFileXML);
		  		$nomFileXML [$cnt - 1] = "";
		  		$nomFileXML [$cnt - 2] = "l";
		  		$nomFileXML [$cnt - 3] = "m";
		  		$nomFileXML [$cnt - 4] = "x";
		  		$fic = $racineSygTemp.$nameSygTemp."/".$repUtil."/step2/$nomFileXML";
		  		$string1001 =  " fichier xzip = $nomFileXzip, fiche xml à lire = $fic <br>";
			}
			else
			{
				system("unzip $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileWzip >> $racineSygTemp$nameSygTemp/$repUtil/step2/fichezip");
				system("rm -f $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileWzip");
				//$nomFileWRD = $nomFileWzip;
				$tab_explode = explode( "_",$nomFileWzip);//ref_ed_lng.wzip
				$nb_tab= count($tab_explode);
				if ($nb_tab > 1)
				{	//$nomFileWRD=$tab_explode[0]."_".$tab_explode[1]."."."doc"; //ref_ed.doc
					$path_value=$racineSygTemp.$nameSygTemp."/".$repUtil."/step2/";
					$fic=cre_CR_doc($path_value,$tab_explode[0],$tab_explode[1],$tab_explode[2]);
				}
				$string1001 =  " fichier wzip = $nomFileWzip, fiche xml … lire = $fic <br>";
			}
    
    
    	//JNG trace	
				$string100 =  " sygMainStep2 :  Op = ajfc , insertion une fiche de recette format xml ou word <br>";
			//	$string1000 =  " chapitre courant : $crRecupere->chapitres, sous chapitre courant = $chapCourant->sousChap<br>";
				erori ($string100,$string1001);
			//fin trace	
		
			//
	
			lireFiche();
			
			if ($chap == -1)				
			$chap = -1;	
			$refchap = $chap;			
														
			lireFichierD();
			
			$crRecupere->chapitres[$chap]->ajouterFr($FR);	
			
			$location = count ($crRecupere->chapitres[$refchap]->fr);
			
			$newFr = &$crRecupere->chapitres[$refchap]->fr[$location - 2];
			
			//JNG trace	
			//	$string100 =  " sygMainStep2 : Appel compareFR ligne 771, compareFR ($newFr->ref) <br>";
			//	$string1000 =  " chapitre courant : $crRecupere->chapitres, sous chapitre courant = $chapCourant->sousChap<br>";
			//	erori ($string1000,$string100);
			//fin trace	
			
			list ($newFr->tdr, $newFr->ndr) = compareFR($newFr->ref);
			
								
			if ($chap < 8)
			$chap = "0".($chap + 2);
			else 
			$chap = ($chap + 2);
			$schap = "00";
							

			serialiser_fiche ($newFr, $chap.$schap);	
			$string = $string2;
			
			if ($crRecupere->chapitres[$refchap + 1]->sousChap[-1]->nom != ""  )
			{
				$nextchap = $refchap + 1;
				if ($nextchap < 8)
				$nextchap = "0".($nextchap + 2);
				else 
				$nextchap = ($nextchap + 2);
				$nextschap = "01";
			}
			
			$refValue = $refValue.$chap.$schap.$nextchap.$nextschap;
			//	echo("<SCRIPT>parent.contenuCR.location.reload();</SCRIPT>\n");
			echo("<SCRIPT>parent.contenuCR.refresh_js('toc','$refValue');</SCRIPT>\n");
			echo("<BODY BGCOLOR=\"#FFFFES\">\n");
			echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"5\">\n");
			echo("<B>$string</B>\n");
			echo("<BR><BR>\n");
		}
		echo("<BODY BGCOLOR=\"#FFFFES\">\n");
		echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"3\">\n");
		//	echo("<TD><INPUT type=button name=nfr value=\"$string3\" onClick=nfr_js()></TD></TR>\n");
		echo("</FONT>\n");
		echo("</CENTER>\n");
	}else{
			echo("<BODY ONLOAD=closePopup() >\n");
			echo("<BODY BGCOLOR=\"#FFFFES\">\n");
		 	echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"5\">\n");
		 	$string = $string4;
		 	echo("<CENTER><B>$string</B><BR><BR>\n");
		 	echo("<TD><INPUT type=button name=nfr value=\"$string3\" onClick=nfr_js()></TD></TR>\n");
			echo("</FONT>\n");
	}
	
}

elseif ($op == ajfsc)
{

// convertir en majuscule
  $ref_fr = strtoupper($ref_fr);
	$repUtil = $rep;
	$mnemonicValue = "ZGA01";
	$refValue  = $ref_fr;
	formname();	
	
	//désérialisation
	deserialiser();
	$frpres = frpresent($refValue, $chap, $schap);
	
	
	if ($frpres != 1)
		{
			$lastLetter = $ref_fr[16];      //premier élément à l'indice 0 !!
			
			if (strcmp($lastLetter,"A") == 0) 
				{
					$lgue = "EN";
				} 
			elseif (strcmp($lastLetter,"B") == 0) 
				{
					$lgue = "FR";
				} 
			elseif (strcmp($lastLetter,"D") == 0) 
				{
					$lgue = "SP";
				}
	
	$chDirOk = chdir("$racineSygTemp$nameSygTemp/$repUtil/step2");
 	if (!$chDirOk) 
 		{
 			die("pb chdir script sygMainStep2!!");
 		}
 	else
	 	{	
			system("gediget ".$ref_fr." ".$lgue." $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/step2/ficheRes", $ficheRes);
			
				
			if ($ficheRes != 0) 
			{
				//$motdepasse = relance_gediget($passwd);
				$motdepasse = "\"$passwd\"";
				$identificationCIl = "-cn $login_user -pass $motdepasse";
				system("gediget ".$ref_fr." ".$lgue." $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/step2/ficheRes", $ficheRes);
			}
		}

	if ($ficheRes != 0) {
			echo("<BODY ONLOAD=closePopup() >\n");
		 	echo("<FONT COLOR=\"#000000\" SIZE=\"5\">\n");
		    $string = $string5;
		 	echo("<CENTER><B>$string</B><BR><BR>\n");
			echo("</FONT>\n"); 	
	}else{
	 	 //on récupère le nom du fichier xzip (un seul) pour le dézipper
		$fiche = list_dir("$racineSygTemp$nameSygTemp/$repUtil/step2/");
		
		foreach ($fiche as $file) 
			{
				if (ereg(".xzip$",$file))
        {
        $nomFileXzip = $file;
        $filexmltype=1;
        } 		 		
		 		//NGJ 16_10_07 ajout pour test fichier de type Word
		 		if (ereg(".wzip$",$file))
         {
         $nomFileWzip = $file;
         $filexmltype=0;
         } 		 		
		 		//fin d'ajout 			 	
			}  	
		if ($filexmltype==1){
			system("unzip $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileXzip >> $racineSygTemp$nameSygTemp/$repUtil/step2/fichezip");
		  system("rm -f $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileXzip");
		  $nomFileXML = $nomFileXzip;
		  $cnt= strlen ($nomFileXML);
		  $nomFileXML [$cnt - 1] = "";
		  $nomFileXML [$cnt - 2] = "l";
		  $nomFileXML [$cnt - 3] = "m";
		  $nomFileXML [$cnt - 4] = "x";
		  $fic = $racineSygTemp.$nameSygTemp."/".$repUtil."/step2/$nomFileXML";
		  //$string1001 =  " fichier xzip = $nomFileXzip, fiche xml à lire = $fic <br>";
    }else{
    	system("unzip $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileWzip >> $racineSygTemp$nameSygTemp/$repUtil/step2/fichezip");
		  system("rm -f $racineSygTemp$nameSygTemp/$repUtil/step2/$nomFileWzip");
		  //$nomFileWRD = $nomFileWzip;
		  $tab_explode = explode( "_",$nomFileWzip);//ref_ed_lng.wzip
	    $nb_tab= count($tab_explode);
	    if ($nb_tab > 1){
	       //$nomFileWRD=$tab_explode[0]."_".$tab_explode[1]."."."doc"; //ref_ed.doc
	       $path_value=$racineSygTemp.$nameSygTemp."/".$repUtil."/step2/";
	       $fic=cre_CR_doc($path_value,$tab_explode[0],$tab_explode[1],$tab_explode[2]);
      }
		  //$string1001 =  " fichier wzip = $nomFileWzip, fiche xml … lire = $fic <br>";
    }

			//JNG trace	
			//	$string1000 =  " sygMainStep2 : Appel lireFiche ligne 1001  op = $op <br>";
			//	erori ($string1000,$string1001);
			//fin trace	
		lireFiche(); 
		
		if ($chap == -1)
			$chap = -1;
		if ($schap == -1)
			$schap = -1;
	
		$refchap = $chap;
		$refschap = $schap;
	
		$sc = &$crRecupere->chapitres[$chap]->sousChap[$schap];
			
		lireFichierD();

		$sc ->ajouterFr($FR);
	
		$location = count ($crRecupere->chapitres[$refchap]->sousChap[$refschap]->fr);
	
		$newFr = &$crRecupere->chapitres[$refchap]->sousChap[$refschap]->fr[$location-2];
			/*
      //JNG trace	
				$string100 =  " sygMainStep2 : Appel compareFR ligne 1058, compareFR ($newFr->ref) <br>";
				$string1000 =  " chapitre courant : $crRecupere->chapitres, sous chapitre courant = $chapCourant->sousChap<br>";
				erori ($string100,$string1000);
			//fin trace	
	     */
		list ($newFr->tdr, $newFr->ndr) = compareFR($newFr->ref);
	
		if ($chap < 8)
			$chap = "0".($chap + 2);
		else 
			$chap = ($chap + 2);
		if ($schap < 8)
			$schap = "0".($schap + 2);
		else 
			$schap = ($schap + 2);

		serialiser_fiche ($newFr, $chap.$schap);
	
		$string = $string2;
	
		if ($crRecupere->chapitres[$refchap]->sousChap[$refschap + 1]->nom != ""  )
		{
			$nextchap = $chap;
			$nextschap = $refschap + 1;
			if ($nextschap < 8)
				$nextschap = "0".($nextschap + 2);
			else 
				$nextschap = ($nextschap + 2);
		}
		$refValue = $refValue.$chap.$schap.$nextchap.$nextschap;
		
		echo("<SCRIPT>parent.contenuCR.refresh_js('toc','$refValue');</SCRIPT>\n");
	
		echo("<BODY BGCOLOR=\"#FFFFES\">\n");
		echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"5\">\n");
		echo("<B>$string</B>\n");
		echo("<BR><BR>\n");
	}
	
//	echo("<BODY  ONLOAD=closePopup()>\n");
	echo("<BODY BGCOLOR=\"#FFFFES\">\n");
	echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"3\">\n");
	echo("<TD><INPUT type=button name=nfr value=\"$string3\" onClick=nfr_js()></TD></TR>\n");
	echo("</FONT>\n");
	echo("</CENTER>\n");	
	}
	
	else
	{
		echo("<BODY ONLOAD=closePopup() >\n");
		echo("<BODY BGCOLOR=\"#FFFFES\">\n");
	 	echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"5\">\n");
	 	$string = $string4;
	 	echo("<CENTER><B>$string</B><BR><BR>\n");
	 	echo("<TD><INPUT type=button name=nfr value=\"$string3\" onClick=nfr_js()></TD></TR>\n");
		echo("</FONT>\n");
	}

}

elseif ($op == newpv) {
		
	newpv();
	$repUtil = $rep;
		
	//on récupère dans l'url le nom du CR recupéré
	$file = "$racineSygTemp$nameSygTemp/$repUtil/step1/$nameCR";
	lireFichierP();
	//sérialisation
	

	/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 1143\n ";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_debut=aff_temp_ecoule(0);
	
	serialiser();
		

					/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 1154 \n ";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_ecoule=aff_temp_ecoule($t_debut);
	
	//libération de la variable - désallocation mémoire plus rapide
	unset($crRecupere);
	
	
}	
//*************************************************************************************************
	
 elseif (($op == prt) || ($op == rfsh) || ($op == ctg) || ($op == ctgY) || ($op == ctgN) || ($op == mkctg) )
 {
	///$op vaut rfsh pour "refresh". Cette opération est utilisée dans le cas où l'utilisateur
	//décoche une case correspondant à un chapitre ou un sous-chapitre. Si un chapitre est 
	//décoché alors ses sous-chapitres et/ou ses Fr sont décochées; il en est de même pour les
	//sous-chapitres avec leurs FR. Le fait de cocher ou décocher une case entraine la 
	//modification de la propriété selected de l'objet correspondant.
	//$op vaut ctg uniquement quand l'utilisateur vient de valider son choix
	//$op vaut ctgY pour la validation à savoir cocher automatiquement les chapitres et sous-chapitres
	//d'une FR s'il ne l'est pas et demander (formulaire) à l'utilisateur de renseigner
	//les informations essentielles pour le cahier de recette customisé et/ou du PV
	//Si $op vaut ctgY alors l'utilisateur a choisi de générer le Pv et il devra renseigner les infos
	//a la fois pour le PV
	//si $op vaut ctgN pas de génération de PV
	//$op vaut mkctg pour la construction du catalogue (fichier xml)
	//on vide le buffer du serveur web à cause des "header" pour le 
	//téléchargement
	if ($op != end)  {
		//on récupère la valeur du répertoire utilisateur contenu dans le champ caché du formulaire
		//Attention il s'agit du chemin absolu on va donc découper la chaine en deux pour avoir
		//d'un côté la racine et de l'autre le répertoire utilisateur mais le chemin relatif
		$repUtil = $rep;
		//désérialisation
		deserialiser();		
	}
	if ($op == prt) {
		//affichage de la page html	

		echo("</HEAD>\n");
		echo("<BODY BGCOLOR=\"#FFFFES\" ONLOAD=closePopup()>\n");
			
			$traitement_mode = $crRecupere->traitement_mode;
			$ref_ctgp = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc ;
			
		$modif_nok = 0;
		if ($traitement_mode != "")
		{
		
					//JNG trace	
			/**/
			//	$string100 =  " sygMainStep2 ligne 1068 : op = $op, traitement_mode = $traitement_mode  <br>";
			//	$string1000 =  " sygMainStep2 : Appel verf_liste_ref_ctg, ref_ctgp = $ref_ctgp <br>";
			//	erori ($string100,$string1000);
			/**/	
			//fin trace
		
			if ($traitement_mode == "MODIF")
			{
				$modif_nok = verf_liste_ref_ctg($ref_ctgp);
				if($modif_nok == 0)
					select_cr_modif();
				else
				{
					echo("<CENTER><FONT COLOR=\"#000FF\" SIZE=\"5\">\n");
					echo("<BR>\n");
					echo("<TD><B><FONT COLOR=\"#FF000\" SIZE=\"5\">$string8<B></TD>");
					echo("</CENTER><BR>\n");
					echo("</FORM>\n");
					echo("</BODY>\n");
					echo("</HTML>\n");
				}
			}			
		}
				
		//fin trace
		if($modif_nok == 0)
			afficherCR();
			//JNG trace	
			/*
				$string100 =  " sygMainStep2 : Appel afficherCR ligne 1061, traitement_mode = $traitement_mode  <br>";
				erori ($string100,"");
			*/	
			//fin trace
		//$traitement_mode = "";
	} 		
	elseif (($op == rfsh) || ($op == ctg) || ($op == ctgY) || ($op == ctgN)) {
		//$type == "toc" dans le cas où il s'agit de l'activation d'un lien de la
		//frame correspondant au sommaire. Dans ce cas là on ne fait rien, on affiche
		//tel que c'était avant l'activation du lien étant donné que celle-ci 
		//provoque un rechargement de la page cible
		if (($op == rfsh) && ($type != "toc")){
			if ($type == "ch") {
				$a_chap = &$crRecupere->chapitres[(integer)$chindex];
				$elt = $a_chap->num;
				$present = isPresent($tabChap, $elt, "chaine");
				if (($present == 0) && ($a_chap->selected == 1)) {
					//le chapitre n'est pas coché
					//il était coché avant donc on décoche ses sous-chapitres et/ou ses FR
					$a_chap->unselect();
				} elseif (($present == 1) && ($a_chap->selected == 0)) {
					//cas où le chapitre était décoché et que l'utilisateur l'a coché
					//on coche alors tous ses sous-chapitres et ses FR
					$a_chap->select();
				}
			} else {
				//$type = sch
				$a_chap = &$crRecupere->chapitres[(integer)$chindex];
				$a_sousChap = &$a_chap->sousChap[(integer)$schindex];
				$elt = $a_sousChap->num;
				$present = isPresent($tabSousChap, $elt, "chaine");
				if (($present == 0) && ($a_sousChap->selected == 1)) {
					//le sous-chapitre n'est pas coché
					//s'il était coché avant on décoche ses FR
					$a_sousChap->unselect();
				} elseif (($present == 1) && ($a_sousChap->selected == 0)) {
					//cas où le sous-chapitre était décoché et que l'utilisateur l'a coché
					//on coche alors tous ses FR
					$a_sousChap->select();
				}
			} 
		}
		
		//cas où une (ou plusieurs) Fr d'un chapitre ou sous-chapitre est cochée/decochée
		//mais pas le chapitre et le sous-chapitre	
		//qui vient d'être coché ou décoché
		//attention ch et sch valent "" quand $op == ctg || crgY || ctgN
		
		if (($op == rfsh) || ($op == ctg)) {
		

	    // Récupérer la variable chap_select sélectionné dans la function javascript refresh_js
			//	echo " sygMainStep2 ligne 1302 : op = $op, type = $type, fiche_chap = $chap_select <br>";
			
				for ( $i= 0; $i < $index +1; $i++)
				{
					$varjava = "form";
					$varjava = $varjava  .$i;
					$mat  [$i] = ( int ) $$varjava ;
					if ( $mat[$i] == 1 )
						$mat[$i] = "01";
					elseif ( $mat[$i] == 2 )
						$mat[$i] = "02";
					elseif ( $mat[$i] == 3 )
						$mat[$i] = "03";
					elseif ( $mat[$i] == 4 )
						$mat[$i] = "04";
					elseif ( $mat[$i] == 5 )
						$mat[$i] = "05";
					elseif ( $mat[$i] == 6 )
						$mat[$i] = "06";
					elseif ( $mat[$i] == 7 )
						$mat[$i] = "07";
					elseif ( $mat[$i] == 8 )
						$mat[$i] = "08";
					elseif ( $mat[$i] == 9 )
						$mat[$i] = "09";
					elseif ( $mat[$i] == "" )
						$mat[$i] = "01";
				}
				// mis a jour edition FRs
				edts();
				
			//	echo " sygMainStep2 ligne 1317 : op = $op, type = $type, fiche_chap = $ngj_fchap <br>";
				
						
			if (($op == rfsh) && ($type != "toc")) {
							
				if ($type == "ch") {
					$chapModif = &$crRecupere->chapitres[(integer)$chindex];
				} else {
					$chapModif = &$crRecupere->chapitres[(integer)$chindex];
					$sousChapModif = &$chapModif->sousChap[(integer)$schindex];
				}
			}
			for ($i = -1;$i < count ($crRecupere->chapitres) - 1;$i++) {
				$chapCourant = &$crRecupere->chapitres[$i];
				if ($i < 8)
				$refchap = "0".($i + 2);
				else 
				$refchap = $i + 2;
				$refschap = "00";
				//on vérifie que ce n'est pas le chapitre qui vient d'être modifié
				//mais uniquement quand $op == rfsh
				if (($op == rfsh) && ($type != "toc")) {
					//est-ce le chapitre qui vient d'être modifié par l'utilisateur 
					$equal = ($chapCourant->num != $chapModif->num);
				} else {
					//activation d'un lien du sommaire
					$equal = true;
				}
				if ($equal == true) {
					//les FR du chapitre
					for ($j = -1;$j < count($chapCourant->fr) - 1;$j++) {
						$fr = &$chapCourant->fr[$j];						
						if ($fr->ed == ""){
								$fr->select();
								$fr->ed = $fr ->edtmp;
						}else{
							$elt = $fr->ref.$refchap.$refschap;
							$present = isPresent($tabRefChap, $elt, "chaine");
							if ($present == 1) 
							{
								$fr->select();
							}else{
								$fr->unselect();
							}
						}	
					}
					//les sous-chapitres
					for ($j = -1;$j < count($chapCourant->sousChap) - 1;$j++) {
						if ($j < 8)
							$refschap = "0".($j + 2);
						else 
							$refschap = $j + 2;
						$ssChap = &$chapCourant->sousChap[$j];
						//on vérifie que ce n'est pas le sous-chapitre modifié
						//mais uniquement si $op == rfsh
						if (($op == rfsh) && ($type != "toc")) {
							//est-ce le sous-chapitre qui vient d'être modifié par l'utilisateur
							$equal = ((($type == "sch") && ($ssChap->num != $sousChapModif->num)) || ($type == "ch"));
						} else {
							//activation d'un lien du sommaire
							$equal = true;
						}
						if ($equal == true) {
							//les Fr du sous-chapitres
							for ($k = -1;$k < count($ssChap->fr) - 1;$k++) {
								$fr = &$ssChap->fr[$k];
							
								if ($fr->ed == ""){										
									$fr->select();
									$fr->ed = $fr ->edtmp;
								}else{
									$elt = $fr->ref.$refchap.$refschap;
									$present = isPresent($tabRefSousChap, $elt, "chaine");
									if ($present == 1){
										$fr->select();
									}else{
										$fr->unselect();
									}	
								}
							}
						}	
					}				
				} else {
					//si c'est le chapitre qui vient d'être modifié mais que c'est juste un 
					//sous-chapitre de ce dernier qui a été modifié alors ne pas modifier la valeur
					// des Fr du chapitre et des autres sous-chapitres
					if ($type == "sch") {
						//les FR du chapitre
						for ($j = -1;$j < count($chapCourant->fr) - 1;$j++) {
							if ($j < 8)
							$refschap = "0".($j + 2);
							else 
							$refschap = $j + 2;
							$fr = &$chapCourant->fr[$j];
							if ($fr->ed == ""){
									$fr->select();
									$fr->ed = $fr ->edtmp;
							}else{
								$elt = $fr->ref.$refchap.$refschap;
								$present = isPresent($tabRefChap, $elt, "chaine");
								if ($present == 1) {
									$fr->select();
								} else {
									$fr->unselect();
								}
							}
							
						}
						//les sous-chapitres
						for ($j = -1;$j < count($chapCourant->sousChap) - 1;$j++) {
							if ($j < 8)
							$refschap = "0".($j + 2);
							else 
							$refschap = $j + 2;
							$ssChap = &$chapCourant->sousChap[$j];
							//on vérifie que ce n'est pas le sous-chapitre modifié
							//mais uniquement si $op == rfsh
							if ($op == rfsh) {
								$equal = ((($type == "sch") && ($ssChap->num != $sousChapModif->num)) || ($type == "ch"));
							} else {
								$equal = true;
							}
							if ($equal == true) {
								//les Fr du sous-chapitres
								for ($k = -1;$k < count($ssChap->fr) - 1;$k++) {
									$fr = &$ssChap->fr[$k];
									if ($fr->ed == ""){
										$fr->select();
										$fr->ed = $fr ->edtmp;
									}else{	
										$elt = $fr->ref.$refchap.$refschap;
										$present = isPresent($tabRefSousChap, $elt, "chaine");
										if ($present == 1) {
											$fr->select();
										} else {
											$fr->unselect();
										}
										
									}
								}
							}	
						}
					}	
				}	
					
			}
			if ($op == rfsh) {
				//sérialisation - on la fera avant pour éviter que l'utilisateur génère
				//un évènement( clic sur une case à cocher) qui pourrait interrompre cette
				//étape
				//JNG trace
			//echo " sygMainStep2=>afficherCR ligne 1222  : op = rfsh<br>";
				//fin trace
				

	/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 1470\n ";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_debut=aff_temp_ecoule(0);
				
				serialiser();
				

	/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 1480\n ";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_ecoule=aff_temp_ecoule($t_debut);
				
				echo("</HEAD>\n");
				echo("<BODY BGCOLOR=\"#FFFFES\" ONLOAD=closePopup()>\n");
				//affichage de la page html dynamique
			//	lireFichierD();
				//JNG trace
				//echo " sygMainStep2=>ligne 1241: appel affcherCR avec le mode de traitement = $traitement_mode<br>";
				//fin trace
				afficherCR();

			} else {  
				//  $op == ctg
				//renseignement des infos sur le cahier de recette customisé : référence, titre, ...
				//on efface le sommaire
				echo("<SCRIPT>parent.sommaireCR.location.reload();</SCRIPT>\n");
				//validation: on met la propriété selected à 1 pour les chapitres ou sous-chapitres
			 	//des FR cochées.

			 	for ($i = -1;$i < count($crRecupere->chapitres) - 1; $i++) {
			 		//Fr pour ce chapitre
			 		$chapCourant = &$crRecupere->chapitres[$i];
			 		$fr_tab = &$chapCourant->fr;
					for ($k = -1;$k < count($fr_tab) - 1;$k++) {
						if (($fr_tab[$k]->selected == 1) && ($chapCourant->selected == 0))
								 $chapCourant->selected = 1;
					}
			 		//on vérifie d'abord pour les sous-chapitres
			 		$sousChap_tab = &$chapCourant->sousChap;
					for ($j = -1;$j < count($sousChap_tab) - 1;$j++) {
							//FR du sous-chapitres
						$fr_tab = &$sousChap_tab[$j]->fr;
						for ($k = -1;$k < count($fr_tab) - 1;$k++) {
							if (($fr_tab[$k]->selected == 1) && ($sousChap_tab[$j]->selected == 0))
							 		$sousChap_tab[$j]->selected = 1;
			 			}
			 				//maintenant si des sous-chapitres ont été sélectionnés 
			 				//ils seront pris en compte
						if (($sousChap_tab[$j]->selected == 1) && ($chapCourant->selected == 0))
								 $chapCourant->selected = 1;
			 				
			 		}
			 	}
		 		//sérialisation - on la fera avant pour éviter que l'utilisateur génère
				//un évènement( clic sur une case à cocher) qui pourrait interrompre cette
				//étape
				
	/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 1533\n ";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_debut=aff_temp_ecoule(0);
	
				serialiser();
				

	/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 1543\n ";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_ecoule=aff_temp_ecoule($t_debut);
			 	//si $op vaut ctg alors on va initialiser certians champs du formulaire
			 	//pays vaudra FRANCE , édition vaudra 01 et titre sera par défaut celle 
			 	//du cahier de recette générique
	
			 	$pays_ctg = "FRANCE";
			 	$edition_ctg = $crRecupere->editionDoc;

			 	//if ($edition_ctg != "01") old code
				if ($crRecupere->traitement_mode == "MODIF")
			 	{
					$ref_ctg = $crRecupere->prefixDoc .$crRecupere->numberDoc.$crRecupere->variantDoc.$crRecupere->versionDoc.$crRecupere->typeDoc.$crRecupere->zzDoc.$crRecupere->lgueDoc ;
					$titre_ctg = $crRecupere->titleDoc;
					//JNG trace 08_01_2007
					$langue_ctg = $crRecupere->lgueDoc;
					//echo "NGJ trace 08_01_2007: sygMainStep2 => ligne 1321 appel verf_FC_valide : opération = $op, ref_ctg = $ref_ctg,edition = $edition_ctg, mode du traitement = $crRecupere->traitement_mode <br>";
					//$edition_etat = verf_FC_valide($ref_ctg,$langue_ctg,$edition_ctg); //etat_edit = 1 valide, = 0 disponible
					//erori ($ref_ctg);
					//if($edition_etat!=0)
					//	$edition_ctg = "??";
					//fin trace
			 	}else {
					$titre_ctg = $crRecupere->titleDoc." - ".$pays_ctg." - ";
					$ref_ctg = "";
				}
		//NGJ 06_02_2007 trace	
		/**/
		//$string1000 =  " sygMainStep2 : Appel saisieInfos ligne 1391, recupere->mode = $crRecupere->traitement_mode  <br>";
		//$string1001 =  "  ref du catalogue : $ref_ctg <br>";
		//erori ($string1000,$string1001);
		/**/
		//fin
				
			 	saisieInfos("");
			}
		} elseif (($op == ctgY) || ($op == ctgN)) {
	 			//sérialisation - on la fera avant pour éviter que l'utilisateur génère
				//un évènement( clic sur une case à cocher) qui pourrait interrompre cette
				//étape
				
				
	/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 1589\n ";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_debut=aff_temp_ecoule(0);
	
				serialiser();
	
	

	/*
	$edition_etat = "sygMainStep2 appel à aff_temp_ecoule ligne 1600\n ";
	$string1000 = "\n";
	erori ($edition_etat,$string1000);
	*/
	//$t_ecoule=aff_temp_ecoule($t_debut);
	
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> NGJ trace 05_06_2007
				$ref_ctg = strtoupper($ref_ctg);
						//NGJ 06_02_2007 trace	
		/**/
		//$string1000 =  " sygMainStep2 : ligne 1411, recupere->mode = $crRecupere->traitement_mode  <br>";
		//$string1001 =  "  ref du catalogue apres strtoupper: $ref_ctg <br>";
		//erori ($string1000,$string1001);
		/**/
		//fin
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		$prefixCr = $ref_ctg[0].$ref_ctg[1].$ref_ctg[2];
	 	$nombreCr = "";
	 	for ($i = 3;$i < 8 ;$i++) $nombreCr .= $ref_ctg[$i];
	 	$variantCr = $ref_ctg[8].$ref_ctg[9];
	 	$versionCr = $ref_ctg[10].$ref_ctg[11];
	 	$typeCr = $ref_ctg[12].$ref_ctg[13];
	 	$zzCr = $ref_ctg[14].$ref_ctg[15];
	 	$langueCr = $ref_ctg[16];
	 		 
	 	//$crRecupere->titleDoc = $titre_ctg." - ".$pays_ctg." - "; old code NGJ 15/01/07
		$crRecupere->titleDoc = $titre_ctg;
	  	$crRecupere->prefixDoc = $prefixCr;
	 	$crRecupere->numberDoc = $nombreCr;
	 	$crRecupere->variantDoc = $variantCr;
	 	$crRecupere->versionDoc = $versionCr;
	 	$crRecupere->typeDoc = $typeCr;
	 	$crRecupere->zzDoc = $zzCr;
	 	$crRecupere->lgueDoc = $langueCr;
	 	$crRecupere->mnemonicDoc = $mne_ctg;
	 	$crRecupere->editionDoc = $edition_ctg;
	 	
	 	if ($langueCr == "A") {
	 		$desLgue = "EN";
	 	} elseif ($langueCr == "B") {
	 		$desLgue = "FR";
	 	} elseif ($langueCr == "D") {
	 		$desLgue = "SP";
	 	}
		$const_refctg=1;
		$dir_courant=getcwd(); // sauvegarde le directory courant
		chdir("$racineSygTemp$nameSygTemp/$repUtil");// se mettre dans le répertoire d'utilisateur
		//$motdepasse = relance_gediget($passwd);
		$motdepasse = "\"$passwd\"";
		$identificationCIl = "-cn $login_user -pass $motdepasse";

		if ($crRecupere->traitement_mode == "CREATE"){
			
			$identificationCIl_null="";
			system("gediget ".$ref_ctg." ".$desLgue." $identificationCIl_null > $racineSygTemp$nameSygTemp/$repUtil/resCdes", $resCde);
			
			// control de l'existance du nouveau CR customisé par gediget
			/**/
			//$string1001 = "ref_ctg = $ref_ctg , langue = $desLgue , identificationCIl = $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/resCdes, code retour = $resCde<br>";
			//$string1000 = " sygMainStep2 apres gediget op = $op : ligne 1452 <br> ";
			//erori ($string1000,$string1001);
			/**/
			if ($resCde == 0) {
				//suppression le fichier ref existé 				
				$files_root = list_dir($racineSygTemp.$nameSygTemp."/".$repUtil."/");
				foreach($files_root as $file) {
				//recherche le fichier de type 3BL48849TTAAQSAGB_03_FR.xzip
					if (ereg(".xzip$",$file)){
						$tab_car_spe = explode( "_",$file);
						list($ref_CR,$edit_CR ,$langue_CR) = explode ("_", $file);
						if (strcmp($ref_CR,$ref_ctg) == 0){// return 1; //true
							if ($langue_browser == "FR"){							
								$string100 = "Le cahier recette customisé à créer : $ref_CR existe déjà en édition = $edit_CR";
							}else{
								$string100 = "The customised acceptance book to create: $ref_CR already exist in edition = $edit_CR";
							}
							system("rm -fR $racineSygTemp$nameSygTemp/$repUtil/$file");			
						}
					} elseif (ereg(".doc$",$file)) {
						
						if ($langue_browser == "FR"){								
							$string100 = "La référence à créer : $ref_ctg existe déjà ";
						}else{
							$string100 = "The reference to create: $ref_ctg already exist ";
						}
							system("rm -fR $racineSygTemp$nameSygTemp/$repUtil/$file");
					}
				}
				//$string100 = "La ref. : $ref_ctg du cahier recette customisé demandée existe déjà";
				echo("<CENTER>\n");
				echo("<FONT COLOR=\"#FF0000\" SIZE=\"4\">\n");
				echo("$string100\n");
				echo("</FONT>\n");
				echo("<HR>\n");
				
				$ref_ctg = "";
				
				
			}
			chdir("$dir_courant");//revient à la repertoire courant = = /home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe
		}		
		
				//NGJ 06_02_2007 trace	
		/**/
		//$string1000 =  " sygMainStep2 : Appel saisieInfos ligne 1497, recupere->mode = $crRecupere->traitement_mode  <br>";
		//$string1001 =  "  ref du catalogue : $ref_ctg <br>";
		//erori ($string1000,$string1001);
		/**/
		//fin
		
		saisieInfos("");
		
		}
	} elseif($op == mkctg) {
	 	//on récupère les infos que l'utilisateur a fourni pour le
	 	//cahier de recette customisé	
		
		// convertir la ref du document en majuscule
		$ref_ctg = strtoupper($ref_ctg);
				//NGJ 06_02_2007 trace	
		/**/
		//$string1000 =  " sygMainStep2 : Appel ligne 1520, recupere->mode = $crRecupere->traitement_mode  <br>";
		//$string1001 =  "  ref du catalogue apres strtoupper: $ref_ctg <br>";
		//erori ($string1000,$string1001);
		/**/
		//fin
		
	 	$prefixCr = $ref_ctg[0].$ref_ctg[1].$ref_ctg[2];
	 	$nombreCr = "";
	 	for ($i = 3;$i < 8 ;$i++) $nombreCr .= $ref_ctg[$i];
	 	$variantCr = $ref_ctg[8].$ref_ctg[9];
	 	$versionCr = $ref_ctg[10].$ref_ctg[11];
	 	$typeCr = $ref_ctg[12].$ref_ctg[13];
	 	$zzCr = $ref_ctg[14].$ref_ctg[15];
	 	$langueCr = $ref_ctg[16];
	 		 
	 	//$crRecupere->titleDoc = $titre_ctg." - ".$pays_ctg." - "; old code NGJ 15/01/07
		$crRecupere->titleDoc = $titre_ctg;
	  	$crRecupere->prefixDoc = $prefixCr;
	 	$crRecupere->numberDoc = $nombreCr;
	 	$crRecupere->variantDoc = $variantCr;
	 	$crRecupere->versionDoc = $versionCr;
	 	$crRecupere->typeDoc = $typeCr;
	 	$crRecupere->zzDoc = $zzCr;
	 	$crRecupere->lgueDoc = $langueCr;
	 	$crRecupere->mnemonicDoc = $mne_ctg;
	 	$crRecupere->editionDoc = $edition_ctg;
	 	
	 	if ($langueCr == "A") {
	 		$desLgue = "EN";
	 	} elseif ($langueCr == "B") {
	 		$desLgue = "FR";
	 	} elseif ($langueCr == "D") {
	 		$desLgue = "SP";
	 	}
	 	
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> NGJ trace 07_02_2007
				//
		$const_refctg=1;
		$dir_courant=getcwd(); // sauvegarde le directory courant
		chdir("$racineSygTemp$nameSygTemp/$repUtil");// se mettre dans le répertoire d'utilisateur
		//$motdepasse = relance_gediget($passwd); // contrôle les caractères speciaux dans le mot de passe
		$motdepasse = "\"$passwd\"";
    $identificationCIl = "-cn $login_user -pass $motdepasse";

		if ($crRecupere->traitement_mode == "CREATE"){
			$resCde = 0;
			$identificationCIl_null="";
			system("gediget ".$ref_ctg." ".$desLgue." $identificationCIl_null > $racineSygTemp$nameSygTemp/$repUtil/resCdes", $resCde);
			
			// control de l'existance du nouveau CR customisé par gediget
			//$string1001 = "ref_ctg = $ref_ctg , langue = $desLgue , identificationCIl = $identificationCIl_null , resCde = $resCde <br>";
			//$string1000 = " sygMainStep2 op = $op : apres gediget ligne 1557 <br> ";
			//erori ($string1000,$string1001);
			
			if ($resCde == 0) {
				// le fichier customisé est existé dans la base gedi alors
				//suppression le fichier ref existé et sortir en faute
				$files_root = list_dir($racineSygTemp.$nameSygTemp."/".$repUtil."/");
				foreach($files_root as $file) {
				//recherche le fichier de type 3BL48849TTAAQSAGB_03_FR.xzip
					//$edition_etat = "sygMainStep2 => ligne 1545 : dir_courant = $racineSygTemp$nameSygTemp/$repUtil/";
					//$string1000 = " fichier = $file";
					//erori ($edition_etat,$string1000);
					if (ereg(".xzip$",$file)){
						$tab_car_spe = explode( "_",$file);
						list($ref_CR,$edit_CR ,$langue_CR) = explode ("_", $file);
						if (strcmp($ref_CR,$ref_ctg) == 0){// return 1; //true
						//$edition_etat = "sygMainStep2 => ligne 1432 : fichier à supprimer = $racineSygTemp$nameSygTemp/$repUtil/$file";
						//$string100 = " fichier = $file";
						//erori ($edition_etat,"");
						if ($langue_browser == "FR"){								
							$string100 = "Le cahier recette customisé à créer : $ref_CR existe déjà en édition = $edit_CR";
						}else{
							$string100 = "The customised acceptance book to create: $ref_CR already exist in edition = $edit_CR";
						}
							system("rm -fR $racineSygTemp$nameSygTemp/$repUtil/$file");			
						}
					} elseif (ereg(".doc$",$file)) {
						
						if ($langue_browser == "FR"){								
							$string100 = "La référence à créer : $ref_ctg existe déjà ";
						}else{
							$string100 = "The reference to create: $ref_ctg already exist ";
						}
							system("rm -fR $racineSygTemp$nameSygTemp/$repUtil/$file");
					}else{
						if ($langue_browser == "FR"){								
							$string100 = "La référence à créer : $ref_ctg existe déjà ";
						}else{
							$string100 = "The reference to create: $ref_ctg already exist ";
						}
					}
				}
				/*
				echo("<CENTER>\n");
				echo("<FONT COLOR=\"#FF0000\" SIZE=\"4\">\n");
				echo("$string100\n");// "La ref. : $ref_ctg du cahier recette customisé demandée existe déjà"
				echo("</FONT>\n");
				echo("<HR>\n");
				*/
				/**/
				//$string1000 =  " sygMainStep2 op = $op  : Appel saisieInfos en mode de création ligne 1576, recupere->mode = $crRecupere->traitement_mode  <br>";
				//$string1001 =  "  ref du catalogue : $ref_ctg , crRecupere->etatDoc = $crRecupere->etatDoc<br>";
				//erori ($string1000,$string1001);
				/**/
				
				$ref_ctg = "";
				saisieInfos($string100);
				$const_refctg=0;
			}
			chdir("$dir_courant");//revient à la repertoire courant = = /home/nguyen6/aww/las43310/apache/8888/htdocs/sygafe
		}
		if($const_refctg == 1){
	 	//on ne mettra pas l'extension .xml afin d'utiliser le nom pour le fichier xzip
	 	$nameCrCusto = $ref_ctg."_".$desLgue."_".$crRecupere->editionDoc;
	 	//par contre ne pas oublier de mettre l'extension .xml ici
	 	//génération du cahier de recette customisé
	 	if (!$CrCusto = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/$nameCrCusto.xml","w")) {
	 		die("Impossible de creer le catalogue customisé");
	 	} else { 

	 		creerCrCusto($CrCusto);	

	 		fclose($CrCusto);
	 	}
	 	chdir("$racineSygTemp$nameSygTemp/$repUtil/step2");
	 	//on compresse le cahier de recette customisé avec la commande zip 

		system("zip $nameCrCusto.xzip $nameCrCusto.xml >> $racineSygTemp$nameSygTemp/$repUtil/resCdes");
	 	//on supprime le fichier xml pour libérer de la place sur le disque
		system("rm -f $racineSygTemp$nameSygTemp/$repUtil/step2/$nameCrCusto.xml");
		
	 	if ($genPv == oui) {
	 	 	//génération éventuelle du PV
		 	//on récupère les infos que l'utilisateur a fourni pour le PV
			
			//convertir le pv en majuscule
			$ref_pv = strtoupper($ref_pv);
			
			
		 	$prefixPv = $ref_pv[0].$ref_pv[1].$ref_pv[2];
		 	$nombrePv = "";
		 	for ($i = 3;$i < 8 ;$i++) $nombrePv .= $ref_pv[$i];
		 	$variantPv = $ref_pv[8].$ref_pv[9];
		 	$versionPv = $ref_pv[10].$ref_pv[11];
		 	$typePv = $ref_pv[12].$ref_pv[13];
		 	$zzPv = $ref_pv[14].$ref_pv[15];
		 	$languePv = $ref_pv[16];
		 	//on modifie les infos principales de crRecupere pour la génération du PV 
		 	//on ajoutera au titre du pv le pays
		 	//$crRecupere->titleDoc = $titre_pv." - ".$pays_ctg." - "; old code NGJ 23_01_2007
			$crRecupere->titleDoc = $titre_pv;
			$crRecupere->prefixDoc = $prefixPv;
		 	$crRecupere->numberDoc = $nombrePv;
		 	$crRecupere->variantDoc = $variantPv;
		 	$crRecupere->versionDoc = $versionPv;
		 	$crRecupere->typeDoc = $typePv;
		 	$crRecupere->zzDoc = $zzPv;
		 	$crRecupere->lgueDoc = $languePv;
		 	$crRecupere->mnemonicDoc = $mne_pv;
		 	$crRecupere->editionDoc = $edition_pv;
		 	
		 	if ($languePv == "A") {
		 		$desLgue = "EN";
		 	} elseif ($languePv == "B") {
		 		$desLgue = "FR";
		 	} elseif ($languePv == "D") {
		 		$desLgue = "SP";
		 	}
		 	
		 	$namePv = $ref_pv."_".$desLgue."_".$crRecupere->editionDoc;
		 	if (!$Pv = fopen("$racineSygTemp$nameSygTemp/$repUtil/step2/$namePv.xml","w")) {
		 		die("$string7");
		 	} else { 		
		 		creerPv($Pv);	
		 		fclose($Pv);
		 	}
			 chdir("$racineSygTemp$nameSygTemp/$repUtil/step2");
			//on compresse le PV avec la commande zip 
			system("zip $namePv.xzip $namePv.xml >> $racineSygTemp$nameSygTemp/$repUtil/resCdes");
			//on supprime le fichier xml pour libérer de la place sur le disque
			system("rm -f $racineSygTemp$nameSygTemp/$repUtil/step2/$namePv.xml"); 
		 }
	 	
	 	//récuperation FRs du CR customose du GEDI
		//cette fonction n'est pas encore finalisee pour ca la variable $gen_arch -> "off"

		//$gen_arch = "off";
		$nb_tt_zippee = 0;
	 	if ($gen_arch == "on")
	 	{
	 		$path_download = $racineSygTemp.$nameSygTemp."/".$repUtil."/step3";
	 		//saveFR();
			//$nbfiche_select = nbficherecette_info();
			//echo " JNG SygMainStep2-ligne 1406 : appel cre_f_zip_chap() : $nb_tt_zippee <= $nbfiche_select_max <br>";
			$nb_tt_zippee=cre_f_zip_chap();
					//$edition_etat = "sygMainStep2 => ligne 1714 , nombre de fiches à zipper = $nb_tt_zippee";
					//$string1000 = " nb_tt_zippee($nb_tt_zippee) <= nbfiche_select_max($nbfiche_select_max)";
					//erori ($edition_etat,$string1000);
			if ($nb_tt_zippee <= $nbfiche_select_max){
				
				cre_zip_CR();
			}
	 	}	
		//affichage de la dernière fenêtre - liens vers les fichiers xzip
		lastWindow();
		} // fin si $const_refctg == 1
	}
} else {

	//erreur

	
}

?>



