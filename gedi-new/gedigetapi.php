<?php
/**
 * Gedi Get command
 *
 * This scipt represents an  Gedi GET command
 * @author Patrick WU
 * @package gedi
 * @version 1.0
 * Array
 * 	(
 *      [file] => a
 * 		[language] => fr
 * 		[edition] => 20
 * 		[-cn] => aaabc
 * 		[-pass] => 1433
 * 		[-title] => -title
 * 		[-gediip] => gedi.ln.cit.alcatel.fr
 * 
 * 	)
 * For example
 * ./gediget 3FQ-40013-ABAA-TQZZA fr -cn xinyeh -pass Wykss@123 
 * ./gediget 3FQ-40013-ABAA-TQZZA fr -cn xinyeh -pass Wykss@123 -pdf 
 * ./gediget 3FQ-40013-ABAA-TQZZA fr -cn xinyeh -pass Wykss@123 -save test.pptx 
 * ./gediget 3FQ-40013-ABAA-TQZZA fr -cn xinyeh -pass Wykss@123 -pdf -save test.pdf 
 */
require_once(dirname(__FILE__).'/constants.inc.php');

//$tmpFile = tempnam (dirname(__FILE__).'/tmp/', "theRequesteContent"); 

if(isset( $parameters['-cn']) && $parameters['-cn']){
	$user = $parameters['-cn'];
}

if(isset( $parameters['-pass']) && $parameters['-pass']){
	$passwd = $parameters['-pass'];
}

if(isset( $parameters['file']) && $parameters['file']){
	$number = $parameters['file'];
}

//-	Add the version 01P01 so 3FQ-40013-AJAA-PBZZA-01P01  otherwise it will be 3FQ-40013-AJAA-PBZZA
if(isset( $parameters['edition']) && $parameters['edition']){
	$number.="-".$parameters['edition'];
}

$ret = array();
if($number){
	$ret = get_file_format_edition(WGET_PATH,$user,$passwd,$number,showEntryInWeblibURL);
}

$lang = "";
if(isset( $parameters['language']) && $parameters['language']){
	$lang = $parameters['language'];
}

// add logic of get the default file extension
if(isset($parameters['-pdf']) && $parameters['-pdf']){
	$format = "pdf";
} else {
	$format = $ret['Format'];
}

if(isset($parameters['-save']) && $parameters['-save']){
	$tmpFile = $parameters['-save'];
}else {
	if(isset( $parameters['file']) && $parameters['file']){
		$tmpFile = dirname(__FILE__).'/'.$parameters['file'];
	}
	if(isset( $parameters['edition']) && $parameters['edition']){
		$tmpFile.="_".$parameters['edition'];
	}else{
		$tmpFile.="_".$ret['DocVersion'];
	}
	if($lang){
		$tmpFile.="_".strtoupper($lang);
	}
	$tmpFile.=".".$format;
	
}

$viewDocInWeblibURL = viewDocInWeblibURL;
$cmd = WGET_PATH." -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$viewDocInWeblibURL?number=$number&mode=source&source_format=$format&no_index_sheet\" --output-document=\"$tmpFile\"";

@system($cmd, $rc);

if ($rc == "0") { 
	$fh = fopen($tmpFile, 'r'); 
	$opt = fread($fh, filesize($tmpFile)); 
	fclose($fh); 
	//unlink($tmpFile); 

	if (stristr($opt, "has no access")) { 
	  echo("You don't have access to document/template <a href=\"$viewDocInWeblibURL?number=$number\" style=\"color: rgb(0, 0, 255);\">$number</a>..."); 
	} 
	if (stristr($opt, "not found in the Web Library")) { 
	  echo("Couldn't find document <a href=\"$viewDocInWeblibURL?number=$number\" style=\"color: rgb(0, 0, 255);\">$number</a> in the Web Library..."); 
	} 
	if (stristr($opt, "Problem with document")) { 
	  echo("Problem with document <a href=\"$viewDocInWeblibURL?number=$number\" style=\"color: rgb(0, 0, 255);\">$number</a> in the Web Library. "); 
	} 
} else { 
	echo("Errorcode $rc received when downloading your document <a href=\"$viewDocInWeblibURL?number=$number\" style=\"color: rgb(0, 0, 255);\">$number</a>..."); 
} 

/**********************************************************
@parameter  
   $number  DOCnumber
@response  array()
	array(14) {
	  ["DocNbr"]=>
	  string(20) "3FQ-40013-ABAA-TQZZA"
	  ["DocVersion"]=>
	  string(2) "19"
	  ["Status"]=>
	  string(2) "RL"
	  ["Title"]=>
	  string(31) "Document Management with Weblib"
	  ["Author"]=>
	  string(13) "johnnyreusens"
	  ["ACL"]=>
	  string(0) ""
	  ["Format"]=>
	  string(4) "pptx"
	  ["Product"]=>
	  string(1) "M"
	  ["Category"]=>
	  string(5) "TOOLS"
	  ["ProjectBuild"]=>
	  string(0) ""
	  ["Feature"]=>
	  string(0) ""
	  ["Keywords"]=>
	  string(6) "WEBLIB"
	  ["TimeStamp"]=>
	  string(12) "130726 08:58"
	  ["Size"]=>
	  string(7) "5066336"
	}
**************************************************************/
function get_file_format_edition($path,$user,$passwd,$number,$api){
	$tmpFile = tempnam (dirname(__FILE__).'/tmp/', "theRequesteContent");
	$cmd = $path." -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$api&number=$number\" --output-document=\"$tmpFile\"";
	@system($cmd, $rc);
	$content =  file_get_contents($tmpFile);
	preg_match_all("/<tr><th>(.*?)<\/th><td>(.*?)<\/td><\/tr>/", $content, $matches, PREG_SET_ORDER);
	@unlink($tmpFile);
	$attribute = array();
	if($matches){
		foreach($matches as $key=>$val){
			$attribute[$val[1]] = $val[2];
		}
	}
	return $attribute;
	
}
//---------------------------------------------------------------