<?php

/**
* @desc Database connection class for use with the Flashbang website
*/
class DB {
	static private $m_objDB = NULL;

	/**
	* @desc Get the database object
	*/
	static function getDB()
	{
		if (self::$m_objDB == NULL)
		{
			// if no instance, create one and connect
			self::$m_objDB = new DB;
			self::$m_objDB->connect();
		}
	return self::$m_objDB;
	}

	private $connect;
	private $queryCount = 0;

	private $activeDB = "";

	/**
	* @desc Connect to the database
	*/
	public function connect($db = "postgres")
	{
		$this->activeDB = $db;
		$this->connect = pg_connect("host=" . PG_HOST . " port=" . PG_PORT . " dbname=$db user=" . PG_USER . " password=" . PG_PASSWORD) or Die("Unable to connect to dataabase.");
	}

	/**
	* @desc Run a query on our database with error trapping
	*/
	public function query($query)
	{
		if(Debug::isDebug())
			Debug::addQuery(nl2br($query));

		$result = pg_query($query) or die("Query failed " . pg_last_error() . nl2br($query) . "<pre>" . debug_print_backtrace() . "</pre>");

		$this->queryCount++;

		return $result;
	}

	/**
	* @desc Return a row (this expects that there's only a single row in the result set)
	*/
	public function singleQuery($query)
	{
		if(Debug::isDebug())
			Debug::addQuery(nl2br($query));

		$result = $this->query($query) or die("Query failed " . pg_last_error() . nl2br($query) . "<pre>" . debug_print_backtrace() . "</pre>");

		return @pg_fetch_object($result);
	}

	/**
	* @desc Get a single value out of a query
	*/
	public function singleValue($query)
	{
		$row = $this->singleQuery($query);
		return $row->value;
	}

	/**
	* @desc Return an array of values
	*/
	public function singleArray($query)
	{
		$result = $this->query($query);
		while($row = pg_fetch_object($result))
			$values[] = $row->value;

		return $values;
	}

	/**
	* @desc Return an array of objects for all rows
	*/
	public function objectArray($query)
	{
		$result = $this->query($query);
		while($row = pg_fetch_object($result))
			$values[] = $row;

		return $values;
	}

	/**
	* @desc How many queries have we done?
	*/
	public function getQueryCount()
	{
		return $this->queryCount;
	}

	/**
	* @desc Our currently active database
	*/
	public function activeDatabase()
	{
		return $this->activeDB;
	}
}