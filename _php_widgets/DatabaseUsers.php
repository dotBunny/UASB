<?php

/**
* @desc List all of the databases we're tracking
*/
class W_DatabaseUsers extends Widget
{
	protected $template = "widget_database_users.tpl";
	
	function __construct($db)
	{
		parent::__construct();

		// how many users?  lazy way of counting
		$query = "SELECT DISTINCT creator FROM changeset";
		$result = DB::getDB()->query($query);
		while($row = pg_fetch_object($result))
		{
			if($row->creator != 5000)
				$users[] = array("serial" => $row->creator, "name" => AServer::PersonIDtoName($row->creator));
		}
		
		$this->smarty->assign("users", $users);
			
		$this->smarty->assign("database", $db);
		$this->smarty->assign("projectname",AServer::GetDatabaseProjectName($db));
	}
}