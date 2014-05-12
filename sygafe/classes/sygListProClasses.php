<?php

/****************************************************************************************/
/***************************** sygListProClasses.php ************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: ce fichier d�finit l'ensemble des classes con�ues pour structurer et	*/
/* d�finir les comportements des objets �labor�s � partir des donn�es du fichier	*/
/* liste_cr.xml 									*/
/*  											*/
/* classes:										*/
/*  											*/
/****************************************************************************************/

//la classe Systeme d�finit les propri�t�s d'un syst�me � savoir un 
//profuit qui dispose d'une documentation
class Systeme
{
	//attributs
	var $nom = "";
	var $projets = array(); //projets pour ce syst�me
	
	//constructeur
	function Systeme($name)
	{
		$this->nom = $name;
	}
	
	//m�thodes
	function ajouterProjet($projet)
	{
		$projet->systeme = $this;
		$this->projets[$this->nbProjets() - 1] = $projet;
	}
	
	function nbProjets()
	{
		return count($this->projets);
	}

	function affiche()
	{
		echo("Syst�me ".$this->nom."<br>\n");
		foreach($this->projets as $p){
			$p->affiche();
		}
		echo("<br><br>\n");
	}
		
	//destructeur
	function destroy()
	{
		foreach($this->projets as $p){
			unset($p);
		}
	}
		
} // fin de la classe Systeme


//la classe Projet d�finit les propri�t�s d'un projet pour un syst�me
class Projet
{
	//attributs
	var $nom = "";
	var $systeme; //syst�me auquel est affect� ce projet
	var $cr = array(); //cahiers de recette du projet
	
	//constructeur
	function Projet($name) 
	{
		$this->nom = $name;
	}
	
	//m�thodes
	function ajouterCR($unCR)
	{
		$unCR->projet = $this;
		$this->cr[$this->nbCR() - 1] = $unCR;
	} 
	
	function nbCR()
	{
		return count($this->cr);
	}
	
	function affiche()
	{
		echo("----Projet ".$this->nom."<br>\n");
		foreach($this->cr as $cahier){
			$cahier->affiche();
		}
	}
	
	//destructeur
	function destroy()
	{
		//possibilit� d'utiliser foreach mais n'existe qu'en PHP4
		//on supprime tous les CR associ�s � ce projet
		while(list($indice,$leCR) = each($this->cr)){
			unset($leCR);
		}
	}
	
} //fin de la classe Projet


//la classe CR d�finit les propri�t�s d'un cahier de recette pour un projet
class CR
{
	//attributs
	var $desc = "";	//descriptif
	var $ref = ""; 	//r�f�rence
	var $lgue = "";	//langue : FR pour fran�ais, EN pour anglais et SP pour espagnol
	var $projet;	//projet auquel est affect� le cahier de recette
	
	//constructeur
	function CR($descriptif, $reference)
	{
		$this->desc = $descriptif;
		$this->ref = $reference;
				
		//afin de faciliter les trauitements urlt�rieurs on va affecter a la
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
			die(sprintf("%s : Unknown language code !!",$lastLetter));
		} 
	}
	
	//m�thodes
	function affiche()
	{
	if ($langue_browser == "FR"){
		echo("--------Cahier de recette :<br>\n");
		echo("descriptif: ".$this->desc."<br>\n");
		echo("r�f�rence : ".$this->ref."<br>\n");
		echo("langue : ".$this->lgue."<br>\n");
	}else{
		echo("--------Acceptance book:<br>\n");
		echo("description: ".$this->desc."<br>\n");
		echo("reference: ".$this->ref."<br>\n");
		echo("language: ".$this->lgue."<br>\n");		}
	}
	
	//destructeur
	function destroy()
	{
		//ne fait rien
	}
		
} //fin de la classe CR


?>

