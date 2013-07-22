<?php

/**
* @desc List all of the databases we're tracking
*/
class W_DatabaseUpdates extends Widget
{
	protected $template = "widget_database_updates.tpl";
	
	function __construct($db, $person = 0, $asset = 0, $serial = 0)
	{
		parent::__construct();
		
		DB::getDB()->connect($db);
		
		if($serial)
			$whereSerial = "AND c.serial = $serial";
		
		// get all updates
		$query = "
			SELECT p.username, c.serial, c.description, c.creator, extract(epoch from c.commit_time) as time
			FROM changeset c, person p
			WHERE c.creator = p.serial
				$whereSerial
			ORDER BY commit_time DESC
		";
		$result = DB::getDB()->query($query);
		while($row = pg_fetch_array($result))
		{	
			if($person && $person != $row["creator"])
				continue;
			
			$new = $row;
			$new["time"] = "<strong>" . date("m-d-Y", $new["time"]) . "</strong><br />" . date("h:i:s a", $new["time"]);
			
			// get the changeset contents
			$query = "
				SELECT a.name, a.serial, a.created_in, a.revision, a.asset
				FROM assetversion a, changesetcontents c
				WHERE a.serial = c.assetversion
					AND c.changeset = $new[serial]
			";
			
			$asset_contains = false;
			
			$assets = array();
			$result2 = DB::getDB()->query($query);
			while($row2 = pg_fetch_array($result2))
			{
				// was it a delete?
				if(preg_match("/DEL_/", $row2["name"]))
				{
					$row2["name"] = '<span class="delete">' . substr($row2["name"], 0, -39) . '</span>';
				}
				
				if($row2["asset"] == $asset)
					$asset_contains = true;
				
				$assets[] = $row2;
			}
			$new["assets"] = $assets;
			
			if($asset && !$asset_contains)
				continue;
			
			$updates[] = $new;
		}
		
		$this->smarty->assign("asset", $asset);
		$this->smarty->assign("updates", $updates);
		$this->smarty->assign("database", $db);
		$this->smarty->assign("projectname",AServer::GetDatabaseProjectName($db));
	}
}