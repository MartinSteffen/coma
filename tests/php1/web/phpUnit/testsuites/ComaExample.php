<?php

require_once 'PHPUnit.php';

// Coma must be installed in ../php2/
define('NEED_NO_LOGIN', true);
include('../../coma1/includes/header.inc.php');

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
