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
 *./gediget 3FQ-40013-ABAA-TQZZA fr -pdf
 *./gediget 3FQ-40013-ABAA-TQZZA fr 
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
if(isset( $parameters['language']) && $parameters['language']){
	$lang = $parameters['language'];
}
// add logic of get the default file extension

$ret = array
if(isset($parameters['-pdf']) && $parameters['-pdf']){
	$format = "pdf";
} else {
	$ret = get_file_format_edition(WGET_PATH,$user,$passwd,$number,showEntryInWeblibURL);
	$format = $ret['format'];
}

//@unlink($tmpFile);

if(isset($parameters['-save']) && $parameters['-save']){
	$tmpFile = $parameters['-save'];
}else {
	if(isset( $parameters['file']) && $parameters['file']){
		$tmpFile = dirname(__FILE__).'/tmp/'.$parameters['file'];
	}
	if(isset( $parameters['edition']) && $parameters['edition']){
		$tmpFile.="_".$parameters['edition'];
	}else{
		$ret = get_file_format_edition(WGET_PATH,$user,$passwd,$number,showEntryInWeblibURL);
		$tmpFile.="_".$ret['edition'];
	}
	if(isset( $parameters['language']) && $parameters['language']){
		$tmpFile.="_".strtoupper($parameters['language']);
	}
	$tmpFile.=".".$format;
	
}

$viewDocInWeblibURL = viewDocInWeblibURL;
$cmd = WGET_PATH." -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$viewDocInWeblibURL?number=$number&mode=source&source_format=$format&no_index_sheet\" --output-document=\"$tmpFile\"";

//$cmd = "$wget -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$viewDocInWeblibURL?number=$number&mode=source&no_index_sheet\" --output-document=\"$tmpFile\"";

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

function get_file_format_edition($path,$user,$passwd,$number,$api){
	$ret= array();
	$ret['format'] = "";
	$ret['edition'] = "";
	$tmpFile = tempnam (dirname(__FILE__).'/tmp/', "theRequesteContent");
	$cmd = $path." -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$api&number=$number\" --output-document=\"$tmpFile\"";
	@system($cmd, $rc);
	$content =  file_get_contents($tmpFile);
	preg_match_all("/<br>Format: <span style=\"(.*?)\">(.*?)<\/span>&nbsp;&nbsp;Category/", $content, $matches, PREG_SET_ORDER);
	preg_match_all("/<span class=\"button1\" style=\"font-size: 11px;\">(.*?)<\/span>/", $content, $matches2, PREG_SET_ORDER);
	@unlink($tmpFile);
    if($matches){
		$ret['format']=$matches[0][2];
	}
	if($matches2){
		$ret['edition']=$matches2[0][1];
	}
	return $ret;
	
}