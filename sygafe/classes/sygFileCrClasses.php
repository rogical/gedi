<?php

/****************************************************************************************/
/***************************** sygFileCrClasses.php *************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: ce fichier d�finit l'ensemble des classes con�ues pour structurer et	*/
/* d�finir les comportements des objets �labor�s � partir des donn�es d'un cahier	*/
/* de recette	 									*/
/*  											*/
/* classes:										*/
/*  											*/
/****************************************************************************************/
/*  V2.05  *  Jacob Nguyen  *  Enlever la s�lection des fiches par d�faut                                          */
/****************************************************************************************/
/*                                                                                                                                                      */
/*                                                                                                                                                      */
/****************************************************************************************/

//la classe Document d�finit les propri�t�s du cahier de recette r�cup�r�
class Document
{
	//attributs
	var $prefixDoc = "";
	var $numberDoc = "";
	var $variantDoc = "";
	var $versionDoc = "";
	var $typeDoc = "";
	var $zzDoc = "";
	var $lgueDoc = ""; //A pour anglais B pour fran�ais et D pour espagnol
	var $titleDoc = "";
	var $mnemonicDoc = "";
	var $editionDoc = "";
	var $editionDoctmp = "";
	var $chapitres = array(); //pilotes
	var $description = ""; 
	//nGJ 08_01_2007
	var $traitement_mode = "";
	//fin
	
	//constructeur
	function Document($prefix = "", $number = "", $variant = "", $version = "", $type = "", $zz = "", $langue = "", $title = "", $mnemonic = "", $edition = "", $description ="", $traitement_mode = "")
	{
		$this->prefixDoc = $prefix;
		$this->numberDoc = $number;
		$this->variantDoc = $variant;
		$this->versionDoc = $version;
		$this->typeDoc = $type;
		$this->zzDoc = $zz;
		$this->lgueDoc = $langue;
		$this->titleDoc = $title;
		$this->mnemonicDoc = $mnemonic;
		$this->editionDoc = $edition;
		$this->editionDoctmp = $edition;
		$this->description = $description;
		//NGJ 08_01_2007
		$this->traitement_mode = $traitement_mode ;
		//fin
	}
	
	//m�thodes
	
	function ajouterChapitre($unChapitre)
	{
		//$unChapitre->parent = $this;
		$this->chapitres[$this->nbChapitres() - 1] = $unChapitre;
	}
	
	function nbChapitres()
	{
		return count($this->chapitres);
	}
	
	function affiche()
	{
	
		echo("Cahier de recette<br>\n");
		echo("pr�fixe : ".$this->prefixDoc."<br>\n");
		echo("nombre : ".$this->numberDoc."<br>\n");
		echo("variant : ".$this->variantDoc."<br>\n");
		echo("version : ".$this->versionDoc."<br>\n");
		echo("type : ".$this->typeDoc."<br>\n");
		echo("zz : ".$this->zzDoc."<br>\n");
		echo("langue : ".$this->lgueDoc."<br>\n");
		echo("titre : ".$this->titleDoc."<br>\n");
		echo("mn�monique : ".$this->mnemonicDoc."<br>\n");
		echo("�dition : ".$this->editionDoc."<br>\n");
		
		
		//les chapitres
		foreach($this->chapitres as $chap) {
			$chap->affiche();
		}
	}
	
	function destroy()
	{
		foreach($this->chapitres as $chapitre) {
			unset($chapitre);
		}
	}
		
} //fin de la classe Document d�finit les propri�t�s du cahier de recette r�cup�r�
	
	
//la classe Chapitre d�finit les propri�t�s d'un Chapitre, �l�ment d'entr�e du cahier
//de recette
class Chapitre
{
	//attributs
	var $num = "";	//num�ro de chapitre
	var $selected;	//s�lectionn� ou non ?? //s�lectionn� par d�faut
	var $nom = "";
	//var $parent;	//document auquel se rapporte ce chapitre
	var $sousChap = array(); //sous-chapitres
	var $fr = array();	//fiches de recette dans le cas ou ce chapitre n'a pas
				//de sous-chapitre
	//constructeur
	function Chapitre($numero, $name)
	{
		$this->num = $numero;
		$this->nom = $name;
		// JNG			$this->selected = 1;
		// JNG 	
		$this->selected = 0;
	}
	
