<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

/**#@+ Konstanten fuer die Forentypen */
define('FORUM_PUBLIC',  1);  // konferenzinterne Foren fuer alle Teilnehmer
define('FORUM_PRIVATE', 2);  // konferenzinterne Foren fuer Kommiteemitglieder
define('FORUM_PAPER',   3);  // konferenzinterne Foren fuer Paperreviewer
define('FORUM_GLOBAL',  4);  // globale konferenzuebergreifende Foren
/**#@-*/

/**
 * Klasse Forum
 *
 * @author  Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class Forum {

  var $intId;
  var $strTitle;
  var $intConferenceId;
  var $intForumType;
  var $intPaperId;

  function Forum($intId, $strTitle, $intConferenceId,
                 $intForumType, $intPaperId=false) {
    $this->intId = $intId;
    $this->strTitle = $strTitle;
    $this->intConferenceId = $intConferenceId;
    $this->intForumType = $intForumType;
    $this->intPaperId = $intPaperId;
  }

} // end class Forum

?>