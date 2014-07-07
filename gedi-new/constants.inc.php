<?php
/**
 * GEDI Constants configuration file
 *
 * This class represents an GEDI Constants configuration file.
 * @author Patrick WU
 * @package gedi
 * @version 1.0
 */
/**#use this url for content retrieval*/ 
define('viewDocInWeblibURL',"https://ct.web.alcatel-lucent.com/scm-lib4/view.cgi");
/**#user this url for simple metadata retrieval. Use actions=yes for full metadate retrieval. Please note this produces the standard html display which might change. An output that will not change is under development using the actions=hilde is under development.*/ 
define('showEntryInWeblibURL',"https://ct.web.alcatel-lucent.com/scm-lib4/show-entry.cgi?actions=hilde");
/**#use this url for content upload*/ 
define('uploadFileWeblib',"https://ct.web.alcatel-lucent.com/scm-lib4/create-entry2_acl.cgi");
/**Run path of perl*/
define('PERL_PATH',"/usr/bin/perl");
/**Run path of lynx*/
define('LYNX',"/usr/bin/lynx");
/**Run path of Wget*/
define('WGET_PATH',"/usr/bin/wget");

?>