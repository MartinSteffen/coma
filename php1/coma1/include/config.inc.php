<?php
/**
 * Configuration of the Database Server
 *
 * @version $Id$
 * @package coma1
 * @subpackage Configuration
 */
/***/
if ( !defined('IN_COMA1') )
{
  die('Hacking attempt');
}

// Change these Values
$sqlServer = 'localhost';
$sqlUser = 'coma1';
$sqlPassword = 'Rhashdeak';
$sqlDatabase = 'coma1';

// This is the Debugging Version :)
if (!defined('DEBUG')) {
  define('DEBUG', true);
}

?>