<?php

/****************************************************************************************/
/***************************** sygListProClasses.php ************************************/
/****************************************************************************************/
/* auteur : David MORIN									*/
/* Description: ce fichier définit l'ensemble des classes conçues pour structurer et	*/
/* définir les comportements des objets élaborés à partir des données du fichier	*/
/* liste_cr.xml 									*/
/*  											*/
/* classes:										*/
/*  											*/
/****************************************************************************************/

//la classe Systeme définit les propriétés d'un système à savoir un 
//profuit qui dispose d'une documentation
class Systeme
{
	//attributs
	var $nom = "";
	var $projets = array(); //projets pour ce système
	
	//constructeur
	function Systeme($name)
	{
		$this->nom = $name;
	}
	
	//méthodes
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
		echo("Système ".$this->nom."<br>\n");
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


//la classe Projet définit les propriétés d'un projet pour un système
class Projet
{
	//attributs
	var $nom = "";
	var $systeme; //système auquel est affecté ce projet
	var $cr = array(); //cahiers de recette du projet
	
	//constructeur
	function Projet($name) 
	{
		$this->nom = $name;
	}
	
	//méthodes
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
		//possibilité d'utiliser foreach mais n'existe qu'en PHP4
		//on supprime tous les CR associés à ce projet
		while(list($indice,$leCR) = each($this->cr)){
			unset($leCR);
		}
	}
	
} //fin de la classe Projet


//la classe CR définit les propriétés d'un cahier de recette pour un projet
class CR
{
	//attributs
	var $desc = "";	//descriptif
	var $ref = ""; 	//référence
	var $lgue = "";	//langue : FR pour français, EN pour anglais et SP pour espagnol
	var $projet;	//projet auquel est affecté le cahier de recette
	
	//constructeur
	function CR($descriptif, $reference)
	{
		$this->desc = $descriptif;
		$this->ref = $reference;
				
		//afin de faciliter les trauitements urltérieurs on va affecter a la
		//propriété lgue la valeur correspondante en fonction de la dernière
		//lettre de la référence
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
	
	//méthodes
	function affiche()
	{
	if ($langue_browser == "FR"){
		echo("--------Cahier de recette :<br>\n");
		echo("descriptif: ".$this->desc."<br>\n");
		echo("référence : ".$this->ref."<br>\n");
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

