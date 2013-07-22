<?php

/**
* @desc List all of the databases we're tracking
*/
class W_DatabaseOverview extends Widget
{
	protected $template = "widget_database_overview.tpl";
	
	function __construct($db)
	{
		parent::__construct();
		
		$maxDesc = 60;
		DB::getDB()->connect($db);
		
		// calculate our total versions
		$query = "SELECT COUNT(*) AS value FROM changeset";
		$this->smarty->assign("versions", DB::getDB()->singleValue($query) - 1);
		
		// how many users?  lazy way of counting
		$query = "SELECT DISTINCT creator FROM changeset";
		$result = DB::getDB()->query($query);
		$this->smarty->assign("users", pg_num_rows($result) - 1);
		
		// calculate our total unique assets
		$query = "SELECT COUNT(*) AS value FROM asset";
		$this->smarty->assign("unique", DB::getDB()->singleValue($query) - 1);
		
		// calculate asset versions
		$query = "SELECT COUNT(*) AS value FROM assetversion";
		$this->smarty->assign("assetversions", DB::getDB()->singleValue($query) - 1);
		
		// get the last couple updates
		$query = "
			SELECT p.username, c.serial, c.description, c.creator, extract(epoch from c.commit_time) as time
			FROM changeset c, person p
			WHERE c.creator = p.serial
			ORDER BY commit_time DESC
			LIMIT 20
		";
		$result = DB::getDB()->query($query);
		
		// hardcode the first row (a bit sloppy!)
		$row = pg_fetch_object($result);
		$this->smarty->assign("time", date("m-d-Y H:i:s", $row->time));
		$this->smarty->assign("creator", $row->creator);
		$this->smarty->assign("name", $row->username);
		$this->smarty->assign("serial", $row->serial);
		
		$desc = $row->description;
		if(strlen($desc) > $maxDesc)
			$desc = substr($desc, 0, $maxDesc) . "...";
			
		$this->smarty->assign("description", $desc);
		
		// assign the other rows
		$more = array();
		while($row = pg_fetch_object($result))
		{
			$new["time"] = date("m-d-Y H:i:s", $row->time);
			$new["creator"] = $row->creator;
			$new["name"] = $row->username;
			$new["serial"] = $row->serial;
			
			$desc = $row->description;
			if(strlen($desc) > $maxDesc)
				$desc = substr($desc, 0, $maxDesc) . "...";
				
			$new["description"] = $desc;
			
			$more[] = $new;
		}
		$this->smarty->assign("more", $more);
		
		$this->smarty->assign("database", $db);

		$this->smarty->assign("projectname",AServer::GetDatabaseProjectName($db));
		
		// hide the div on page load via jquery
		AServer::$render->addJQuery("\$('#more_$db').hide();");
		
		// and show on click
		AServer::$render->addJQuery("\$('a#show_$db').click(function() {
		\$('#more_$db').toggle('fast');
		return false;
		});");

	}
}