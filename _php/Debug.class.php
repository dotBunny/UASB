<?php

class Debug
{
	private static $footer;
	private static $queries;
	
	/**
	* @desc Should we be in debug mode?
	*/
	public static function isDebug()
	{
		//return false;
		
		if(getenv('REMOTE_ADDR') == "192.168.2.40")
			return true;
		else
			return false;
	}
	
	/**
	* @desc Add a note to our footer
	*/
	public static function addNote($note)
	{
		self::$footer[] = $note;
	}
	
	/**
	* @desc Add a note to our footer
	*/
	public static function addQuery($query)
	{
		self::$queries[] = $query;
	}
	
	
	public static function footer()
	{
		$footer2[] = "Queries: " . DB::getDB()->getQueryCount();
		$footer2[] = "Cache: " . ($GLOBALS["USECACHE"] ? "ON" : "OFF");

		return @implode(" | ", self::$footer) . "<br />"
		. @implode(" | ", $footer2) . "<br />"
		. @implode("\n", self::$queries);
	}
	
	public static function TextLog($text)
	{
		$fp = fopen("/www/log.txt", "a");
		fwrite($fp, date("r") . "\t" . $_SERVER["SCRIPT_NAME"] . "\t" . $text . "\n");
		fclose($fp);
	}
}