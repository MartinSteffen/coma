<?php
/**
 * Install Skript für Coma1
 *
 * Stand der Tabellen 23.01.2005
 *
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig, damit Config Dateie eingebunden werden kann
 *
 * @ignore
 */
define('IN_COMA1', true);
// MySQL Daten bereit stellen
require_once('./include/config.inc.php');

$conn = @mysql_connect($sqlServer, $sqlUser , $sqlPassword);
if (empty($conn)) {
  echo("Could not connect to MySQL Server $sqlServer!<br>");
  echo("Please check 'include/config.inc.php' for correct values!<br><br>");
}
if (!mysql_select_db($sqlDatabase, $conn)) {
  echo(mysql_error($conn).'<br>');
  echo("Please check 'include/config.inc.php' for correct values!<br><br>");
}

$sql = 'DROP TABLE IF EXISTS `Conference`, `ConferenceConfig`, `Criterion`, '.
       '                     `DeniesPaper`, `Distribution`, `ExcludesPaper`,'.
       '                     `Forum`, `IsAboutTopic`, `IsCoAuthorOf`, `Message`,'.
       '                     `Paper`, `Person`, `PrefersPaper`, `PrefersTopic`,'.
       '                     `Rating`, `ReviewReport`, `Role`, `Session`, `Topic`';
$result = mysql_query($sql, $conn);
if (empty($result)) {
  echo('Dropping Tables:<br>');
  echo(mysql_error($conn).'<br><br>');
}

// BEGIN SQL DATA
$sql = "

";
// END SQL DATA

$result = mysql_query($sql, $conn);
if (empty($result)) {
  echo('Creating Tables:<br>');
  echo(mysql_error($conn).'<br><br>');
}
?>