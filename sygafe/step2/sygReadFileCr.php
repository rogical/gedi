<?php

/****************************************************************************************/
/****************************** sygReadFileCr.php ****************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: lecture du fichier en gestion sous GEDI repr�sentant le cahier de  	*/
/* recette. Ce fichier liste l'ensemble des fiches de recette d'un projet structur�es	*/
/* en chapitres et sous-chapitres							*/
/*											*/
/* fonctions:										*/
/*  Lecture fichier du correspondance 3DR-FR											*/
/****************************************************************************************/


//variables pour la lecture du cahier de recette au format xml
 
//identifiants du cahier de recette r�cup�r�
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

$val = ""; 			//contenu d'une balise : caract�res + accents
$previousTag = "";
$currentTag = "";
$colnameValue = "";		//valeur pour l'attribut colname "col1","colspec0","colspec2"
$nameChapterValue = "";		//nom du chapitre
$nameSubChapterValue = "";	//nom du sous-chapitre
$subChaptersValue = array();	//sous-chapitres
$frValue = array();		//fiches de recette
$mnemonicValue = "";		//mn�monique d'une fiche de recette
$desValue = "";			//d�signation de la fiche de recette
$refValue = "";			//r�f�rence de la fiche de recette
$edValue = "";			//�dition de la fiche de recette
$chapterCount = 0;
$subChapterCount = 0;
$frCount = 0;

function startElement($parser, $name, $attr)
{
	global $previousTag, $currentTag, $colnameValue;
	
	$previousTag = $currentTag;
	$currentTag = $name;
	
	//echo($currentTag."     <br>");

	if (strcasecmp($name, "entry") == 0) {
		$colnameValue = "";
		$colnameValue = $attr["COLNAME"];
	}
	
}

function endElement($parser, $name)
{
	global $currentTag, $previousTag, $nameChapterValue, $nameSubChapterValue, $systemCount, $chapterCount;
	global $subChapterCount, $frCount, $mnemonicValue, $refValue, $desValue, $edValue, $colnameValue;
	global $frValue, $subChaptersValue, $crRecupere;
	global $prefixCtg, $numberCtg, $variantCtg, $versionCtg, $doctypeCtg, $zzCtg, $lgueCtg;
	global $titleCtg, $mnemonicCtg, $editionCtg, $description ;
	global $val;

	
	if (strcasecmp($name, "docstatus") == 0) {
		//cr�ation d'une instance de document; Affectation de l'objet � $crRecupere
		$crRecupere = new Document($prefixCtg, $numberCtg, $variantCtg, $versionCtg,
					 $doctypeCtg, $zzCtg, $lgueCtg, $titleCtg, $mnemonicCtg, $editionCtg, $description);
		
	} elseif (strcasecmp($name, "mnemoref") == 0) {
		//on modifie la valeur de currentTag sinon la d�signation de la fiche de recette
		//est concat�n�e au mn�monique d'o� aucune valeur de d�signation!! 
		$currentTag = "PARA";
		$previousTag = "ENTRY";
	} elseif (strcasecmp($name, "row") == 0) {
		//on peut proc�der � l'instanciation de la FR
		//les donn�es obligatoires doivent �tre renseign�es sinon il s'agit de l'entr�e No document
		if (strcasecmp($refValue, "") != 0) {
			$frTemp = new FR($mnemonicValue, $desValue, $refValue, $edValue, $edValue);
			$frValue[$frCount] = $frTemp;
			$frCount++;
		}
		
		//on reinitialise les variables
		$mnemonicValue = "";
		$desValue = "";
		$refValue = "";
		$edValue = "";
	} elseif (strcasecmp($name, "h2") == 0) {
		//on peut proc�der � l'instanciation du sous-chapitre
		$numChapter = strval($chapterCount + 1);
		$strSubChapter = strval($subChapterCount + 1);
		$numSubChapter = $numChapter.".".$strSubChapter; 
		$scTemp = new SousChapitre($numSubChapter, $nameSubChapterValue);
		//on associe au sous-chapitre les fr qui lui sont associ�es
		foreach($frValue as $fiche) {
			$scTemp->ajouterFr($fiche);
		}
		$subChaptersValue[$subChapterCount] = $scTemp;
		$subChapterCount++;
		//on r�initialise les variables
		$nameSubChapterValue = "";
		$frCount = 0;
		$frValue = array();
	} elseif (strcasecmp($name, "h1") == 0) {
		//on peut proc�der � l'instanciation du Chapitre
		$numChapter = strval($chapterCount + 1);
		$chapTemp = new Chapitre($numChapter, $nameChapterValue);
		//on associe au chapitre ses sous-chapitres
		foreach($subChaptersValue as $unChapitre) {
			$chapTemp->ajouterSousChap($unChapitre);
		}
		//on associe les fiches de recette s'il n'a pas de sous-chapitres
		foreach($frValue as $fiche) {
			$chapTemp->ajouterFr($fiche);
		}
		
		$crRecupere->ajouterChapitre($chapTemp);
		$chapterCount++;
			
		//on r�initialise les variables
		$frCount = 0;
		$frValue = array();
		$nameChapterValue = "";
		$subChapterCount = 0;
		$subChaptersValue = array();
	}
}  // fin du function endElement


