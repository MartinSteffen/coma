<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

/**
 * Klasse Topic
 *
 * @author  Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @author  Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class Topic {

  var $intId;
  var $intConferenceId;
  var $strName;

  function Topic($intId, $intConferenceId, $strName) {
    $this->intId = $intId;
    $this->intConferenceId = $intConferenceId;
    $this->strName = $strName;
  }

} // end class Topic

?>