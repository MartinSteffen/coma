<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

require_once('class.paper.inc.php');

/**
 * Klasse PaperSimple
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
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
  var $intStatus;
  var $fltAvgRating;
  
  function PaperSimple($intId, $strTitle, $intAuthorId, $intStatus, $fltAvgRating = 0.0){
    $this->Paper($intId);
    $this->strTitle = $strTitle;
    $this->intAuthorId = $intAuthorId;
    $this->intStatus = $intStatus;
    $this->fltAvgRating = $fltAvgRating;
  }

  // weg damit! (Tom)  
  function PaperSimpleFromPaper($paper, $title, $authorId, $status, $avgRating = 0.0){
    $this->PaperSimple($paper->intId, $title, $authorId, $status, $avgRating);
  }

} // end class PaperSimple

?>