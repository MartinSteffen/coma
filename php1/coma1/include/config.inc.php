<?php
/**
 * Konfiguration der ServerParameter
 *
 * @version $Id$
 * @package coma1
 * @subpackage Configuration
 */
/***/
if ( !defined('IN_COMA1') )
{
  exit('Hacking attempt');
}

/**#@+
 *@ignore
 */
$sqlServer = 'localhost';
$sqlUser = 'coma1';
$sqlPassword = 'Rhashdeak';
$sqlDatabase = 'coma1';

$ftpServer = 'localhost';
$ftpUser = 'ftp-php1';
$ftpPassword = 'janMiUct';
/**#@-*/

/**
 * Loeschen von Passwoertern
 *
 * Diese Funktion sollte nach jedem include dieser Datei so schnell
 * wie moeglich nach dem Include dieser Datei ausgefuert werden,
 * um eventuellen Passworddiebstahl oder so zu erschweren
 *
 * Ist das wirklich noetig? Und bringt es ueberhaupt was? -Jan
 *
 */
//function ConfigClearVariables()
//{
//  unset($sqlPassword);
//  unset($ftpPassword);
//  return true;
//}

?>