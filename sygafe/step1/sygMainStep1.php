<?php

/****************************************************************************************/
/********************************** sygMainStep1.php ************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: construction du formulaire et mise à jour dynamique des listes de	*/
/* valeur pour l'obtention d'un cahier de recette générique d'un projet.		*/
/*  											*/
/* fonctions:										*/
/*  											*/
/****************************************************************************************/

echo("<HTML>\n");
echo("<HEAD>\n");

?>
<!- fonctions JavaScript ->
<SCRIPT>

function getcookie(cookiename)
{
	var index = document.cookie.indexOf(cookiename);
	
	namestart = (document.cookie.indexOf("=", index) + 1);
	nameend = document.cookie.indexOf(";", index);
	thecookie = document.cookie.substring(namestart, nameend);
	return thecookie;
}
//variable  : référence sur la fenêtre popup
var w;

//permet de savoir si une action est déjà en cours
var action = 0;

//identifiant saisi - on lance SYGAFE
function logSygafe_js()
{
	var Langue = getcookie('Langue');
	action = 1;
	
	if (document.logForm.log_name.value.length == 0) {
		if (Langue == "FR") 
			alert('Vous devez saisir un identifiant !');
		else
			alert('You must identifie yourself !');
		
		action = 0;
	} else {
		document.logForm.submit();
	}
}
//*****
function logSygafe1_js()
{
	document.logForm.submit();
}

function delta_refs_js()
{
	document.firstPage.action = "sygMain.php?op=DLT";
	document.firstPage.submit();
}

function check_ref_js()
{

	var Langue = getcookie('Langue');
	var reg = new RegExp("^[0-9]{1}$","i");
	var car1 = 0;
	var car2 = 0;
	var long_ref = 17;
	

	// Verification du reference 1
	ref1value = document.delta.ref1.value;
	ref1value = ref1value.toUpperCase();
	
	if (document.delta.ref1.value.length != 17){
		if (Langue == "FR"){ 
					alert("Les références = "+ref1value+" doit avoir 17 caractères (Ex: 3BW8086AAAAADAHB) !");
		}else{
					alert("Reference = "+ref1value+" must have 17 characters in length (Eg: 3BW8086AAAAADAHA)!");
		}
		action = 0;
		long_ref = 0;
	}else{
		if (document.delta.ref2.value.length  != 17){
			if (Langue == "FR"){ 
				alert("Nok, La référence = "+ref2value+" doit avoir 17 caractères (Ex: 3BW8086AAAAADAHB) !");
			}else{
				alert("Nok,Reference = "+ref2value+" must have 17 characters in length (Eg: 3BW8086AAAAADAHA)!");
			}
			action = 0;
			long_ref = 0;
		}else{
			if ((reg.test(ref1value.substr(3, 1))) &&
				(reg.test(ref1value.substr(4, 1))) && 
				(reg.test(ref1value.substr(5, 1))) && 
				(reg.test(ref1value.substr(6, 1))) &&
				(reg.test(ref1value.substr(7, 1))))	{
				car1 = 0;
					ref2value = document.delta.ref2.value;
					ref2value = ref2value.toUpperCase();
					if ((reg.test(ref2value.substr(3, 1))) &&
						(reg.test(ref2value.substr(4, 1))) && 
						(reg.test(ref2value.substr(5, 1))) && 
						(reg.test(ref2value.substr(6, 1))) &&
						(reg.test(ref2value.substr(7, 1)))){
						car2 = 0;
					}else{
						car2 = 1;
					}
			}else{
				car1 = 1;
			}  // fin de test sur les 5 chiffres du ref 1
		} // fin de test sur la longueur du ref2
	}// fin de test sur la longueur du ref1

	
	if ((car1 != 0) || (car2 != 0)){

		if (Langue == "FR"){
				if(car1 == 1)
					alert(" La référence 1 = "+ref1value+" est erronée !! Vérifier:  \n la tranche de code doit être des chiffres ");
				if(car2 == 1)
					alert(" La référence 2 = "+ref2value+" est erronée !! Vérifier:  \n la tranche de code doit être des chiffres ");
		}else{
			if(car1 == 1)
				alert(" Bad reference 1 = "+ref1value+" !! Check the followings: \n the section of code  must be in figures ");
			if(car2 == 1)
				alert(" Bad reference 2 = "+ref2value+" !! Check the followings: \n the section of code  must be in figures ");				
		}
	// fin  nok	
	}else{
		if (long_ref == 17){
			openPopup(20, 12, 'waitForm', 'resizable=0');
			document.delta.action = "sygMain.php?op=LIR";
			document.delta.submit();
		}
	}
}