function characterData($parser, $data)
{
	global $previousTag, $currentTag, $colnameValue, $mnemonicValue, $desValue, $refValue, $edValue, $nameChapterValue, $nameSubChapterValue;
	global $prefixCtg, $numberCtg, $variantCtg, $versionCtg, $doctypeCtg, $zzCtg, $lgueCtg;
	global $titleCtg, $mnemonicCtg, $editionCtg;
	global $val, $description;

	$val .= $data;
	$valSansEspace = trim($val);
	
	if (strcasecmp($currentTag, "para") == 0) {
		if (strcasecmp($colnameValue, "col1") == 0) {
			$desValue .= $val;
		
		} elseif (strcasecmp($colnameValue, "COLSPEC2") == 0) {
			$edValue .= trim($val);
		}
	} elseif (strcasecmp($currentTag, "mnemoref") == 0) {
		$mnemonicValue .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "ref-text") == 0) {
		$refValue .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "ht") == 0) {
		if (strcasecmp($previousTag, "h1") == 0) {
			$nameChapterValue .= $val;
		} elseif (strcasecmp($previousTag, "h2") == 0) {
			$nameSubChapterValue .= $val;
		}
	} elseif (strcasecmp($currentTag, "prefix") == 0) {
		$prefixCtg .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "number") == 0) {
		$numberCtg .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "variant") == 0) {
		$variantCtg .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "version") == 0) {
		$versionCtg .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "documenttype") == 0) {
		$doctypeCtg .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "zz") == 0) {
		$zzCtg .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "language") == 0) {
		$lgueCtg .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "title1") == 0) {
		$titleCtg .= $val;
	} elseif (strcasecmp($currentTag, "title2") == 0) {
		$titleCtg .= " ".$val;
	} elseif (strcasecmp($currentTag, "title3") == 0) {
		$titleCtg .= " ".$val;
	} elseif (strcasecmp($currentTag, "mnemonicdoc") == 0) {
		$mnemonicCtg .= $valSansEspace;
	} elseif ((strcasecmp($currentTag, "ednum") == 0) && (strcasecmp($previousTag, "mnemonicdoc") == 0)) {
		$editionCtg .= $valSansEspace;
	} elseif (strcasecmp($currentTag, "descriptitem") == 0) {
		$description .= " ".$val;
	}
	//fin d'une balise
	$val = "";
}

function externalEntityHandler($parser, $entityName, $base, $systemId, $publicId)
{
	
	//??
} 

//
function defaultHandler($parser, $data) {
	global $val;
		
	if ($data == "&eacute;") {
		$val = $val."�";
	} elseif ($data == "&egrave;") {
		$val = $val."�";
	} elseif ($data == "&agrave;") {
		$val = $val."�";
	} elseif ($data == "&amp;") {
		$val = $val."&";
	} elseif ($data == "&ecirc;") {
		$val = $val."�";
	} elseif ($data == "&ocirc;") {
		$val = $val."�";
	} elseif ($data == "&icirc;") {
		$val = $val."�";
	} elseif ($data == "&ucirc;") {
		$val = $val."�";
	} elseif ($data == "&acirc;") {
		$val = $val."�";
	} elseif ($data == "&sect;") {
		$val = $val."�";
	} elseif ($data == "&lt;") {
		$val = $val."<";
	} elseif ($data == "&ccedil;") {
		$val = $val."�";
	} elseif ($data == "&ugrave;") {
		$val = $val."�";
	}
}
 
 
function lireFichierP()
{
	global $file;
	global $langue_browser;
		//  NGJ 25_06_07
		//	$string1000 =  " sygreadFileCr.lireFichierP ligne 241  <br>";
		//	$string1001 =  " lecture du fichier = $file <br>";
		//	erori ($string1000,$string1001);
		//
	if ($langue_browser == "FR")
		{
			$string1="Impossible d'ouvrir $file en lecture";
			$string2="Erreur xml a la ligne";
		}
	else
		{
			$string1="Can not read file $file ";
			$string2="XML error on line ";
		}	
		//Cr�ation d'un analyseur XML
	$xml_parser = xml_parser_create();
	//Affecte les gestionnaires de d�but et de fin de balise XML
	xml_set_element_handler($xml_parser, "startElement", "endElement");
	//Affecte les gestionnaires de texte litt�ral
	xml_set_character_data_handler($xml_parser, "characterData");

	//Affecte le gestionnaire XML par d�faut
	xml_set_default_handler($xml_parser, "defaultHandler");

	//Configure le gestionnaire XML de r�f�rences externes
	xml_set_external_entity_ref_handler($xml_parser, "externalEntityHandler");

	if (!($fp = fopen($file, "r"))) {
		die("$string1");
	}
	
	while (($data = fread($fp, 4096))) {
	// Commencer l'analyse d'un fichier XML
	
		if (!xml_parse($xml_parser, $data, feof($fp))) {
			die(sprintf("$string2 %d colonne %d", xml_get_current_line_number($xml_parser), 
			xml_get_current_column_number($xml_parser)));
		}
		
	}
	//D�truit un analyseur XML
	xml_parser_free($xml_parser);
	fclose($fp);

}
//***************************************************************************************


