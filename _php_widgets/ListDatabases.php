<?php

/**
* @desc List all of the databases we're tracking
*/
class W_ListDatabases extends Widget
{
	protected $template = "widget_list_databases.tpl";
	
	function __construct()
	{
		parent::__construct();
			
		$this->smarty->assign("databases", AServer::GetDatabases());
		$this->smarty->assign("projectnames",AServer::GetDatabaseProjectNames());
	}
}