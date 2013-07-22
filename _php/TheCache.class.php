<?php

// Memcache singleton object
class TheCache
{
	private static $m_objMem = NULL;
	public static function Get() {
		if (self::$m_objMem == NULL) {
			self::$m_objMem = new Memcache;
			@self::$m_objMem->connect("localhost", 11211) or $GLOBALS["USECACHE"] = false;
		}
		return self::$m_objMem;
	}
	
	public static function FlushAll()
	{
		self::Get()->flush();
	}
}

?>