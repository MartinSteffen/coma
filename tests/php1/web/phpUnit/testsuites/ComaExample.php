<?php

require_once 'PHPUnit.php';

// Coma must be installed in ../coma1/
define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
require_once '~wprguest1/coma1/include/header.inc.php';

class ComaExample extends PHPUnit_TestCase
{

  function testTrue() {
      $this->assertTrue(TRUE);
  }

  function testMakePassword() {
    $pass = MD5(strtolower("test"));
    $this->assertEquals($pass, makePassword("test"));
  }
}

?>
