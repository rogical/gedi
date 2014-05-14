<?php
require_once './gedi.ini.php';
$gediPath = $argv[1];
//php request api file name
$phpFile = $argv[2];
//document name
$parameters['file'] = $argv[3];
//language
$parameters['language'] = $argv[4];

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
//check ip
if(!in_array('-gediip',$parameters)){
	//get gedi ip from gedi.ini
		
}
//check username and pass
if(!in_array('-cn',$parameters)){

}
//check parameters legal

//use php file to request api

//print_r($parameters);

require_once $gediPath.'/'.$phpFile;
