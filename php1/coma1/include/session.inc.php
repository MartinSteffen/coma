<?php
/**
 * Sessionverwaltung
 *
 * Diese Datei einbinden um Coma1 Sessionmangment zu verwenden.
 *
 * @version $Id$
 * @package coma1
 * @subpackage Sessions
 *
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}


session_name('coma1');
session_cache_limiter('nocache');
session_start();

?>