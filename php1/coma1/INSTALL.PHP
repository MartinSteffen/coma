<?php
/**
 * Install Skript für Coma1
 *
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

// Funktionen bereit stellen
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

// Tabellen entfernen
$sql = 'DROP TABLE `Conference`, `ConferenceConfig`, `Criterion`, `DeniesPaper`,'.
       '           `Distribution`, `ExcludesPaper`, `Forum`, `IsAboutTopic`,'.
       '           `IsCoAuthorOf`, `Message`, `Paper`, `Person`, `PrefersPaper`,'.
       '           `PrefersTopic`, `Rating`, `ReviewReport`, `Role`, `Session`,'.
       '           `Topic`';

$result = mysql_query($sql, $conn);
if (empty($result)) {
  echo('Dropping Tables:<br>');
  echo(mysql_error($this->mySqlConnection).'<br><br>');
}

// Tabellen anlegen 1

$result = mysql_query($sql, $conn);
if (empty($result)) {
  echo('Creating Tables:<br>');
  echo(mysql_error($this->mySqlConnection).'<br><br>');
}


?>