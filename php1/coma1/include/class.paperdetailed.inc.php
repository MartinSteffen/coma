<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

require_once('./class.papersimple.inc.php');

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

  function PaperDetailed($id, $title, $authorId, $status, $avgRating = 0.0, $coAuthorIds='', $coAuthors, $abstract, $formatId, $lastEdit, $filePath){
    $this->PaperSimple($id, $title, $authorId, $status, $avgRating);
    $this->intCoAuthorsIds = $coAuthorIds;
    $this->strCoAuthors = $coAuthors;
    $this->strAbstract = $abstract;
    $this->intFormatId = $formatId;
    $this->strLastEdit = $lastEdit;
    $this->strFilePath = $filePath;
  }

  // weg damit! (Tom)
  function PaperDetailedFromPaperSimple($paperSimple, $coAuthorIds='', $coAuthors, $abstract, $formatId, $lastEdit, $filePath){
    $this->PaperDetailed($paperSimple->intId, $paperSimple->strTitle, $paperSimple->intAuthorId, $paperSimple->intStatus, $paperSimple->fltAvgRating, $coAuthorsIds, $cpAuthors, $abstract, $formatId, $lastEdit, $filePath);
  }

} // end class PaperDetailed

?>