$previousTag1 = "";
$currentTag1 = "";
$domaind = "";
$referenced = "";
$named = "";
$referenceFRd = "";
$comp3DR = array();
$compCount = 0;
$colnameValue1 = "";
$ppp = 0 ;
$cc = 1;
 
function startElementd ($parser, $name, $attr)
{
	global $previousTag1, $currentTag1;
	global $colnameValue1;	
	$previousTag1 = $currentTag1;
	$currentTag1 = $name;
	if (strcasecmp($name, "entry") == 0) {
		$colnameValue1 = "";
		$colnameValue1 = $attr["COLNAME"];
	}	
}

function endElementd ($parser, $name)
{
	global $domaind, $referenced, $named, $referenceFRd;
	global $compCount, $comp3DRtmp, $comp3DR;
	global $colnameValue1;
	global $previousTag1, $currentTag1;
	global $ppp, $cc;
	

	if (strcasecmp($name, "row") == 0) 
	    {
		$comp3DRtmp = new TroisDR ($domaind, $referenced, $named, $referenceFRd);
		$comp3DR[$compCount] = $comp3DRtmp;
		$compCount++; 
		$domaind = "";
		$referenced = "";
		$named = "";
		$referenceFRd = "";
		$ppp++;
		$cc = 1;
		} 
		elseif  (strcasecmp($name, "docstatus") == 0)
		{
			$caiet = new ColectionTDR ($valoare);
			foreach ($comp3DR as $fdr)
			{
				$caiet ->ajouterFr($fdr); 
				$comp3DR = array();
			}
		}

		
}

function characterDatad ($parser, $data)
{
	global $domaind, $referenced, $named, $referenceFRd, $currentTag1, $previousTag1;
	global $colnameValue1, $compCount;
	global $ppp, $cc, $valoare;


if (strcasecmp ($currentTag1, "para") == 0 )
			{
			if (strcasecmp ($colnameValue1, "col1") == 0 ) 
				{		
					$domaind .= $data;
					
				}
			elseif (strcasecmp($colnameValue1, "col2") == 0 ) 
				{
					$referenced .= trim ($data);
				}
			elseif (strcasecmp($colnameValue1, "col3") == 0 )
				{
					$named .= $data;
				}
			elseif (strcasecmp($colnameValue1, "col4") == 0 ) 
				{
					$referenceFRd	.= trim($data);
				}
			}
/*		if (strcasecmp ($currentTag1, "para") == 0 )
			{
			if (strcasecmp ($colnameValue1, "col1") == 0 and ( $cc == 1)) 
				{		
				
						$comp3DR[$ppp]->domaind	= $data;
						$cc=0;
				}
			elseif (strcasecmp($colnameValue1, "col2") == 0 and ( $cc == 0)) 
				{
						$cc=1;
						$comp3DR[$ppp]->referenced	= $data;

					
				}
			elseif (strcasecmp($colnameValue1, "col3") == 0 and ( $cc == 1))
				{
						$cc=0;
						$comp3DR[$ppp]->named	= $data;
				
					
				}
			elseif (strcasecmp($colnameValue1, "col4") == 0 and ( $cc == 0)) 
				{
						$cc=1;
						$comp3DR[$ppp]->referenceFRd	= $data;

						
				}
			}*/

}
 
 
function externalEntityHandlerd ($parser, $entityName, $base, $systemId, $publicId)
{
	
	//??
} 

 
function lireFichierD()
{

	global $file_3DR, $racineSyg, $racineSygTemp,$nameSygTemp;
	
	$path = $racineSygTemp.$nameSygTemp."/".$file_3DR.".xml";
	
	ficpres($file_3DR.".xml");
	//Cr�ation d'un analyseur XML
	$xml_parser1 = xml_parser_create();
	//Affecte les gestionnaires de d�but et de fin de balise XML
	xml_set_element_handler($xml_parser1, "startElementd", "endElementd");
	//Affecte les gestionnaires de texte litt�ral
	xml_set_character_data_handler($xml_parser1, "characterDatad");
	//Configure le gestionnaire XML de r�f�rences externes
	xml_set_external_entity_ref_handler($xml_parser1, "externalEntityHandlerd");
	
	if (!($fp = fopen($path, "r"))) {
		die("Impossible d'ouvrir ".$path." en lecture - pb gediget : document absent??");
	}
	
	while (($data = fread($fp, 4096))) {
		// Commencer l'analyse d'un fichier XML
		if (!xml_parse($xml_parser1, $data, feof($fp))) {
			die(sprintf("Xml error on line %d colonne %d", xml_get_current_line_number($xml_parser1), xml_get_current_column_number($xml_parser1)));
		}
	}
	//D�truit un analyseur XML
	xml_parser_free($xml_parser1);
	fclose($fp);
	
}

