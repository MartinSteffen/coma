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
  define('INCPATH', dirname(__FILE__).'/');
}
require_once(INCPATH.'class.paper.inc.php');

/**
 * Klasse PaperAlgorithmic
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class PaperAlgorithmic extends Paper {

} // end class PaperAlgorithmic

?>