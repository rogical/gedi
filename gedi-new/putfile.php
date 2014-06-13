<?php
    $user = "xinyeh";
	$passwd = "Wykss@123";
	$createFile = "/home/patrick/gedi/gedi-new/tmp/test12.doc";

	$outputfile = "/home/patrick/gedi/gedi-new/tmp/test122.txt";

	$category = "MY/SETUP";

	//$mode = "enter";
	$mode="checkin";

	$title = "test1234565";

	$format="doc";

	$docNbrVersion= "8DM-43347-1115-DDZZA-01";

	$status = "IP";

	$domain="";

	$comments= "test123411";


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
    echo $url;
    echo "<br>";
	$cmd = "/usr/bin/wget -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$url\" --output-document=\"$outputfile\"";

    $opt = @shell_exec("$cmd");

?>