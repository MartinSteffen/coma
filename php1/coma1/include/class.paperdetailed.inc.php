<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

require_once('class.papersimple.inc.php');

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
  var $intFormatId;
  var $strLastEdit;
  var $strFilePath;

  function PaperDetailed($intId, $strTitle, $intAuthorId, $intStatus,
                         $fltAvgRating = 0.0, $intCoAuthorIds = '', $strCoAuthors,
                         $strAbstract, $intFormatId, $strLastEdit, $strFilePath) {
    $this->PaperSimple($intId, $strTitle, $intAuthorId, $intStatus, $fltAvgRating);
    $this->intCoAuthorsIds = $intCoAuthorIds;
    $this->strCoAuthors = $strCoAuthors;
    $this->strAbstract = $strAbstract;
    $this->intFormatId = $intFormatId;
    $this->strLastEdit = $strLastEdit;
    $this->strFilePath = $strFilePath;
  }

  // weg damit! (Tom)
  function PaperDetailedFromPaperSimple($paperSimple, $coAuthorIds='', $coAuthors, $abstract, $formatId, $lastEdit, $filePath){
    $this->PaperDetailed($paperSimple->intId, $paperSimple->strTitle, $paperSimple->intAuthorId, $paperSimple->intStatus, $paperSimple->fltAvgRating, $coAuthorsIds, $cpAuthors, $abstract, $formatId, $lastEdit, $filePath);
  }

} // end class PaperDetailed

?>