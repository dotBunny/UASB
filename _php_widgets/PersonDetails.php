<?php

/**
* @desc Show info about someone
*/
class W_PersonDetails extends Widget
{
	protected $template = "widget_person_details.tpl";
	
	function __construct($db, $serial)
	{
		parent::__construct();
		
		// how many changesets have our name on them?
		$query = "SELECT COUNT(*) AS value FROM changeset WHERE creator=$serial";
		$this->smarty->assign("changesets", DB::getDB()->singleValue($query));
		
		// how many files in total?
		$query = "
			SELECT COUNT(*) AS value
			FROM assetversion, changesetcontents, changeset
			WHERE assetversion.serial = changesetcontents.assetversion
				AND changeset.serial = changesetcontents.changeset
				AND changeset.creator = $serial
		";
		$this->smarty->assign("assetversions", DB::getDB()->singleValue($query));
		
		// which scripts do we own?  that is, we have greater than 75% of updates
		$query = "
			SELECT DISTINCT asset
			FROM assetversion
			WHERE name like '%.js' or name like '%.cs'
		";
		
		// iterate each script
		$result = DB::getDB()->query($query);
		while($row = pg_fetch_object($result))
		{			
			$total = AServer::CountAssetEdits($row->asset, 0);
			$mine = AServer::CountAssetEdits($row->asset, $serial, $debug);
			
			$percent = $mine / $total;
		
			//print "$percent<br />";
			
			// save in an associative arra for easy sort
			if($percent > 0.50)
			{
				$sort[] = $percent;
				
				$owned[] =
					array(
						"serial" => $row->asset
						,"name" => AServer::AssetName($row->asset)
						,"percent" => sprintf("%0.1f", $percent * 100)
					);
			}
		}
		
		if(count($owned) > 0)
		{
			array_multisort($sort, SORT_DESC, $owned, SORT_DESC);
			
			$this->smarty->assign("scripts", $owned);
		}
		
		$this->smarty->assign("name", AServer::PersonIDtoName($serial));
		$this->smarty->assign("database", $db);
		$this->smarty->assign("projectname",AServer::GetDatabaseProjectName($db));
	}
}