<?php
$viewDocInWeblibURL = "https://ct.web.alcatel-lucent.com/scm-lib4/view.cgi"; #use this url for content retrieval 
$showEntryInWeblibURL = "https://ct.web.alcatel-lucent.com/scm-lib4/show-entry.cgi?actions=minimal"; #user this url for simple metadata retrieval. Use actions=yes for full metadate retrieval. Please note this produces the standard html display which might change. An output that will not change is under development using the actions=hilde is under development. 
$lynx = "/usr/bin/lynx"; 
$wget = "/usr/bin/wget";

//$number = <the ALU number of the document eg 3FQ-12345-ABCD-DDZZA>
//-	Add the version 01P01 so 3FQ-40013-AJAA-PBZZA-01P01 
$number = "3FQ-40013-ABAA-TQZZA";
//$number = "3FQ-40013-AJAA-PBZZA-01P01";

$format = "pptx"; #example of doc format

global $templates, $wget, $viewDocInWeblibURL; 
//$user = $_SERVER['AUTHENTICATE_POLARISUID']; 
//$passwd = $_SERVER['PHP_AUTH_PW']; 
$user = "xinyeh";
$passwd = "Wykss@123";
//$passwd = preg_replace("/\\$/", "\\\\$", $passwd);

$tmpFile = tempnam ("/home/shawn/gedi/tmp", "theRequesteContent"); 
$cmd = "$wget -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$viewDocInWeblibURL?number=$number&mode=source&source_format=$format&no_index_sheet\" --output-document=\"$tmpFile\"";
//$cmd = "$wget -q --no-check-certificate --http-user=\"$user\" --http-passwd=\"$passwd\" \"$viewDocInWeblibURL?number=$number&mode=source&no_index_sheet\" --output-document=\"$tmpFile\"";

//echo $cmd;

@system($cmd, $rc);

 if ($rc == "0") 
      { 
        $fh = fopen($tmpFile, 'r'); 
        $opt = fread($fh, filesize($tmpFile)); 
        fclose($fh); 
        //unlink($tmpFile); 

        if (stristr($opt, "has no access")) 
        { 
          echo("You don't have access to document/template <a href=\"$viewDocInWeblibURL?number=$number\" style=\"color: rgb(0, 0, 255);\">$number</a>..."); 
        } 
        if (stristr($opt, "not found in the Web Library")) 
        { 
          echo("Couldn't find document <a href=\"$viewDocInWeblibURL?number=$number\" style=\"color: rgb(0, 0, 255);\">$number</a> in the Web Library..."); 
        } 
        if (stristr($opt, "Problem with document")) 
        { 
          echo("Problem with document <a href=\"$viewDocInWeblibURL?number=$number\" style=\"color: rgb(0, 0, 255);\">$number</a> in the Web Library. "); 
        } 
      } 
      else 
      { 
        echo("Errorcode $rc received when downloading your document <a href=\"$viewDocInWeblibURL?number=$number\" style=\"color: rgb(0, 0, 255);\">$number</a>..."); 
      } 
