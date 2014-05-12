<?php

/****************************************************************************************/
/****************************** sygReadFileP.php ****************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: lecture du fichier en gestion sous GEDI qui liste les projets et leurs 	*/
/* cahiers de recette.								 	*/
/*											*/
/* fonctions:										*/
/*  											*/
/****************************************************************************************/



	$csl=$HTTP_COOKIE_VARS[Csl];
	$repUtil = $csl;
	$passwd = $HTTP_COOKIE_VARS[Cip];
	$cil = $HTTP_COOKIE_VARS[Login];
	$login_user = "\"$cil\"";
	
	//echo " sygReadFileP ligne 22 : csl=$HTTP_COOKIE_VARS[Csl];passwd = $HTTP_COOKIE_VARS[Cip];cil = $HTTP_COOKIE_VARS[Login];<br>";

$previousTag = "";
$currentTag = "";
$nameSystemValue = "";		//nom du système
$projectsValue = array();	//projets pour un système
$nameProjectValue = "";		//nom d'un projet
$crValue = array();		//cahiers de recettes pour un projet
$descValue = "";		//descriptif du cahier de recette
$refValue = "";			//référence du cahier de recette
$systemCount = 0;
$projectCount = 0;
$crCount = 0;


//cette fonction permet de créer la liste des references FR du catalogue général dans un fichier listerefctg
//et qu'il se trouve dans le répetoire sygtemp
function create_liste_ref_ctg($refValue)
{
	global $racineSyg,$racineSygTemp, $nameSygTemp;
	global $passwd,$login_user,$repUtil;

	$listerefctg = $racineSygTemp.$nameSygTemp."/".$repUtil."/listerefctg";
	
	$files_root = list_dir($racineSygTemp.$nameSygTemp);
	$cnts = 0;
	foreach($files_root as $file)
	{
		if (ereg("listerefctg", $file)) 
		{
			$cnts = 1;
		}
	}
	if ($cnts == 0)
	{
		$fic = fopen($listerefctg, "a");
		fwrite ($fic, $refValue."\n");	
		fclose ($fic);
	}else{
		$ref_rech=0;
		$lgrefValue = strlen($refValue);
		$fic = fopen($listerefctg, "a+");
		while (!feof ($fic)) 
		{
			$buffer = fgets($fic, 4096);
			if (strncmp($buffer, $refValue,$lgrefValue) == 0) 
				$ref_rech=1;
		}
		if ($ref_rech == 0) {
			fwrite ($fic, $refValue."\n");
		}
		fclose ($fic);
	}
}

function startElement($parser, $name, $attr)
{
	global $previousTag, $currentTag;
	
	$previousTag = $currentTag;
	$currentTag = $name;
}

function endElement($parser, $name)
{
	global $systemes, $nameSystemValue, $projectsValue, $nameProjectValue, $crValue, $systemCount,
		$projectCount, $crCount, $refValue, $descValue;
		
	if (strcasecmp($name, "row") == 0) {
		$crTemp = new CR($descValue, $refValue);
		$crValue[$crCount] = $crTemp; 
		$crCount++;
		//on reinitialise les variables
		$descValue = "";
		$refValue = "";
	} elseif (strcasecmp($name, "h2") == 0) {
		$pTemp = new Projet($nameProjectValue);
		//on associe au projet les Cr qui lui sont associés
		foreach($crValue as $cahier) {
			$pTemp->ajouterCR($cahier);
		}
		$projectsValue[$projectCount] = $pTemp;
		$projectCount++;
		//on réinitialise les variables
		$nameProjectValue = "";
		$crCount = 0;
		$crValue = array();
	} elseif (strcasecmp($name, "h1") == 0) {
		$sysTemp = new Systeme($nameSystemValue);
		//on associe au système les projets qui lui sont associés
		foreach($projectsValue as $unProjet) {
			$sysTemp->ajouterProjet($unProjet);
		}
		$systemes[$systemCount] = $sysTemp;
		$systemCount++;
		//on réinitialise les variables
		$nameSystemValue = "";
		$projectCount = 0;
		$projectsValue = array();
	}
}


function characterData($parser, $data)
{
	global $previousTag, $currentTag, $descValue, $refValue, $nameSystemValue, $nameProjectValue;
	
	if (strcasecmp($currentTag, "mnemoref") == 0) {
		$descValue .= trim($data); //on supprime les espaces a gauche et a droite
	} elseif (strcasecmp($currentTag, "ref-text") == 0) {
		$refValue .= trim($data);
		create_liste_ref_ctg($refValue);
	} elseif (strcasecmp($currentTag, "ht") == 0) {
		if (strcasecmp($previousTag, "h1") == 0) {
			$nameSystemValue .= trim($data);
		} elseif (strcasecmp($previousTag, "h2") == 0) {
			$nameProjectValue .= trim($data);
		}
	}

}

function externalEntityHandler($parser, $entityName, $base, $systemId, $publicId)
{
	
	//??
} 

function lireFichierP()
{

	global $file_orig, $racineSyg,$racineSygTemp, $nameSygTemp;
	global $langue_browser;
	
	if ($langue_browser == "FR")
		{
			$string1="Impossible d'ouvrir le fichier ".$path." en lecture ou problème d'accès à GEDI";
			$string2="Erreur XML à la ligne ";
		}
	else
		{
			$string1="Can not read ".$path." file or GEDI access problem";
			$string2="XML error on line ";
		}
	$path = $racineSygTemp.$nameSygTemp."/".$file_orig.".xml";
		
	ficpres($file_orig.".xml");

	$xml_parser = xml_parser_create();
	xml_set_element_handler($xml_parser, "startElement", "endElement");
	xml_set_character_data_handler($xml_parser, "characterData");
	xml_set_external_entity_ref_handler($xml_parser, "externalEntityHandler");
	
	if (!($fp = fopen($path, "r"))) {
		die($string1);
	
	}
	
	while (($data = fread($fp, 4096))) {
		if (!xml_parse($xml_parser, $data, feof($fp))) {
			die(sprintf("$string2%d colonne %d", xml_get_current_line_number($xml_parser), xml_get_current_column_number($xml_parser)));
		}
	}
	
	xml_parser_free($xml_parser);
	fclose($fp);

}

?>
