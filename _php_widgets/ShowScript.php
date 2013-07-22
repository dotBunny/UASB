<?php

include_once(ROOT . "_php/geshi.php");

/**
* @desc Show info about someone
*/
class W_ShowScript extends Widget
{
	protected $template = "widget_show_script.tpl";
	
	function __construct($db, $serial)
	{
		parent::__construct();
		DB::getDB()->connect($db);
		
		// if our name isn't a script, don't show
		$name = AServer::AssetName($serial);
		
		// which coloring?
   		if(strstr($name, ".js"))
   			$type = "javascript";
   		if(strstr($name, ".cs"))
   			$type = "csharp";
   		if(strstr($name, ".shader"))
   			$type = "csharp";
		
		if(empty($type))
		{
			$this->template = "blank.tpl";
			return;
		}
		
		// what's our large object id?
		$assetversion = AServer::AssetLatest($serial);
		$query = "SELECT stream AS value FROM assetcontents WHERE assetversion=$assetversion AND tag='asset'";
		$oid = DB::getDB()->singleValue($query);
				
		// read it
		pg_query("begin");
   		$handle = pg_lo_open($oid, "r");
  		$data = pg_lo_read($handle, 50000);
   		pg_query("commit");
   		
   		$geshi =& new GeSHi($data, $type);
   		$geshi->enable_classes();
   		$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
   		$geshi->set_header_type(GESHI_HEADER_DIV);
   		$geshi->set_tab_width(4);
   		
   		// add the stylesheet to our header
   		$style = '
   		<style type="text/css">
		<!--'
		. $geshi->get_stylesheet()
		. '
		-->
		</style>';
		AServer::$render->addHead($style);
   		
   		$this->smarty->assign("data", $geshi->parse_code());
		
		$this->smarty->assign("database", $db);
		$this->smarty->assign("projectname",AServer::GetDatabaseProjectName($db));
	}
}