<?php

require_once 'PHPUnit.php';

// Coma must be installed in ../coma1/
define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
include('../coma1/include/header.inc.php');

class ComaExample extends PHPUnit_TestCase
{
global $myDBAccess;
$strEmail = "sae@me.de";

  function testTrue() {
      $this->assertTrue(TRUE);
  }

  function test_myDBAccess() {
      $this->assertFalse($myDBAccess->failed());
  }

  function test_checkEmail() {
    $this->assertTrue($myDBAccess->checkEmail($strEmail));
  }
}

?>
