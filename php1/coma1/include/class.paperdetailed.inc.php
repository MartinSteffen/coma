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
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @author Falk Starke <fast@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class PaperDetailed extends PaperSimple {

  var $intCoAuthorsIds;
  var $strCpAuthors;
  var $strAbstract;
  var $intFormatId;
  var $strLastEdit;
  var $strFilePath;
  
  function PaperDetailed($id, $title, $authorId, $status, $avgRating = 0.0, $coAuthorsIds, $cpAuthors, $abstract, $formatId, $lastEdit, $filePath){
    $this->PaperSimple($id, $title, $authorId, $status, $avgRating);
    $this->intCoAuthorsIds = $coAuthorsIds;
    $this->strCpAuthors = $cpAuthors;
    $this->strAbstract = $abstract;
    $this->intFormatId = $formatId;
    $this->strLastEdit = $lastEdit;
    $this->strFilePath = $filePath;
  }

  function PaperDetailedFromPaperSimple($paperSimple, $coAuthorsIds, $cpAuthors, $abstract, $formatId, $lastEdit, $filePath){
    $this->PaperSimple($paperSimple->intId, $paperSimple->strTitle, $paperSimple->intAuthorId, $paperSimple->intStatus, $paperSimple->fltAvgRating);
    $this->intCoAuthorsIds = $coAuthorsIds;
    $this->strCpAuthors = $cpAuthors;
    $this->strAbstract = $abstract;
    $this->intFormatId = $formatId;
    $this->strLastEdit = $lastEdit;
    $this->strFilePath = $filePath;
  }

} // end class PaperDetailed

?>