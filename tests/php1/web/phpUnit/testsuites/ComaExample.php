<?php

require_once 'PHPUnit.php';

// Coma must be installed in ../coma1/
define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
require_once '../coma1/include/header.inc.php';

$strEmail = 'sae@me.de';

class ComaExample extends PHPUnit_TestCase
{

  function testTrue() {
      $this->assertTrue(TRUE);
  }

  function test() {
    $this->assertTrue(checkEmail($strEmail));
  }
}

?>
