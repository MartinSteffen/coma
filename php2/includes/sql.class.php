<?php

// dummy version, to be replaced by Torben's one. mje

class SQL {
	
	var $conn=false;
	var $last_insert_id=0;

	function connect($host="",$user="",$pass="",$name="") 
	{
		if (!$this->conn) 
		{
			$this->conn=mysql_connect(
				( $host ? $host : $GLOBALS['dbhost']),
				( $user?$user:$GLOBALS['dbuser']),
				( $pass?$pass:$GLOBALS['dbpass'])
				);
			mysql_select_db(($name?$name:$GLOBALS['dbname']), $this->conn );
		}
	}

	function query($queryStr) {

		if (!$this->conn) {
			$this->connect();
		}
		$resultSet=mysql_query($queryStr, $this->conn);
		if (mysql_error()) die(mysql_error());
		$resultArr=array();
		while ($row=mysql_fetch_array($resultSet)) {
			$resultArr[]=$row;
		}
		return $resultArr;
	}
	function queryAssoc($queryStr) {

		if (!$this->conn) {
			$this->connect();
		}
		$resultSet=mysql_query($queryStr, $this->conn);
		$resultArr=array();
		while ($row=mysql_fetch_assoc($resultSet)) {
			$resultArr[]=$row;
		}
		return $resultArr;
	}
	function queryIndex($queryStr) {

		if (!$this->conn) {
			$this->connect();
		}
		$resultSet=mysql_query($queryStr, $this->conn);
		$resultArr=array();
		while ($row=mysql_fetch_row($resultSet)) {
			$resultArr[]=$row;
		}
		return $resultArr;
	}

	function insert($queryStr) {

		if (!$this->conn) {
			$this->connect();
		}
		$isok=mysql_query($queryStr, $this->conn);

		if (!$isok) {
			return array("text"=>mysql_error($this->conn));
		} else {	
			$this->last_insert_id=mysql_insert_id($this->conn);
			return true;
		}
	}

	function error() {
		return mysql_error($this->conn);
	}

	function insert_id() {
		return $this->last_insert_id;
	}
}