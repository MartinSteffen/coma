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
 * Klasse Message
 *
 * @author  Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class Message {

  var $intId;
  var $intSender;
  var $strSendTime;
  var $strSubject;
  var $strText;
  var $objNext;

  function Message($intId, $intSender, $strSendTime, $strSubject, $strText,
                   $objNext = false) {
    $this->intId = $intId;
    $this->intSender = $intSender;
    $this->strSendTime = $strSendTime;
    $this->strSubject = $strSubject;
    $this->strText = $strText;
    $this->objNext = $objNext;
  }

  function getNextMessages() {
    return $this->objNext;
  }

  function getNextMessageCount() {
    if ($this->objNext) {
      return count($this->objNext);
    }
    return 0;
  }

  function getNextMessage($intIndex) {
    if ($intIndex >= 0 & $intIndex < count($this->objNext)) {
      return $this->objNext[$intIndex];
    }
    return false;
  }

} // end class Message

?>