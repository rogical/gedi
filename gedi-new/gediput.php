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
	if(substr($arg,0,1) == '-'){
		$parameters[$arg] = @$argv[$k+1];
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
//print_r($parameters);

//do other options
require_once $gediPath.'/'.$phpFile;
