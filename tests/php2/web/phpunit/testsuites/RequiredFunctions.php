<?php

require_once 'PHPUnit.php';

class RequiredFunctions extends PHPUnit_TestCase
{

  // Constructor
  function RequiredFunctions($name) {
    $this->PHPUnit_TestCase($name);
  }

  function setUp() {
  }

  function tearDown() {
  }

  // ftp functions
  function testFtp() {
    $this->assertTrue(function_exists('ftp_connect'));
    $this->assertTrue(function_exists('ftp_login'));
    $this->assertTrue(function_exists('ftp_chdir'));
  }
  
  // mysql functions
  function testMySql() {
    $this->assertTrue(function_exists('mysql_connect'));
    $this->assertTrue(function_exists('mysql_select_db'));
    $this->assertTrue(function_exists('mysql_close'));
    $this->assertTrue(function_exists('mysql_query'));
  }

}