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
 * Klasse PaperVariance
 *
 * @author Tom Scherezr <tos@informatik.uni-kiel.de>
 * @author Falk Starke <fast@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class PaperVariance extends Paper{

  var $fltVariance;

  function PaperVariance($intId, $fltVariance) {
    $this->Paper($intId);
    $this->fltVariance = $fltVariance;
  }

}

?>