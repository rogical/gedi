<?php
require_once './lib/ParseINI.php';
$gediPath = $argv[1];
unset($argv[0]);
unset($argv[1]);
//php request api file name
$phpFile = $argv[2];
unset($argv[2]);
//document name
$parameters['file'] = isset($argv[3])?$argv[3]:'';
unset($argv[3]);

foreach($argv as $k=>$arg){
	if(substr($arg,0,1) == '-' ){
		if(isset($argv[$k+1]) && substr($argv[$k+1],0,1) != '-'){
			$parameters[$arg] = @$argv[$k+1];
		}else{
			$parameters[$arg] = '';
		}
		
	}
	
}

//parameters error message
$parameters_error = 'gediput <sourcefile> [-reference <reference> -language <language>] [-edition <edition>]  [<options>] 

   sourcefile          File location

Options are :

  -pass                Pass of the user, like -pass "my_password"
  -security            Security level (NP,CD)
  -type                type of the document, it is the short name like GOP, FOP, FEX, NOTYPED (for other)
  -client              Specific client
  -gediip              IP adress of GEDI server
  -edition             Edition of the document
  -summary             Summary of the document
  -reference           Reference of the document
  -cn                  Corporate Intranet Login (CIL), like -cn "john doe"
  -statuspropnbr       Proposal number, only if the tatus is PD
  -mnemonic            Mnemonic of the document
  -codif               code of the document, like ST for standard or OTHER
  -autoValid           Automatic validation of the XML modules only like -autoValid
  -dtd                 dtd of the XML document
  -status              Status of the document (IP,PD,RL,RD,RP,WD,WR)
  -language            Language of the document (DE,EN,FR,IT,SP)
  -format              Format of the document, like DCF, XML, ALICE, ACDS, WORD, OTHER 
  -reason              raison of update
  -theme               Theme of the document
  -date                Availability date (YYYY-MMM-DD) (ex:2002-dec-08)
  -title               Title of the document
';

if(empty($parameters['file'])){
	echo $parameters_error;
	exit();
}

$parseINIObj = new ParseINI();
//check ip
if(!array_key_exists('-gediip',$parameters)){
	//get gedi ip from gedi.ini
	$parameters['-gediip'] = $parseINIObj->get('-gediip');
}
//check username
if(!array_key_exists('-cn',$parameters)){
	$parameters['-cn'] = $parseINIObj->get('-cn');
}
//check pass
if(!array_key_exists('-pass',$parameters)){
	$parameters['-pass'] = $parseINIObj->get('-pass');
}
//print_r($parameters);

//do other options
require_once $gediPath.'/'.$phpFile;
