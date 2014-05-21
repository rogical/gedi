<?php
require_once './lib/ParseINI.php';
$gediPath = $argv[1];
//php request api file name
$phpFile = $argv[2];
//document name
$parameters['file'] = isset($argv[3])?$argv[3]:'';
//language
$parameters['language'] = isset($argv[4])?$argv[4]:'';
//edition
if(!empty($argv[5])){
	if(substr($argv[5],0,1) !== '-')
		$parameters['edition'] = $argv[5];
}
foreach($argv as $k=>$arg){
	if(substr($arg,0,1) == '-'){
		if(isset($argv[$k+1])){
			if(substr($argv[$k+1],0,1) == '-'){
				$parameters[$arg] = $arg;
			}else{
				$parameters[$arg] = $argv[$k+1];
			}
		}else{
			$parameters[$arg] = $arg;
		}
	}
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

//parameters error message
$parameters_error = 'gediget <reference> <language> [<edition>] [options]

   reference   Reference of the document
   language    Language of the document
   edition     Edition of the document

If the edition is not set, the current edition of the document is used.

Options are :

 -gediip <ip address>         IP address of GEDI server
 -cn "<cn>"                   Complete Name
 -pass "<password>"           User password
 -pdf                         Get the PDF file of the document
 -source                      Get the source file of the document (default)
 -save <filename>             Save the file with the specified name
 -extract                     Extract the document for edition
 -module					  Get the module files attached to the document
 -nosrc                       Do not transfer the document
';

//file name
if(empty($parameters['file']) || substr($parameters['file'],0,1) == '-'){
	echo $parameters_error;
	exit();
}
//language
if(empty($parameters['language']) || substr($parameters['language'],0,1) == '-'){
	echo $parameters_error;
	exit();
}
// '-' parameters
$legal_parameters = array('-gediip','-cn','-pass','-pdf','-source','-save','-extract','-module','-nosrc');
foreach($parameters as $key=>$value){
	if(substr($key,0,1) == '-' && !in_array($key,$legal_parameters)){
		echo $parameters_error;
		exit();
	}
}

//do other options
require_once $gediPath.'/'.$phpFile;
