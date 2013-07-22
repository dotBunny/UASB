<?php
class Helpers
{
	/**
	* @desc Set a cookie (with our prefix, year expiration)
	*/
	public static function SetCookie($name, $value)
	{
		setcookie("bunny_" . $name, $value, time() + 60*60*24*365, "/", ".dotbunny.com");
	}
	
	/**
	* @desc Get a cookie (with our prefix)
	*/
	public static function GetCookie($name) {
		return $_COOKIE["bunny_" . $name];
	}
	
	/**
	* @desc Get the depths for our categories
	*/
	public static function GetDepths() {
		$query = "
			SELECT node.id, (COUNT(parent.name) - 1) AS depth
			FROM an_categories AS node,
			an_categories AS parent
			WHERE node.lft BETWEEN parent.lft AND parent.rgt
			GROUP BY node.id
			ORDER BY node.lft;
			";
			
		$result = DB::getDB()->query($query);
		while($row = mysql_fetch_object($result))
			$depth[$row->id] = $row->depth;
		
		return $depth;
	}
	
	/**
	* @desc Get the full name of something (including its parents)
	*/
	public static function GetFullCategoryName($catid)
	{
		if($catid == 1)
			return "All";
		
		$query = "
			SELECT parent.name
			FROM an_categories AS node, an_categories AS parent
			WHERE node.lft BETWEEN parent.lft AND parent.rgt
			AND node.id = $catid
			AND parent.id > 1
			ORDER BY parent.lft;
		";
		
		$name = "";
		
		$result = DB::getDB()->query($query);
		while($row = mysql_fetch_object($result))
			$name .= stripslashes($row->name) . ", ";
		
		return substr($name, 0, -2);
	}
	
	/**
	* @desc Get the first Monday on or before this date
	*/
	public static function MondayBefore($time = -1)
	{
		if($time == -1)
			$time = time();
		
		$start = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time), 0);
		
		while(date("w", $start) != 1)
			$start -= 60 * 60 * 24;
			
		return $start;
	}
	
	/**
	* @desc Get the first Monday on or after this date
	*/
	public static function MondayAfter($time = -1)
	{
		if($time == -1)
			$time = time();
		
		$start = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time), 0);
		
		while(date("w", $start) != 1)
			$start += 60 * 60 * 24;
			
		return $start;
	}	
	
	/**
	* @desc Which month are we in?
	*/
	public static function WeekOfMonth($time)
	{
		// start on the 1st, then find the next monday, then check all mondays
		$start = mktime(0, 0, 0, date("m", $time), 1, date("Y", $time), 0);
		$start = self::MondayAfter($start);
		
		for($i = 1; $i < 6; $i++)
		{
			if($start == $time)
				return $i;
				
			$start += 60 * 60 * 24 * 7;
		}
	}
}
?>