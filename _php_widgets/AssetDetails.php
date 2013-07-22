<?php

/**
* @desc List all of the databases we're tracking
*/
class W_AssetDetails extends Widget
{
	protected $template = "widget_asset_details.tpl";
	
	function __construct($db, $serial)
	{
		parent::__construct();
		
		// when we we created (oldest row from assetversion)
		$query = "SELECT * FROM assetversion WHERE asset=$serial ORDER BY revision ASC";
		$row = DB::getDB()->singleQuery($query);
		$this->smarty->assign("created", AServer::FormatAssetVersionRow($row));

		// last update (newest row from assetversion)
		$query = "SELECT * FROM assetversion WHERE asset=$serial ORDER BY revision DESC";
		$row = DB::getDB()->singleQuery($query);
		$this->smarty->assign("lastupdate", AServer::FormatAssetVersionRow($row));
		
		// our history of filenames
		$query = "SELECT name FROM assetversion WHERE asset=$serial ORDER BY revision DESC";
		$result = DB::getDB()->query($query);
		while($row = pg_fetch_object($result))
			$names[$row->name] = $row->name;
		
		// bold the first one (hackish)
		$keys = array_keys($names);
		$names[$keys[0]] = "<strong>" . $names[$keys[0]] . "</strong>";
			
		$this->smarty->assign("names", join("<br />", $names));
		
		/*
		Who all has edited this?  We could do one mega-query, or we could be lazy and use PHP and step through it
		
		...laziness wins.
		*/
		
		// get a list of the assetversions that used this asset serial
		$query = "SELECT serial AS value FROM assetversion WHERE asset=$serial";
		$aversions = DB::getDB()->singleArray($query);
		
		// get a list of the changesets this was involved in
		$query = "SELECT changeset AS value FROM changesetcontents WHERE assetversion IN (" . join(',', $aversions) . ")";
		$changesets = DB::getDB()->singleArray($query);
				
		// get a list of all of the creators in those changesets
		$query = "SELECT DISTINCT creator AS value FROM changeset WHERE serial IN (" . join(',', $changesets) . ")";
		$creators = DB::getDB()->singleArray($query);
		
		// build up our list of editors
		foreach($creators as $creator)
		{
			$total = AServer::CountAssetEdits($serial, $creator);
			$allcreators[] = AServer::FormatPersonID($creator) . " ($total)";
			$allcreatorssort[] = $total;
		}
		
		array_multisort($allcreatorssort, SORT_DESC, $allcreators);
			
		$this->smarty->assign("creators", join('<br />', $allcreators));
		
		// show all revisions -- changeset and then the id
		
		
		$this->smarty->assign("serial", $serial);
		$this->smarty->assign("database", $db);
		$this->smarty->assign("projectname",AServer::GetDatabaseProjectName($db));
		
		$this->smarty->assign("project", $project);
	}
}