	//m�thodes
	
	function ajouterSousChap($unSousChap)
	{
		//$unSousChap->parent = $this;
		$unSousChap->numParent = $this->num;
		$this->sousChap[$this->nbSousChap() - 1] = $unSousChap;
	}
	
	function ajouterFr($uneFr)
	{
		//$uneFr->parent = $this;
		$uneFr->numParent = $this->num;
		$this->fr[$this->nbFr() - 1] = $uneFr;
	}
	
	//nombre de FR selectionn�es et non s�lectionn�es
	function nbFr()
	{
		return count($this->fr);
	}
	
	//nombre de Fr s�lectionn�es
	function nbFrSelected() {
		$nb=0;
		for ($i = -1;$i < count($this->fr) - 1; $i++) {
		$uneFr = &$this->fr[$i];
			if ($uneFr->selected == 1)
				$nb++;
		}
		return $nb;
	}
	
	//nombre de sous-chapitres
	function nbSousChap()
	{
		return count($this->sousChap);
	}
	
	//nombre de sous-chapitres s�lectionn�es
	function nbSousChapSelected()
	{
		$nb=0;
		for ($i = -1;$i < count($this->sousChap) - 1; $i++) {
		$unSousChapitre = &$this->sousChap[$i];
			if ($unSousChapitre->selected == 1)
				$nb++;
		}
		return $nb;
	}

	//cette fonction permet lorsque l'utilisateur a d�coch� un chapitre de 
	//d�cocher ses sous-chapitres et/ou ses FR
	function unselect()
	{
		$this->selected = 0;
		
		for ($i = -1;$i < count($this->sousChap) - 1; $i++) {
			$sousChapitre = &$this->sousChap[$i];
			$sousChapitre->unselect();
		}
		
		for ($i = -1;$i < count($this->fr) - 1; $i++) {
			$uneFr = &$this->fr[$i];
			$uneFr->unselect();
		}
	}
	
	//cette fonction permet lorsque l'utilisateur a coch� un chapitre de 
	//s�lectionner ses FR et/ou ses sous-chapitres
	function select()
	{	
		$this->selected = 1;
		
		for ($i = -1;$i < count($this->sousChap) - 1; $i++) {
			$sousChapitre = &$this->sousChap[$i];
			$sousChapitre->select();
		}
			
		for ($i = -1;$i < count($this->fr) - 1; $i++) {
			$uneFr = &$this->fr[$i];
			$uneFr->select();
		}
	}
	
	function affiche()
	{
		echo($this->num."  ".$this->nom."<br>\n");
		echo("s�lectionn� : ".$this->selected."<br>\n");
		
		//les fiches de recette
		foreach($this->fr as $uneFr){
			$uneFr->affiche();
		}
		//les sous-chapitres
		foreach($this->sousChap as $sc){
			$sc->affiche();
		}
		echo("<br><br>\n");
	}
		
	//destructeur
	function destroy()
	{
		//les fiches de recette
		foreach($this->fr as $uneFr){
			unset($uneFr);
		}
		
		//les sous-chapitres
		foreach($this->sousChap as $sc){
			unset($sc);
		}
	}
		
} // fin de la classe Chapitre


//la classe SousChapitre d�finit les propri�t�s d'un sous-chapitre
class SousChapitre
{
	//attributs
	var $num = "";	//num�ro de sous-chapitre (ex 1.1)
	var $selected; //s�lectionn� par d�faut
	var $nom = "";
	//var $parent;	//chapitre auquel appartient ce sous-chapitre
	var $numParent=""; 	//num�ro de chapitre auquel est affect� ce sous-chapitre
			//utilis� pour la s�rialisation/d�s�rialisation
	var $fr = array(); //fiches de recette
	
	//constructeur
	function SousChapitre($numero, $name)
	{
		$this->num = $numero;
		$this->nom = $name;
		// JNG			$this->selected = 1;
		// JNG 	
		$this->selected = 0;
	}
	
	//m�thodes
	
	function ajouterFr($uneFr)
	{
		//$uneFr->parent = $this;
		$uneFr->numParent = $this->num;
		$this->fr[$this->nbFr() - 1] = $uneFr;
	}
	
