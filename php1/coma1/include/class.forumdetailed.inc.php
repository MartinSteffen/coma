<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * Klasse ForumDetailed
 *
 * @author  Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class ForumDetailed extends Forum {
  
  var $objThreads;
  
  function ForumDetailed($intId, $strTitle, $intForumType = 0,
                 $intPaperId = false, $objThreads = false) {
    $this->intId = $intId;    
    $this->strTitle = $strTitle;    
    $this->intForumType = $intForumType;    
    $this->intPaperId = $intPaperId;
    $this->objThreads = $objThreads;
  }
  
  function getThreads() {      	
    return $this->objThreads;
  }

  function getThreadCount() {
    if ($this->objThreads) {
      return count($this->objThreads);
    }
    return 0;     
  }

  function getThread($intIndex) {
    if ($intIndex >= 0 & $intIndex < count($this->objThreads)) {
      return $this->objThreads[$intIndex];
    }
    return false;
  }
  
} // end class ForumDetailed

?>