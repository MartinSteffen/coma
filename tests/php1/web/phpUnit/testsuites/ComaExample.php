<?php

require_once 'PHPUnit.php';

// Coma must be installed in ../coma1/
define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
include('../coma1/include/header.inc.php');

$strEmail = 'sae@me.de';

class ComaExample extends PHPUnit_TestCase
{

  function testTrue() {
      $this->assertTrue(TRUE);
  }

  function testTrue() {
        $this->assertEquals('sae@me.de',"sae@me.de");
  }

  function test_myDBAccess() {
  	global $myDBAccess;
    $this->assertFalse($myDBAccess->failed());
  }

  function test_checkEmail() {
  	global $myDBAccess;
    $this->assertTrue($myDBAccess->checkEmail('sae@me.de'));
  }
}

?>
