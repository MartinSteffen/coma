<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
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
  var $fltAvgRating; // false, falls kein Rating existiert
  var $objTopics;

function PaperSimple($intId, $strTitle, $intAuthorId, $strAuthor, $intStatus,
                     $fltAvgRating, $objTopics) {
    $this->Paper($intId);
    $this->strTitle = $strTitle;
    $this->intAuthorId = $intAuthorId;
    $this->strAuthor = $strAuthor;
    $this->intStatus = $intStatus;
    $this->fltAvgRating = $fltAvgRating;
    $this->objTopics = $objTopics;
  }

} // end class PaperSimple

?>