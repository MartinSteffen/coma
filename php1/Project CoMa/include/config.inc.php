<?php
/**
 * Konfiguration der ServerParameter
 *
 * $Id$
 *
 */

$sqlServer = "localhost";
$sqlUser = "coma1";
$sqlPassword = "Rhashdeak";
$sqlDatenbank = "coma1";

$ftpServer = "localhost";
$ftpUser = "ftp-php1";
$ftpPassword = "janMiUct";

/**
 * Diese Funktion sollte nach jedem include dieser Datei so schnell 
 * wie moeglich nach dem Include dieser Datei ausgefuert werden, 
 * um eventuellen Passworddiebstahl oder so zu erschweren
 *
 * Ist das wirklcih noetig? Und bringt es ueberhaupt was?
 * 
 */
//function ConfigClearVariables()
//{
//  unset($sqlPassword);
//  unset($ftpPassword);
//}

?>