function lireFiche()
{
	global $langue_browser;
	if ($langue_browser == "FR")
		{
			$string1="Ins�rer une nouvelle fiche";
			$string2="R�f�rence mauvaise, ce n'est pas un fichier XML";
			$string3="Impossible d'ouvrir ".$fic." en lecture or probl�me d'acc�s � GEDI";
			$string4="Erreur xml a la ligne";
		}
	else
		{
			$string1="Insert a new acceptance sheet ";
			$string2="Bad reference, this not an XML file";
			$string3="Can not read ".$fic." file  or GEDI access problem";
			$string4="XML error on line ";
		}
	global $fic, $ok;
	
	$xml_parser2 = xml_parser_create();
	xml_set_element_handler($xml_parser2, "startElementf", "endElementf");
	xml_set_character_data_handler($xml_parser2, "characterDataf");
//	xml_set_external_entity_ref_handler($xml_parser2, "externalEntityHandlerd");
	

	$cnt= strlen ($fic);

			//JNG trace	
				$string1000 =  " sygReadFileCr : debut lireFiche ligne 476, lecture fichier fic  = $fic<br>";
				$string1001 =  " xml_parser2=$xml_parser2 <br>";
				erori ($string1000,$string1001);
			//fin trace	
	if($fic[$cnt-1] == "y")
			{
				echo("<BODY BGCOLOR=\"#FFFFES\">\n");
				echo("<B><CENTER>\n");
				echo("<FONT COLOR=\"#FF0000\" SIZE=\"5\">\n");
				echo("<INPUT type=button name=nfr value=\"$string1\" onClick=nfr_js()><BR><BR>\n");
				die(" $string2");
				echo("<B></CENTER>\n");
			}
	
	else
		if (!($fp = fopen($fic, "r"))) {
		echo("<BODY BGCOLOR=\"#FFFFES\">\n");
		echo("<B><CENTER>\n");
		echo("<INPUT type=button name=nfr value=\"$string1\" onClick=nfr_js()><BR>\n");
		echo("<B></CENTER>\n");
		die("$string3");
		
	}

	while (($data = fread($fp, 4096))) {
		if (!xml_parse($xml_parser2, $data, feof($fp))) {
			die(sprintf("$string4 %d colonne %d", xml_get_current_line_number($xml_parser2), xml_get_current_column_number($xml_parser2)));
		}
				//JNG trace	
				$string1000 =  " sygReadFileCr  ligne 505 : while (($data = fread($fp, 4096)))<br>";
				$string1001 =  " xml_parse  = $xml_parser2 <br>";
				erori ($string1000,$string1001);
			//fin trace	
		
	}
	
	xml_parser_free($xml_parser2);
	fclose($fp);
	
}


function startElementf ($parser, $name, $attr)
{
	global  $currentTag2, $FR, $previousTag2;
	$previousTag2 = $currentTag2;
	$currentTag2 = $name;

}

function endElementf ($parser, $name)
{

global $refValue, $mnemonicValue, $desValue, $edValue, $edValue, $FR, $edValue1 ; 
		if (strcasecmp($name, "docstatus") == 0) 
		{
		$FR = new FR ($mnemonicValue, $desValue, $refValue, $edValue1, $edValue1);
		}
		
}

function characterDataf ($parser, $data)
{
global $desValue, $edValue, $currentTag2, $FR, $edValue1, $previousTag2, $ok;
$val .= $data;
$valSansEspace = trim($val);
		
	if (strcasecmp($currentTag2, "title1") == 0) {
		$desValue .= $val;
	} elseif (strcasecmp($currentTag2, "title2") == 0) {
		$desValue .= " ".$val;
	} elseif (strcasecmp($currentTag2, "title3") == 0) {
		$desValue .= " ".$val;
	} elseif ((strcasecmp($currentTag2, "ednum") == 0) && ($ok != 1) ) {
		$edValue1 = $valSansEspace;
		$ok = 1;

	}
	 


}


 
//*****************************************************************************************	
 
?>
