<?php
    $user = "xinyeh";
	$passwd = "Wykss@123";
	$createFile = "/home/patrick/gedi/gedi-new/tmp/test.html";

	$outputfile = "/home/patrick/gedi/gedi-new/tmp/20140526.html";

	$category = "M/TOOLS";

	$mode = "enter";

	$title = "test1234562";

	$format="html";

	$docNbrVersion= "3FQ-40013-ABAA-PCZZA-04";

	$status = "IP";

	$domain="38";

	$comments= "test12341";


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
	echo "<pre>";
	var_dump($opt);
	echo "</pre>";
?>