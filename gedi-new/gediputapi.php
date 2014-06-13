<?php
    /****************************************************************
	/**
	 * Gedi PUT command
	 *
	 * This scipt represents an  Gedi PUT command
	 * @author Patrick WU
	 * @package gedi
	 * @version 1.0
	 * Array
	 * 	(
	 *      [file] => a
	 * 		[language] => fr
	 * 		[edition] => 20
	 *      [-save] => test.pdf
	 * 		[-cn] => aaabc
	 * 		[-pass] => 1433
	 * 		[-title] => -title
	 * 		[-gediip] => gedi.ln.cit.alcatel.fr
	 * 
	 * 	)
	 * For example
	./gediput /home/patrick/gedi/gedi-new/tmp/test.html -reference '3FQ-40013-ABAA-PCZZA' -language FR -edition 04 -title 'test1234562' -summary 'test' -security NP -type M/TOOLS -status IP -reason 'test12341' -theme 'zzz' -format 'html' -status 'IP' -client '38' -date 2003-may-15
	*****************************************************************/
	//weblib api
	$outputfile = tempnam (dirname(__FILE__).'/tmp/', "theRequesteContent"); 

	if(isset( $parameters['-cn']) && $parameters['-cn']){
		$user = $parameters['-cn'];
	}

	if(isset( $parameters['-pass']) && $parameters['-pass']){
		$passwd = $parameters['-pass'];
	}

	if(isset( $parameters['file']) && $parameters['file']){
		$createFile = $parameters['file'];
	}
	if(isset( $parameters['-reference']) && $parameters['-reference']){
		$docNbrVersion = $parameters['-reference'];
		if(isset( $parameters['-edition']) && $parameters['-edition']){
			$docNbrVersion.="-".$parameters['-edition'];
		}
	}
	if(isset( $parameters['-type']) && $parameters['-type']){
		$category = $parameters['-type'];
	}
	if(isset( $parameters['-status']) && $parameters['-status']){
		$status = $parameters['-status'];
	}
	if(isset( $parameters['-format']) && $parameters['-format']){
		$format = $parameters['-format'];
	}
	if(isset( $parameters['-title']) && $parameters['-title']){
		$title = $parameters['-title'];
	}
	if(isset( $parameters['-reason']) && $parameters['-reason']){
		$comments = $parameters['-reason'];
	}
	if(isset( $parameters['-client']) && $parameters['-client']){
		$domain = $parameters['-client'];
	}
	if(isset( $parameters['-client']) && $parameters['-client']){
		$domain = $parameters['-client'];
	}
	$mode = "enter";
	//$mode ="checkin";


//    $user = "xinyeh";
//	$passwd = "Wykss@123";
//	$createFile = "/home/patrick/gedi/gedi-new/tmp/test.html";

//	$outputfile = "/home/patrick/gedi/gedi-new/tmp/20140526.html";

//	$category = "M/TOOLS";



//	$title = "test1234562";

//	$format="html";

//	$docNbrVersion= "3FQ-40013-ABAA-PCZZA-04";

	//$status = "IP";

	//$domain="38";

	//$comments= "test12341";


    $info  = "category=" . $category;
    $info .= "&mode=$mode";
    $info .= "&title=$title";
    $info .= "&format=$format";
    $info .= "&docversion=$docNbrVersion";
    $info .= "&acl=None";
    $info .= "&status=$status";
    $info .= "&domain=$domain";
    $info .= "&cnnr=";
    $info .= "&comments=$comments";
    $info .= "&filename=$createFile";
    $info .= "&projectbuild=";
    $info .= "&feat=";
    $info .= "&allfiles=no";

    $info = str_replace(" ", "%20", $info);
    $url = "https://ct.web.alcatel-lucent.com/scm-lib4/create-entry-API.cgi?$info";
    //$cmd = "$wget -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$url\" --output-document=\"$outputfile\"";
    //echo $url;
    //echo "<br>";
	$cmd = "/usr/bin/wget -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$url\" --output-document=\"$outputfile\"";

    $opt = @shell_exec("$cmd");