function new_PV_js()
{


	document.firstPage.action = "sygMain.php?op=newpv";
	document.firstPage.submit();
		
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

function reconex_js()
{
	document.rcnx.action = "sygMain.php?op=NCNX";
	document.rcnx.submit();
}
//*****	

//cette fonction met le focus sur la première zone de saisie qd le formulaire
//est chargé
function begin()
{
	document.logForm.log_name.focus();
}

//l'utilisateur a fait une recherche de systèmes
//on appelle le script php pour remettre la liste des
//systèmes à jour
function afficheSys_js()
{
	if (action != 1){
		action = 1;
		document.firstPage.action = "sygMain.php?op=RS";
		document.firstPage.submit();
	}
}

//affichage de l'ambulance pour l'image correspondant à l'aide
//quand l'utilisateur passe la souris dessus
function helpOver_js()
{
	document.images["helpImg"].src="images/ambulance1.jpg";
}

//on affiche à nouveau le livre pour l'aide
function helpOut_js()
{
	document.images["helpImg"].src="images/help.jpg";
}

//l'utilisateur a sélectionné un système
//mise à jour et affichage de la liste des projets
//pour ce système
function afficheP_js()
{	
	if (action != 1) {
		action = 1;
		document.firstPage.action = "sygMain.php?op=RP";
		document.firstPage.submit();
	}
}

//l'utilisateur a sélectionné un projet
//mise à jour et affichage des langues de CR dispos pour ce projet
function afficheLgue_js()
{
	if (action != 1) {
		action = 1;
		document.firstPage.action = "sygMain.php?op=EF";
		document.firstPage.submit();
	}
}

//récupération du cahier de recette
function recupererCR_js()
{


	if (action != 1) {
		var Langue = getcookie('Langue');
		action = 1;
		if ((document.firstPage.ref_cr.value.length == 0) && (document.firstPage.selection_lgue.selectedIndex == 0)) {
			if (Langue == "FR") 
				alert("Vous devez sélectionner la langue !");
			else
				alert("You must select a language!");
			
			action = 0;
		} else {
			openPopup(20, 12, 'waitForm', 'resizable=0');
			document.firstPage.action = "sygMain.php?op=CR";
			document.firstPage.submit();
		}
	}
}

function recupererCR_PV_js()
{
	if (action != 1) {
		var Langue = getcookie('Langue');
		action = 1;
		if ((document.firstPage.ref_cr.value.length == 0) && (document.firstPage.selection_lgue.selectedIndex == 0)) {
			if (Langue == "FR") 
				alert("Vous devez sélectionner la langue !");
			else
				alert("You must select a language!");
			action = 0;
		} else 
		{
			openPopup(20, 12, 'waitForm', 'resizable=0');
			document.firstPage.action = "sygMain.php?op=CR&pv=1";
			document.firstPage.submit();
			
			
		}
	}
}

//cette fonction affiche un Popup centré
//pour nous il permet de signaler à l'utilisateur de patienter un peu
function openPopup(width, height, windowName, featureString)
{
	if (!windowName)
		windowname = '';
	if (!featureString) {
		featureString = '';
	} else {
		featureString = ',' + featureString;
	}
	var x = Math.round((screen.availWidth - width) / 2);
	var y = Math.round((screen.availHeight - height) / 2);
	featureString = 'left=' + x + ',top=' + y + ',width=' + width + ',height=' + height + featureString;
	
	w = open('about:blank','waitForm',featureString);
	var html = '';
	html = '<HTML><BODY><CENTER><IMG SRC=\"images/timer.jpg\" name=\"horloge\" width=\"56\" height=\"80\" border=\"0\"></CENTER></BODY><\/HTML>';
	w.document.open();
	w.document.write(html);
	w.document.close();
}	

//cette fonction permet de fermer la fenêtre popup
//on appelle à nouveau waitForm() pour obtenir une référence
//sur la fenêtre afin de la fermer
function closeWaitForm()
{
	//openPopup(20, 12, 'waitForm', 'resizable=0');
	w = open('about:blank', 'waitForm', 'resizable=0');
	w.close();
}

</SCRIPT>
<?php

//JNG 
	global $traitement_mode,$DEBUG_FLAG;
	//$passwd = $HTTP_COOKIE_VARS[PASSWD];
	$passwd = $HTTP_COOKIE_VARS[Cip];
	$cil = $HTTP_COOKIE_VARS[Login];
	$login_user = "\"$cil\"";
	
// echo " sygMainStep1 ligne 315 : User=$HTTP_ENV_VARS[USER];csl=$HTTP_ENV_VARS[LOGNAME];;passwd = $HTTP_COOKIE_VARS[Cip];cil = $HTTP_COOKIE_VARS[Login];<br>";

	
//fin JNG



	if (isset($HTTP_COOKIE_VARS[Langue]) && $op != "DCX") 
		$langue_browser = $HTTP_COOKIE_VARS[Langue];


if ($langue_browser == "FR")
		{
			$string1="La dernière session ne s'est pas terminée correctement.";	
			$string2="Vérifier si vous avez une session en cours.";
			$string3=" SE RECONNECTER ";
			$string4="Impossible de créer le fichier gedi.ini";
			$string5="Obtenir le cahier de recette";
			$string6="Erreur : document inexistant";	
			$string7="Reintroduire les Références pour les cahiers de recette";
			$string8="EXECUTION DE LA REQUETE GEDI";
			$string9="Obtenir un autre cahier de recette ";
			//JNG 28-09-2006
			$string10="Veuillez relancer GEDI puis le fermer par le bouton Logout dans le menu";
			$string11 = "!!! La référence à comparer n'est pas de type format xml !!!";
			//fin
		}
	else
		{
			$string1="The previous session was not correctly ended.";
			$string2="Please verify if you have an opened session.";
			$string3=" RELOGIN ";
			$string4="Can not create gedi.ini file.";
			$string5="Obtain an acceptance book";
			$string6="Error : document not found";
			$string7="Please insert the References of the acceptance books";
			$string8="GEDI EXECUTION RESULT";
			$string9="Obtain an other acceptance book";
			//JNG 28-09-2006
			$string10="Please relaunch GEDI and then close it using the button Logout in the menu bar";
			$string11 = "!!! The reference to compare: is not of type format xml !!!";
			//fin warning
			
		}


//le type d'opération $op est accessible étant donné que ce fichier est inclus dans le
//fichier SygMain.php qui lui reçoit le code opération $op

if ($op == INIT) {

	//création du lien vers le manuel d'utilisateur de SYGAFE

	createSygTemp(); //création du répertoire de stocckage si absent
		//création du lien vers le manuel d'utilisateur de SYGAFE

	deleteRepUtil(); //suppression des répertoires utilisateurs

	//page d'accueil - entrer d'un nom d'utilisateur
	logForm();
	

} 
//*****
elseif ($op == DLT)
{
affich();
}

elseif ($op == NCNX)
{

$repUtil = $csl;

chdir($racineSygTemp.$nameSygTemp);
system("rm -r $repUtil");

echo("<BODY BGCOLOR=\"#FFFFES\" ONLOAD=\"window.location='$urlSygafe'\">\n");
}

elseif ($op == RCNX)
{
	echo("<BODY BGCOLOR=\"#FFFFES\" BACKGROUND=\"images/fond.jpg\">\n");
	
	echo("<FONT COLOR=\"#0000FF\" SIZE=\"8\">\n");
	echo("<CENTER><B>SYGAFE<B> <P>\n");
	echo("<FONT COLOR=\"#FF0000\" SIZE=\"5\">\n");
	echo("<B>$string1<B><P>\n");
	echo("<B>$string2<B>\n");
	
	echo("<FORM method=post action=sygMain.php?op=RCNX name=rcnx>\n");
	echo("<FONT COLOR=\"#FF0000\" SIZE=\"4\">\n");
	echo("<FORM><INPUT type=button name=home value=\" $string3 \" onClick=\"reconex_js()\"></FORM></CENTER>\n");
	
	
	echo("<TABLE width=100%>\n");
	echo("<TD width=5% align=left><FONT COLOR=\"#000000\" SIZE=\"2\">\n");
	if ($langue_browser == "FR"){

		echo("<A HREF=".$urlSygafe."MANUEL_UTILISATION.pdf TARGET=\"_blank\" onmouseOver=\"helpOver_js()\" onmouseOut=\"helpOut_js()\"><IMG SRC=\"".$urlSygafe."images/help.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	}else{

		echo("<A HREF=".$urlSygafe."USER_MANUAL.pdf TARGET=\"_blank\" onmouseOver=\"helpOver_js()\" onmouseOut=\"helpOut_js()\"><IMG SRC=\"".$urlSygafe."images/help.jpg\" name=\"helpImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	}
	echo("<TD width=13% align=right><FONT COLOR=\"#000000\" SIZE=\"3\">\n");
	echo("<A HREF=\"mailto:jean-francois.treyssat@alcatel.fr\"><IMG SRC=\"images/mail.jpg\" name=\"mailImg\" width=\"32\" height=\"32\" border=\"0\"></A>\n");
	
	echo("</FONT></TD></TR>\n");
	echo("</TABLE>\n");
	
	echo("</BODY>\n");
	echo("</HTML>\n");
}

elseif ($op == DCX)
{
$repUtil = $csl;


chdir($racineSygTemp.$nameSygTemp);
system("rm -r $repUtil");

echo("<TITLE> SYGAFE </TITLE>\n");	
echo("</HEAD>\n");
echo("<HEAD>\n");

if($home != 1 )
{
	echo("<BODY onload = \"parent.window.location='$urlSygafe/CIL/formulaire.html'\">\n");
	setcookie ("CONTOR", "OFF"); 
}
else
{
	if ($langue_browser != "")
	{
		setcookie ("Langue", $langue_browser, time()+31536000);  
		$HTTP_COOKIE_VARS[Langue] = $langue_browser;
	}
	
	echo("<BODY onload = \"parent.window.location='$urlSygafe'\">\n");
		
}

}


elseif ($op == LIR)
{
// aducere cr din gedi + dezipare -> 2 fisiere XML
//am aici cele 2 referinte $ref1 si $ref2

$repUtil = $csl;
system("rm  $racineSygTemp$nameSygTemp/$repUtil/delta/*"); //*//

// convertir en majuscule
$ref1 = strtoupper($ref1);
$ref2 = strtoupper($ref2);
//fin
$nomRef1 = fileGedi ($ref1);
$res1 = $res;
if ($res1 != 0)
	$refc1 = $refc;
$nomRef2 = fileGedi ($ref2);
$res2 = $res;
if ($res2 != 0)
	$refc2 = $refc;
		//  NGJ 25_06_07
		//	$string1000 =  " sygMainStep1 ligne 522 : op = $op comparaison de 2 fiches de recette <br>";
		//	$string1001 =  "ref1 = $ref1, ref2 = $ref2 , code de retour filegedi : res1 = $res1 , res2 = $res2<br>";
		//	erori ($string1000,$string1001);
		//
	if (($res1 != 0) or ($res2 != 0)) {
		if (($res1 == 11) or ($res2 == 11)) {
			echo("<BR><BR>\n");
			echo("<BODY  ONLOAD=closeWaitForm()>\n");
			echo("<FONT COLOR=\"#000000\" SIZE=\"5\">\n");
			echo("<CENTER><B>$string11<BR>$refc1<BR>$refc2</B><BR><BR>\n");
			echo("</FONT>\n"); 	
			echo("<BR><CENTER><A HREF=\"".$urlSygafe."sygMain.php?op=DLT\">$string7</A><BR>\n");
		}else{
			echo("<BR><BR>\n");
			echo("<BODY  ONLOAD=closeWaitForm()>\n");
			echo("<FONT COLOR=\"#000000\" SIZE=\"5\">\n");
			echo("<CENTER><B>$string6<BR>$refc1<BR>$refc2</B><BR><BR>\n");
			echo("</FONT>\n"); 	
			echo("<BR><CENTER><A HREF=\"".$urlSygafe."sygMain.php?op=DLT\">$string7</A><BR>\n");		 	
		}
	}else
		header("Location: ".$urlSygafe."sygMain.php?op=com&nomRef2=$nomRef2&rep=$repUtil&nomRef1=$nomRef1");


}  // fin  si ($op == LIR)
//*****

elseif ($op == RAS) {
//$op = RAS : il s'agit de la page de démarrage pour l'obtention 
//d'un cahier de recette
	
	if (!eregi('msie', $HTTP_SERVER_VARS['HTTP_USER_AGENT']))
	{	echo "<b>Warning !</b>This software has been developed and tested with \"Internet Explorer\"<br>";
		echo "Using an another browser could lead to unexpected results !<br>";
	}

	//on crée le répertoire utilisateur avec pour nom l'identifiant fourni
	//si un répertoire de même nom existe, on le supprime 
	//*****
  if ($csl != ""){
	$repUtil = "$csl";
	$log_name = "$csl";


	if (!isset($HTTP_COOKIE_VARS[Langue]) ) 
		{
		/* Initialiser la langue française par defaut
			setcookie ("Langue", "FR", time()+31536000);  
			$HTTP_COOKIE_VARS[Langue] = "FR";
			$langue_browser = "FR";
		*/	
		// Initialiser la langue Anglaise par defaut
			setcookie ("Langue", "EN", time()+31536000);  
			$HTTP_COOKIE_VARS[Langue] = "EN";
			$langue_browser = "EN";
		}
	else
		$langue_browser = $HTTP_COOKIE_VARS[Langue];
		
	$repertoires = list_dir($racineSygTemp.$nameSygTemp);
	
	foreach($repertoires as $rep)
	{
		if ($rep == $log_name)
		{
			$cnxpresent = 1;	
		}
	}
	
	if ($cnxpresent == 1 )
	{	
		header("Location: ".$urlSygafe."sygMain.php?op=RCNX");
	}else{
	
	// Suppression du répertoire d'utilisateur
	system("chmod -R 777 $racineSygTemp$nameSygTemp/$repUtil");	
	system("rm -fR $racineSygTemp$nameSygTemp/$repUtil");

	// Recréation du répertoire d'utilisateur
	system("mkdir $racineSygTemp$nameSygTemp/$repUtil");
	system("chmod 775 $racineSygTemp$nameSygTemp/$repUtil");

	// Création du répertoire step1
	system("mkdir $racineSygTemp$nameSygTemp/$repUtil/step1");
	system("chmod 775 $racineSygTemp$nameSygTemp/$repUtil/step1");
	// Création du répertoire step2	
	system("mkdir $racineSygTemp$nameSygTemp/$repUtil/step2");
	system("chmod 775 $racineSygTemp$nameSygTemp/$repUtil/step2");
	// Création du répertoire step3
	system("mkdir $racineSygTemp$nameSygTemp/$repUtil/step3");
	system("chmod 775 $racineSygTemp$nameSygTemp/$repUtil/step3");
	// Création du répertoire delta
	system("mkdir $racineSygTemp$nameSygTemp/$repUtil/delta");
	system("chmod 775 $racineSygTemp$nameSygTemp/$repUtil/delta");
	// copier le fichier de configuration du gedi configparam.xml pour l'utilisateur
	system("cp   /agl/tools/utils/gedicmd/current/configparam.xml $racineSygTemp$nameSygTemp/$repUtil");
	
	$counter = $racineSygTemp.$nameSygTemp."/counter";
	$date_counter = $racineSygTemp.$nameSygTemp."/date_counter";

	if ($HTTP_COOKIE_VARS[CONTOR] != "ON") 
	{
		global $visitcnt;	
		global $file_count_date; //JNG ajout le 26/10/2006
		$cnts = 0; 
		$date_cnts = 0;
		$files_root = list_dir($racineSygTemp.$nameSygTemp);
		foreach($files_root as $file)
		{
			if (ereg("counter", $file)) 
				{
					$cnts = 1;
				}
			if (ereg("date_counter", $file)) 
				{
					$date_cnts = 1;
				}
		}
		if ($cnts == 0)
		{
			$fic = fopen($counter, "a");
			fwrite ($fic, 1);	
			fclose ($fic);
			
			$file_count_date = date("j.n.Y", fileatime($counter));
			$fic = fopen($date_counter, "a");
			fwrite ($fic, $file_count_date);	
			fclose ($fic);
			
		}else{
			$fic = fopen($counter, "r");
			$visitcnt = fread ($fic, filesize ($counter) );
			fclose ($fic);
			$fic = fopen($counter, "w");
			$visitcnt ++;
			fwrite ($fic, $visitcnt);	
			fclose ($fic);
			
			if ($date_cnts == 1)
			{
				$fic = fopen($date_counter, "r");
				$file_count_date = fread ($fic, filesize ($date_counter) );
				fclose ($fic);
			}
			else{
			
				system("rm  $counter");
				$fic = fopen($counter, "a");
				fwrite ($fic, 1);	
				fclose ($fic);
				
				$file_count_date = date("j.n.Y", fileatime($counter));
				$fic = fopen($date_counter, "a");
				fwrite ($fic, $file_count_date);	
				fclose ($fic);
			}
			
		}
			setcookie ("CONTOR", "ON"); 
	}	

	
	$fic = fopen($counter, "r");
	$visitcnt = fread ($fic, filesize ($counter) );
	fclose ($fic);
	
	$fic = fopen($date_counter, "r");
	$file_count_date = fread ($fic, filesize ($date_counter) );
	fclose ($fic);
	
	// création du fichier gedi.ini
	cre_gediInit("$racineSygTemp$nameSygTemp/$repUtil/gedi.ini");
	
	//on lit les données du fichier XML $file_p
	lireFichierP();

	//sérialisation de la liste des systèmes et des systèmes retenus
	$fp = fopen("$racineSygTemp$nameSygTemp/$repUtil/step1/systemes.syg","w");
	$s = serialize($systemes);

	fputs($fp,$s);
	fclose($fp);
	
	$systemesRetenus = $systemes; 	//par défaut on affichera dans le <select>
					//l'ensemble des systèmes 
	$fp = fopen("$racineSygTemp$nameSygTemp/$repUtil/step1/sysRetenus.syg","w");
	//      $s = serialize($systemesRetenus);       //      keep already built $s		//	jpg/20100111
	fputs($fp,$s);
	fclose($fp);
	
	entetePage1();
	
	//creation du select dynamique pour les systemes retenus 	
	creerSelectSys($systemesRetenus);
	
	echo("</TABLE><BR>\n");
	
	accesDirect();
	
	//delta();
	
		piedDePage1();
	}
	}	
	
} else {

	
	//on récupère la valeur du répertoire utilisateur contenu dans le champ caché du formulaire
	//Attention il s'agit du chemin absolu on va donc découper la chaine en deux pour avoir
	//d'un côté la racine et de l'autre le répertoire utilisateur
	
	$repUtil = $rep;

	if ($op == RS) {
		//RS : recherche de systemes
		
	  // recherche des systèmes suivant le critère entré par l'utilisateur
	  // affichage de la liste des systèmes retenus
		
		$s = implode("", @file("$racineSygTemp$nameSygTemp/$repUtil/step1/systemes.syg"));
	 	$systemes = unserialize($s);
		
		entetePage1();
		
		rechercherSystemes($sys_search);
	
		$fp = fopen("$racineSygTemp$nameSygTemp/$repUtil/step1/sysRetenus.syg","w");
		$s = serialize($systemesRetenus);
		fputs($fp,$s);
		fclose($fp);
		
		//creation du select dynamique pour les systemes retenus 	
		creerSelectSys($systemesRetenus);
		
		echo("</TABLE><BR>\n");

		accesDirect();
		
		piedDePage1();
		
	} elseif (($op == RP ) || ($op == EF)) {
	 
	 //RP : affichage de la liste des projets pour le système sélectionné
	 //EF : affichage de la liste des langues et éditions des cahiers de recette dispos pour 
	 //     le projet sélectionné
	
		$s = implode("", @file("$racineSygTemp$nameSygTemp/$repUtil/step1/systemes.syg"));
	 	$systemes = unserialize($s);
		
		$sr = implode("", @file("$racineSygTemp$nameSygTemp/$repUtil/step1/sysRetenus.syg"));
	 	$systemesRetenus = unserialize($sr);
	 	
	 	// Remarque : on accèdera au premier element du tableau des projets d'un systeme donné
	 	// via l'index -1 du tableau suite la désérialisation.Il en est de même pour les
	 	// cahiers de recette de chaque projet
		
		entetePage1(); 
		
		//creation du select dynamique pour les systemes retenus 	
		creerSelectSys($systemesRetenus);
		
		echo("<SCRIPT>document.firstPage.selection_sys.selectedIndex = ($selection_sys + 1);</SCRIPT>\n");
		
		//creation du select dynamique pour les projets
		$sys = $systemesRetenus[$selection_sys];
		creerSelectProjet($sys);
		
		if ($op == EF) {

		//EF : affichage de la liste des langues et éditions des cahiers de recette dispos pour le projet sélectionné
			if ($selection_p != -2) {
			
				echo("<SCRIPT>document.firstPage.selection_p.selectedIndex = ($selection_p + 2);</SCRIPT>\n");
				//creation du select pour les langues des CR disponibles pour ce projet
				$p = $sys->projets[(integer)$selection_p];
				creerSelectLgue($p);
				echo("</TABLE><BR><BR>\n");
				echo("<CENTER>\n");
				echo("<INPUT type=button name=button1 value=\" $string5 \" onClick=\"recupererCR_js()\">\n"); 
				echo("</CENTER>\n");
				
				accesDirect();

			} else {
				echo("</TABLE><BR>\n");
				
				accesDirect();
			}
			
		} else {
		//op = RP : affichage de la liste des projets pour le système sélectionné

			echo("</TABLE><BR>\n");

			accesDirect();
		}
			
		//pied de page
		piedDePage1();
		
	} elseif ($op == CR) {
	
	// génération de la requête GEDI pour la récupération du cahier de recette
	//            gediget reference langue edition
		
	 	//creation de l'entete
	
		echo("<HTML>\n");
		echo("<HEAD>\n");
		echo("<TITLE>$string8</TITLE>\n");	
		echo("</HEAD>\n");
		echo("<BODY BGCOLOR=\"#FFFFES\" ONLOAD=closeWaitForm()>\n");
		echo("<CENTER><FONT COLOR=\"#0000F\" SIZE=\"7\">\n");
		echo("<B>$string8</B>\n");
		echo("</FONT>\n");
		echo("</CENTER>\n");
		
		//construction du formulaire
		
		if ($pv != 1 )
			echo("<FORM method=\"post\" action=\"sygMain.php?op=stp2&rep=$repUtil\" name=\"affichQuery\">\n");
		else
			echo("<FORM method=\"post\" action=\"sygMain.php?op=newpv&rep=$repUtil\" name=\"affichQuery\">\n");
	 		
	 	//champ caché pour mémoriser le chemin du répertoire utilisateur
		echo("<INPUT TYPE=\"hidden\" name=\"rep\" value=\"".$repUtil."\">\n");
	 	
	 	//dès lors que l'utilisateur a rentré une valeur dans le champ ref_cr
	 	//on prend en compte cette référence
		
	 	if ($ref_cr == "") { 
	 		//désérialisation
			$s = implode("", @file("$racineSygTemp$nameSygTemp/$repUtil/step1/systemes.syg"));
		 	$systemes = unserialize($s);
			
			$sr = implode("", @file("$racineSygTemp$nameSygTemp/$repUtil/step1/sysRetenus.syg"));
		 	$systemesRetenus = unserialize($sr);
		 	
		 	// Remarque : on accèdera au premier element du tableau des projets d'un systeme donné
		 	// via l'index -1 du tableau suite la désérialisation. Il en est de même pour les
		 	// cahiers de recette de chaque projet
		 	
		 	$sys = $systemesRetenus[$selection_sys];
		 	$p = $sys->projets[(integer)$selection_p];
		 	
		 	tableauLguesDispo($p);
		 	
		 	$lgueChoisie = $lguesDispo[$selection_lgue];	 //$selection_lgue est un index
									//pour accéder à la véritable valeur
									//de la langue choisie	 								 	
		 	//on parcours les différentes FR du système et du projet choisi
		 	//on récupère le Cr dès lors qu'on a trouvé celui correspondant 
		 	//à la langue choisie par l'utilisateur
		 	foreach($p->cr as $ctg){
		 		if ($ctg->lgue == $lgueChoisie) {
		 			
		 			//on va vérifier qu'aucun autre fichier xml
		 			// n'est présent sinon on le supprime et on supprime
		 			//les fichier.syg du répertoire step2
		 			$fichiersStp1 = list_dir($racineSygTemp.$nameSygTemp."/".$repUtil."/step1");
					foreach ($fichiersStp1 as $fic) {
						if (ereg(".xml$",$fic)) {
							unlink($racineSygTemp.$nameSygTemp."/".$repUtil."/step1/".$fic);
						}
					} 			
		 			$fichiersStp2 = list_dir($racineSygTemp.$nameSygTemp."/".$repUtil."/step2");
					foreach ($fichiersStp2 as $fic) {
						if ((ereg(".syg$",$fic)) || (ereg(".xzip$",$fic))) {
							unlink($racineSygTemp.$nameSygTemp."/".$repUtil."/step2/".$fic);
						}
					}
		 			$chDirOk = chdir("$racineSygTemp$nameSygTemp/$repUtil/step1");
		 			if (!$chDirOk) {
		 				die("pb chdir script sygMainStep1!!");
		 			} else {
					//on récupère le résultat de la commande //
						$identificationCIl_null="";
						system("gediget ".$ctg->ref." ".$ctg->lgue." $identificationCIl_null > $racineSygTemp$nameSygTemp/$repUtil/resCdes", $resCde);

							/*NGJ 01/062007*
							$string1000 =  " sygMainStep1 ligne 858 : appel gediget code retour = ".$resCde;
							$string10001 =  " referenceC=".$ctg->ref.", identificationCIl=".$identificationCIl_null." \n";
							erori ($string1000,$string10001);
							*/


						if ($resCde != 0) {
							if ($langue_browser == "FR"){
								if ($resCde == 1){
									$string10 =  " Problème d'accès à GEDI, référence = $ctg->ref inexistante <br>";
								}elseif ($resCde == 2){
									$string10 = " Problème d'accès à GEDI, référence = $ctg->ref est en undo modify <br>";
								}elseif ($resCde == 3){
									$string10 = " GEDI le problème ne peut pas connecter au LDAP, mot de passe incorrect <br>";
								}elseif ($resCde == 6){
									$string10 =  " Problème d'accès à GEDI, référence = $ctg->ref longueur incorrecte <br>";
								}
							}else{
								if ($resCde == 1){
									$string10 =  " Access GEDI problem having the reference = $ctg->ref not existent <br>";
								}elseif ($resCde == 2){
									$string10 = " Access GEDI problem having the reference = $ctg->ref is undo modify <br>";
								}elseif ($resCde == 3){
									$string10 = " GEDI problem cannot connect to the LDAP, Wrong password entered <br>";
								}elseif ($resCde == 6){						
									$string10 =  " Access GEDI problem having the reference = $ctg->ref incorrect  length <br>";
								}
							}
							//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
							//$identificationCIl = "-cn $login_user -pass $motdepasse";
							//$motdepasse = relance_gediget($passwd);
							$motdepasse = "\"$passwd\"";
							$identificationCIl = "-cn $login_user -pass $motdepasse";
							system("gediget ".$ctg->ref." ".$ctg->lgue." $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/resCdes", $resCde);
								

						}else{
							$string10="";
						  // Vérifier si le fichier est du format xzip
							$files_root = list_dir("$racineSygTemp$nameSygTemp/$repUtil/step1/");
							foreach($files_root as $file) {
								if (ereg(".doc$",$file)) {	
									$resCde=10;
									if ($langue_browser == "FR"){								
										$string10 = "!!! La référence à modifier : $ctg->ref est de type format WORD !!!";
									}else{
										$string10 = "!!! The reference to modify: $ctg->ref is of type format WORD !!!";
									}
									system("rm -fR $racineSygTemp$nameSygTemp/$repUtil/step1/$file");
								}
								/**/
								//$string1000 =  " sygMainStep1 : ligne 950 : lire fichier : $racineSygTemp$nameSygTemp/$repUtil/$file<br>";
								//$string1001 =  " traitement_mode=$traitement_mode , op=$op , code retour = $resCde <br>";
								//erori ($string1000,$string1001);
								/**/
							}// fin du foreach
						}
					//fin trace	
		 			}
		 		}
		 	}
	 	} else {	 	
		    
					//NGJ 17_04_2007
	// cas la référence est en modification
			$ref_cr = strtoupper($ref_cr);
	/**/
	//$string1000 =  " sygMainStep1 : ligne 963 : traitement_mode=$traitement_mode , op=$op <br>";
	//$string1001 =  " Transforme la reférence en majuscule ref_cr = $ref_cr <br>";
	//erori ($string1000,$string1001);
	/**/
	//fin trace
	 		//la lettre désignant la langue se trouve être le 17ème caractère
	 		$lastLetter = $ref_cr[16]; //premier élément à l'indice 0 !!
	 		
	 		if (strcmp($lastLetter,"A") == 0) {
				$lgue = "EN";
			} elseif (strcmp($lastLetter,"B") == 0) {
				$lgue = "FR";
			} elseif (strcmp($lastLetter,"D") == 0) {
				$lgue = "SP";
			}
			
			$resCde = -1; //code retour du gediget
		
		 	$chDirOk = chdir("$racineSygTemp$nameSygTemp/$repUtil/step1");
		 	if (!$chDirOk) {
		 		die("pb chdir script sygMainStep1!!");
		 	} else {
				$identificationCIl_null="";
				system("gediget ".$ref_cr." ".$lgue." $identificationCIl_null > $racineSygTemp$nameSygTemp/$repUtil/resCdes", $resCde);
				
							/*NGJ 01/062007*/
				//$string1000 =  " sygMainStep1 ligne 994 : appel gediget code retour = $resCde<br>";
				//$string1001=  " referenceC=$ref_cr, langue = $lgue, identificationCIl=$identificationCIl , dir = $racineSygTemp$nameSygTemp/$repUtil<br>";
				//erori ($string1000,$string1001);
							/**/
				if ($resCde != 0) {
				
				// formatage du message d'erreur selon le code de retour du gediget
				$file_resCdes = "$racineSygTemp$nameSygTemp/$repUtil/resCdes";
				$string10 = edit_mess_gedi ($file_resCdes,$ref_cr,$identificationCIl_null,$lgue);
				/*
						if ($langue_browser == "FR"){
							if ($resCde == 1){
								$string10 =  " Problème d'accès à GEDI, référence = $ref_cr inexistante <br>";
							}elseif ($resCde == 2){
								$string10 = " Problème d'accès à GEDI, référence = $ref_cr est en undo modify <br>";
							}elseif ($resCde == 3){
								$string10 = " GEDI le problème ne peut pas connecter au LDAP, mot de passe incorrect <br>";
							}elseif ($resCde == 6){
								$string10 =  " Problème d'accès à GEDI, référence = $ref_cr longueur incorrecte <br>";
							}
						}else{
							if ($resCde == 1){
								$string10 =  " Access GEDI problem having the reference = $ref_cr not existent <br>";
							}elseif ($resCde == 2){
								$string10 = " Access GEDI problem having the reference = $ref_cr is undo modify <br>";
							}elseif ($resCde == 3){
								$string10 = " GEDI problem cannot connect to the LDAP, Wrong password entered <br>";
							}elseif ($resCde == 6){
								$string10 =  " Access GEDI problem having the reference = $ref_cr incorrect length <br>";
							}
						}
					//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
					//$motdepasse = relance_gediget($passwd);
					$motdepasse = "\"$passwd\"";
					$identificationCIl = "-cn $login_user -pass $motdepasse";
					system("gediget ".$ref_cr." ".$lgue." $identificationCIl > $racineSygTemp$nameSygTemp/$repUtil/resCdes", $resCde);
				*/
					/*
					//*NGJ 01/062007
					$string1000 =  " sygMainStep1 ligne 1026 : appel gediget code retour = $resCde<br>";
					$string10001 =  " referenceC=$ref_cr, langue = $lgue, identificationCIl=$identificationCIl <br>";
					erori ($string1000,$string10001);
					*/
				}else{
				//cas $resCde = 0
					$string10="";
					$resCde=10;  // 
						  // Vérifier si le fichier est du format xzip
					$files_root = list_dir("$racineSygTemp$nameSygTemp/$repUtil/step1/");
					foreach($files_root as $file) {
						if (ereg(".xzip$",$file)) {
							$tab_car_spe = explode( "_",$file);
							list($ref_CR_get,$edit_CR ,$langue_CR) = explode ("_", $file);
							if (strcmp($ref_CR_get,$ref_cr) == 0){// return 1; //true
								$resCde=0;						
							}
						}
					}
						
					if ($resCde != 0) {		
          	
						if ($langue_browser == "FR"){								
							$string10 = "!!!  La référence à modifier : $ref_cr n'est pas en format xzip  !!! ";
						}else{
							$string10 = "!!!  The reference to modify: $ref_cr is not in format xzip  !!! ";
						}
						//system("rm -fR $racineSygTemp$nameSygTemp/$repUtil/step1/$file");
						
					}
						
						//<<<<<<<<<<<<<<<<<<<<<<<<<<
						/*
						$string1000 =  " sygMainStep1 : ligne 1033 : lire fichier : $racineSygTemp$nameSygTemp/$repUtil/step1/$file<br>";
						$string1001 =  " traitement_mode=$traitement_mode , op=$op , code retour = $resCde <br>";
						erori ($string1000,$string1001);
						*/
				}
				
		 	}  // fin du chdir
		}  // fin  traitement de mode MODIF
		 		 
		 //on analyse le résultat de la commande afin de savoir si
		 //c'est un pb se serveur FTP Gedi ou que le document n'est pas dans
		 //la base
		 
		 //on doit igonorer /sygTemp de $repUtil pour ne garder que le nom d'utilisateur ou identifiant saisi
		 $pos = strpos($repUtil,"/") + 1; //$repUtil est de la forme sygTemp/identifiant
		 $identifiant = substr($repUtil,$pos);
		if ($resCde == 0){
		 	 //on récupère le nom du fichier xzip (un seul) pour le dézipper
			 $filesStep1 = list_dir("$racineSygTemp$nameSygTemp/$repUtil/step1/");
			 foreach ($filesStep1 as $file) {
			 	if (ereg(".xzip$",$file)) {
			 		$nomFileXzip = $file;
			 	}
			 }
			 
			 //unzip du fichier
			 //ATTENTION ! on utilisera provisoirement le unzip se trouvant sous
			 // /home/morin38/ mais il devra être installé correctement dans 
			 //l'environnment standard or pb avec MANPATH actuellement ??
			 system("unzip $racineSygTemp$nameSygTemp/$repUtil/step1/$nomFileXzip >> $racineSygTemp$nameSygTemp/$repUtil/resCdes");
			 system("rm -f $racineSygTemp$nameSygTemp/$repUtil/step1/$nomFileXzip");
						//on récupère le nom du fichier xml après dézippage
						$filesStep1 = list_dir("$racineSygTemp$nameSygTemp/$repUtil/step1/");
						foreach ($filesStep1 as $file) {
							if (ereg(".xml$",$file)) {
								$nomFileCr = $file;
							}
						}
						//le fait de faire un ob_start()  mais pas de ob_end_flush() fait
						//que aucune entete n'est envoyée auparavant et le header suivant
						//permettant l'affichage du CR est réalisé
						if($pv != 1)
							header("Location: ".$urlSygafe."sygMain.php?op=stp2&rep=$repUtil&nameCR=$nomFileCr&ref_cr=$ref_cr");
						else
							header("Location: ".$urlSygafe."sygMain.php?op=newpv&rep=$repUtil&nameCR=$nomFileCr&ref_cr=$ref_cr");
					//}
		}
	 	if ($resCde != 0) {
		 	echo("<BR><BR>\n");
			echo("<CENTER><FONT COLOR=\"#00000\" SIZE=\"3\">\n");
		 	echo("<FONT COLOR=\"#FF0000\" SIZE=\"4\">\n");
			echo("<B>$string10</B><BR><BR>\n"); // message de faute
			echo("</FONT>\n"); 	
			echo("</CENTER>\n"); 	
		 	echo("<CENTER><FONT COLOR=\"#00000\" SIZE=\"3\">\n");
		 	echo("<BR><CENTER><INPUT type=button name=home  value=\" $string9 \" onClick=\"home_js('affichQuery')\">\n");	
		}
	 	piedDePage11("affichQuery");
	}  // fin de l'op=CR
} 
	
?>

