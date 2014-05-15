<?php
require_once './lib/ParseINI.php';
$gediPath = $argv[1];
//php request api file name
$phpFile = $argv[2];
//document name
$parameters['file'] = $argv[3];
//language
$parameters['language'] = $argv[4];
//edition
if(substr($argv[5],0,1) !== '-')
	$parameters['edition'] = $argv[5];
	
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
if(!in_array('-gediip',$parameters)){
	//get gedi ip from gedi.ini
	$parameters['-gediip'] = $parseINIObj->get('-gediip');
}
//check username
if(!in_array('-cn',$parameters)){
	$parameters['-cn'] = $parseINIObj->get('-cn');
}
//check pass
if(!in_array('-pass',$parameters)){
	$parameters['-pass'] = $parseINIObj->get('-pass');
}

//use php file to request api

require_once $gediPath.'/'.$phpFile;
