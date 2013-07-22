<?php


/**
* @desc Show info about someone
*/
class W_ScriptsTodo extends Widget
{
	protected $template = "widget_script_todo.tpl";
	
	function __construct($db)
	{
		parent::__construct();
		DB::getDB()->connect($db);
		
		// get all of our script files
		$query = "
			SELECT a.serial, a.asset, a.name, c.stream
			FROM assetversion a, variant v, assetcontents c
			WHERE c.assetversion = a.serial AND c.tag='asset' AND a.name like '%.js' AND a.serial = ((
				SELECT assetversion.serial
				FROM assetversion, variant, changeset, variantcontents, variantinheritance, changesetcontents
				WHERE assetversion.serial = changesetcontents.assetversion AND assetversion.asset = a.asset AND changesetcontents.changeset = changeset.serial AND variantcontents.changeset = changeset.serial AND variant.serial = variantcontents.variant AND variantinheritance.parent = variant.serial AND variantinheritance.child = v.serial AND (variant.dynamic OR variant.basetime >= changeset.commit_time)
				ORDER BY variantinheritance.depth, changeset.commit_time DESC, assetversion.serial DESC
				LIMIT 1
				))
			ORDER BY a.name;
		";
		$result = DB::getDB()->query($query);
		while($row = pg_fetch_object($result))
		{
			pg_query("begin");
   			$handle = pg_lo_open($row->stream, "r");
  			$data = pg_lo_read($handle, 50000);
   			pg_query("commit");
   			
   			$lines = split("\n", $data);
   			
   			// do we have a todo in this file?
   			$i = 1;
   			foreach($lines as $line)
   			{
   				if(stripos($line, "TODO"))
   					$todos[] = array("asset" => $row->asset, "name" => $row->name, "line" => $line, "number" => $i);
   					
   				$i++;
			}
		}
		
		$this->smarty->assign("todos", $todos);
		$this->smarty->assign("database", $db);
		$this->smarty->assign("projectname",AServer::GetDatabaseProjectName($db));
	}
}