	//nombre de FR selectionn�es et non s�lectionn�es
	function nbFr()
	{
		return count($this->fr);
	}

	//nombre de Fr s�lectionn�es
	function nbFrSelected() {
		$nb=0;
		for ($i = -1;$i < count($this->fr) - 1; $i++) {
			$uneFr = &$this->fr[$i];
			if ($uneFr->selected == 1)
				$nb++;
		}
		return $nb;
	}
	
	//cette fonction permet lorsque l'utilisateur a d�coch� un sous-chapitre de 
	//d�cocher ses FR
	function unselect()
	{		
		$this->selected = 0;
		
		for ($i = -1;$i < count($this->fr) - 1; $i++) {
			$uneFr = &$this->fr[$i];
			$uneFr->unselect();
		}
	}
	
	//cette fonction permet lorsque l'utilisateur a coch� un sous-chapitre de 
	//s�lectionner ses FR
	function select()
	{		
		$this->selected = 1;
		
		for ($i = -1;$i < count($this->fr) - 1; $i++) {
			$uneFr = &$this->fr[$i];
			$uneFr->select();
		}
	}
	
	function affiche()
	{
		echo("----".$this->num."  ".$this->nom."<br>\n");
		echo("s�lectionn� : ".$this->selected."<br>\n");
		foreach($this->fr as $uneFr){
			$uneFr->affiche();
		}
		echo("<br><br>\n");
	}
		
	//destructeur
	function destroy()
	{
		foreach($this->fr as $uneFr){
			unset($uneFr);
		}
	}
		
} // fin de la classe SousChapitre


//la classe FR d�finit les propri�t�s d'une fiche de recette
class FR
{
	//attributs
	var $selected;	//s�lectionn�e par d�faut
	var $mne = ""; 	//mn�monique
	var $des = "";	//d�signation
	var $ref = ""; 	//r�f�rence
	var $lgue = "";	//langue : FR pour fran�ais, EN pour anglais et SP pour espagnol
	var $ed = "";	//�dition
	var $edtmp = ""; //edition initial
	//var $parent;	//sous-chapitre auquel est attach� cette fiche de recette
	var $numParent = ""; //num�ro du chapitre ou sous-chapitre
			     //utilis� pour la s�rialisation/d�s�rialisation
	//*****
	var $tdr =""; //referinta 3DR
	var $ndr =""; // nume doc 3DR
	//*****
			      
	//constructeur
	function FR($mnemonique = "", $designation = "", $reference = "", $edition = "", $editioni ="")
	{
		$this->mne = $mnemonique;
		$this->des = $designation;
		$this->ref = $reference;
		$this->ed = $edition;
		$this->edtmp = $editioni;
		// JNG			$this->selected = 1;
		// JNG 	
		$this->selected = 0;
		//*****
		$this->tdr = "";
		$this->ndr = "";
		//*****
				
		//afin de faciliter les traitements ult�rieurs on va affecter a la
		//propri�t� lgue la valeur correspondante en fonction de la derni�re
		//lettre de la r�f�rence
		$lastLetter = $reference[strlen($reference) - 1];
		if (strcmp($lastLetter,"A") == 0) {
			$this->lgue = "EN";
		} elseif (strcmp($lastLetter,"B") == 0) {
			$this->lgue = "FR";
		} elseif (strcmp($lastLetter,"D") == 0) {
			$this->lgue = "SP";
		} else {
			die(sprintf("%s : code langue inconnu !!",$lastLetter));
		} 
	}
	
	//m�thodes
	
	//cette fonction permet de d�s�lectionner la FR
	function unselect()
	{
		$this->selected = 0;
	}
	
	//cette fonction permet de s�lectionner la FR
	function select()
	{
		$this->selected = 1;
	}
	
