<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

if (!defined('INCPATH')) {
  /** @ignore */
  define('INCPATH', dirname(__FILE__).'/');
}
require_once(INCPATH.'class.paper.inc.php');

/**
 * Klasse PaperSimple
 *
 * @author Jan Waller <jwa@informatik.uni-kiel.de>
 * @author Falk Starke <fast@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class PaperSimple extends Paper {

  var $strTitle;
  var $intAuthorId;
  var $strAuthor; // hinzugefuegt (Tom) (i.d.R. first_name &nbsp; last_name)
  var $intStatus;
  var $strLastEdit;
  var $fltAvgRating; // false, falls kein Rating/Review existiert
  var $strFilePath;
  var $objTopics;

  function PaperSimple($intId, $strTitle, $intAuthorId, $strAuthor, $intStatus,
                       $strLastEdit, $fltAvgRating, $strFilePath, $objTopics) {
    $this->Paper($intId);
    $this->strTitle = $strTitle;
    $this->intAuthorId = $intAuthorId;
    $this->strAuthor = $strAuthor;
    $this->intStatus = $intStatus;
    $this->strLastEdit = $strLastEdit;
    $this->fltAvgRating = $fltAvgRating;
    $this->strFilePath = $strFilePath;
    $this->objTopics = $objTopics;
  }

  /**
   * Testes, ob das Paper das Topic $intTopicId besitzt.
   *
   * @param int $intTopicId ID des zu testenden Topics
   * @return bool true gdw. das Paper das zu testende Topic besitzt.
   * @access public
   * @author Sandro (21.01.05)
   */
  function hasTopic($intTopicId) {
    for ($i = 0; $i < count($this->objTopics); $i++) {
      if ($this->objTopics[$i]->intId == $intTopicId) {
        return true;
      }
    }
    return false;
  }

} // end class PaperSimple

?>