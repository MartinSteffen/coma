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
require_once(INCPATH.'class.papersimple.inc.php');

/**
 * Klasse PaperDetailed
 *
 * @author Jan Waller <jwa@informatik.uni-kiel.de>
 * @author Falk Starke <fast@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class PaperDetailed extends PaperSimple {

  var $intCoAuthorIds;
  var $strCoAuthors;
  var $strAbstract;
  var $strMimeType;
  var $strLastEdit;
  var $strFilePath;

  function PaperDetailed($intId, $strTitle, $intAuthorId, $strAuthor, $intStatus,
                         $fltAvgRating, $intCoAuthorIds, $strCoAuthors, $strAbstract,
                         $strMimeType, $strLastEdit, $strFilePath, $objTopics) {
    $this->PaperSimple($intId, $strTitle, $intAuthorId, $strAuthor, $intStatus,
                       $fltAvgRating, $objTopics);
    $this->intCoAuthorIds = $intCoAuthorIds;
    $this->strCoAuthors = $strCoAuthors;
    $this->strAbstract = $strAbstract;
    $this->strMimeType = $strMimeType;
    $this->strLastEdit = $strLastEdit;
    $this->strFilePath = $strFilePath;
  }

} // end class PaperDetailed

?>