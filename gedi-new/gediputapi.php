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
	./gediput /home/patrick/gedi/gedi-new/tmp/test.html -reference '8DM-43347-6100-DDZZA' -language FR -edition 01 -title 'test1234562' -summary 'test6100' -security 'NP' -type 'M/TOOLS' -status 'IP' -reason 'test12341' -theme 'zzz' -format 'html' -client '38' -date '2003-may-15'
	*****************************************************************/
	require_once(dirname(__FILE__).'/constants.inc.php');
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
	$status = "";
	if(isset( $parameters['-status']) && $parameters['-status']){
		$status = $parameters['-status'];
	}
	if(isset( $parameters['-format']) && $parameters['-format']){
		$format = $parameters['-format'];
	}
	if(isset( $parameters['-title']) && $parameters['-title']){
		$title = $parameters['-title'];
	}
	$comments = "";
	if(isset( $parameters['-reason']) && $parameters['-reason']){
		$comments = $parameters['-reason'];
	}
	$domain = "";
	if(isset( $parameters['-client']) && $parameters['-client']){
		$domain = $parameters['-client'];
	}
	$mode = "enter";
	//$mode ="checkin";
	system(PERL_PATH.' fileencode.pl '.$createFile);
	$file_name = explode("/",$createFile);
	$real_file = "/tmp/". end($file_name);
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
    $info .= "&filename=$real_file";
    $info .= "&projectbuild=";
    $info .= "&feat=";
    $info .= "&allfiles=no";
	$info .= '&enctype=multipart/form-data';

    $info = str_replace(" ", "%20", $info);
	$url = uploadFileWeblib."?$info";
	$cmd = WGET_PATH." -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$url\" --output-document=\"$outputfile\"";
	echo $cmd;
    $opt = @shell_exec("$cmd");
	@unlink($real_file);
