<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

/**#@+ Konstanten fuer die Reviewpraeferenzen */
define('ATTITUDE_NONE',    0);
define('ATTITUDE_PREFER',  1);
define('ATTITUDE_DENY',    2);
define('ATTITUDE_EXCLUDE', 3);
/**#@-*/

/**
 * Klasse ReviewerAttitude
 *
 * Bildet Themen- und Paper-IDs auf Praeferenzwerte ab.
 *
 * @author Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class ReviewerAttitude {

  var $intReviewerId;
  var $intConferenceId;
  var $intPaperAttitudes;
  var $intTopicAttitudes;

  function ReviewerAttitude($intReviewerId, $intConferenceId) {
    $this->intReviewerId = $intReviewerId;
    $this->intConferenceId = $intConferenceId;
    $this->intPaperAttitudes = array();
    $this->intTopicAttitudes = array();
  }
  
  function getPaperAttitude ($intPaperId) {
    if (!isset($this->intPaperAttitudes[$intPaperId])) {
    	$this->intPaperAttitudes[$intPaperId] = ATTITUDE_NONE;
    }
    return $this->intPaperAttitudes[$intPaperId];
  }

  function getTopicAttitude ($intTopicId) {
    if (!isset($this->intTopicAttitudes[$intTopicId])) {
    	$this->intTopicAttitudes[$intTopicId] = ATTITUDE_NONE;
    }
    return $this->intTopicAttitudes[$intTopicId];
  }

  function setPaperAttitude ($intPaperId, $intAttitude) {
    if ($intAttitude == ATTITUDE_NONE || $intAttitude == ATTITUDE_PREFER ||
        $intAttitude == ATTITUDE_DENY || $intAttitude == ATTITUDE_EXCLUDE) {
      $this->intPaperAttitudes[$intPaperId] = $intAttitude;    
      return true;
    }
    return false;
  }

  function setTopicAttitude ($intTopicId, $intAttitude) {
    if ($intAttitude == ATTITUDE_NONE || $intAttitude == ATTITUDE_PREFER) {
      $this->intTopicAttitudes[$intTopicId] = $intAttitude;
      return true;
    }
    return false;
  }
} // end class ReviewerAttitude

?>