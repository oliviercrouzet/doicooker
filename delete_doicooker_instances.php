<?php
if (PHP_SAPI != 'cli') die('cli only');  // php-cli only

/*******************************************************************
  delete_doicooker_instances
  Description:
  - delete mysql records of doicooker plugin in plugin table of sites and in mainplugins table of main site
   
  Install:
  - copy or make a symbolic link of the file in the root directory of the Lodel install
  
  Execute:
  - cd PATH_TO_ROOT_LODEL_DIRECTORY
  - php delete_doicooker_instances.php mysite # update the site "mysite"
    or
    php delete_doicooker_instances.php all # update all sites (excepted site listed in the array $exclude. See below)
  - after execution, this file should be removed from Lodel root directory
 *******************************************************************/

require_once('lodel/install/scripts/me_manipulation_func.php');

define('DO_NOT_DIE', true); // only die of a server error
// define('QUIET', true);   // no output
$exclude = array();         // the $exclude array may contain site names to be excluded from processing at execution with the  parameter "all"

$sites = new ME_sites_iterator($argv, 'errors'); // 'errors' display only errors ot the function ->m()
while ($siteName = $sites->fetch()) {
  if (in_array($siteName, $exclude)) continue;
    echo "\tsupression du plugin DOI cooker pour ce site \n";
    $db->execute(lq ("DELETE FROM #_TP_plugins where name='doicooker';"));
}

echo "***Travail dans l'Administration générale ***\n";
echo "\tsupression du plugin DOI cooker dans la table 'mainplugins' \n";
$base_lodel = c::Get('database','cfg');
$GLOBALS['currentdb'] = $base_lodel;
usecurrentdb();
$db->execute(lq ("DELETE FROM #_TP_mainplugins where name='doicooker';"));

?>