	function affiche()
	{
	if ($langue_browser == "FR"){
		echo("--------Fiche de recette :<br>\n");
		echo("mn�monique : ".$this->mne."<br>\n");
		echo("d�signation: ".$this->des."<br>\n");
		echo("r�f�rence : ".$this->ref."<br>\n");
		echo("langue : ".$this->lgue."<br>\n");
		echo("�dition : ".$this->ed."<br>\n");
		echo("s�lectionn�e : ".$this->selected."<br>\n");
		}else{
		echo("--------Acceptance sheet :<br>\n");
		echo("mnemonique : ".$this->mne."<br>\n");
		echo("designation: ".$this->des."<br>\n");
		echo("reference : ".$this->ref."<br>\n");
		echo("language : ".$this->lgue."<br>\n");
		echo("edition : ".$this->ed."<br>\n");
		echo("selected : ".$this->selected."<br>\n");
		}
	}
	
	//destructeur
	function destroy()
	{
		//ne fait rien
	}
		
		
} //fin de la classe FR

//*********************************************************************************************

class TroisDR
{
	//attributs
	var $domain3DR = "";  //domeniu 
	var $reference3DR = ""; //referinta 3DR
	var $name3DR = "";		//nume 3DR
	var $referenceFR3DR = "";	//rferinta FR asociata la 3DR
	var $modificed = 0; //edition modifie par utilizateur
	
	//constructeur
	function TroisDR ($domain = "", $reference = "", $name = "", $referenceFR = "")
	{
		$this->domain3DR = $domain;
		$this->reference3DR = $reference;
		$this->name3DR = $name;
		$this->referenceFR3DR = $referenceFR;
		$this->modificed = $modificed;
		
	}
	
	//m�thodes
	
		
	function affiche()
	{
	if ($langue_browser == "FR"){
		echo("Cahier de recette<br>\n");
		echo("domaine : ".$this->domain3DR."<br>\n");
		echo("referenceFR : ".$this->reference3DR."<br>\n");
		echo("nom : ".$this->name3DR."<br>\n");
		echo("referenceFR : ".$this->referenceFR3DR."<br>\n");
}else{
		echo("Acceptance book<br>\n");
		echo("domain: ".$this->domain3DR."<br>\n");
		echo("Acceptance sheet reference: ".$this->reference3DR."<br>\n");
		echo("name: ".$this->name3DR."<br>\n");
		echo("Acceptance sheet reference: ".$this->referenceFR3DR."<br>\n");
}
	}
	
	function destroy()
	{
	}
	
		
}


class ColectionTDR
{
	//attributs
	var $nameC = "";// nom collectin 3DR
/*	var $rftdr =""; //rfe 3dr
	var $numetdr = ""; //nume 3dr
	var $rffr = ""; //ref fr
*/	var $troisDR = array(); //fiches de recette
	
	//constructeur
	function ColectionTDR ($name/*, $azz, $bzz, $czz*/)
	{
		$this->nameC = $name;
	/*	$this->rftdr = $azz;
		$this->numetdr = $bzz;
		$this->rffr = $czz;
	*/		
	}
	
	//m�thodes
	
	function ajouterFr($tdr)
	{
		//$uneFiche3DR->parent = $this;
		$tdr->colectionTDR = $this;
		$this->troisDR[$this->nbtdr() - 1] = $troisDR;
	}
	
	//nombre de FR selectionn�es et non s�lectionn�es
	function nbtdr()
	{
		return count($this->$troisDR);
	}

		
	//destructeur
	function destroy()
	{
		foreach($this->$troisDR as $p){
			unset($p);
		}
	}
		
} // fin de la classe ColectionTDR




class Editionss
{
	//attributs
	var $matrix = array ();
	
	function Editionss ()
	{
	//this->matrix = $matrix;
	}
}

class Delta

{
	var $name = "COLLECTION";
	var $frDelta = array();
	function Delta ()
	{

	}
	
	
	function nbFr()
	{
		return count($this->frDelta);
	}	
	
	function ajouterFr($uneFr)
	{
		//$uneFr->parent = $this;
		$uneFr->numParent = $this->num;
		$this->frDelta[$this->nbFr() - 1] = $uneFr;
	}
}


//class qui contienne tout les FR du CRs compare : nom, ref, edition FR en CR1, edition FR en CR2
class frDelta

{
	function frDelta($ref, $nom, $cr1, $cr2)
	{
		$this->ref = $ref;
		$this->nom = $nom;
		$this->cr1 = $cr1;
		$this->cr2 = $cr2;
	}
}

//******************************************************************************************** 
?>
