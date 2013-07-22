<?php

/**
* @desc This servers as our global application class
*/
class AServer
{
	static $render;
	static function GetDatabasesByUsername($username) {

		if ( !session_is_registered("databases") || empty($_SESSION['databases']) ) {

			$query = "SELECT * FROM all_databases__view ORDER BY databasename";
			$list = DB::getDB()->objectArray($query);

			foreach($list as $entry) {

				$test = new DB;
				$test->connect($entry->databasename);
				$db_query = "SELECT role.name AS value FROM role WHERE role.name = 'just_" . addslashes($username) . "'";
				$test = DB::getDB()->singleValue($db_query);
				if ( $test ) {
					$databases[] = $entry->databasename;
				}
			}
			$_SESSION['databases'] = $databases;

			if ( !empty($_SESSION['uasb_username']) && count($databases) == 0 ) {
				session_destroy();
				die("User not authorized for any databases.");
			}
		}


		//Session it to cache
		return $_SESSION['databases'];
	}

	/**
	* @desc Get all of the databases
	*/
	static function GetDatabases()
	{
		return AServer::GetDatabasesByUsername($_SERVER['PHP_AUTH_USER']);
	}

	static function GetDatabaseProjectName($db) {
		if ( in_array($db,$_SESSION['databases']) ) {
			$query = "SELECT projectname  AS value FROM all_databases__view WHERE databasename ='$db'";
			return DB::getDB()->singleValue($query);
		} else {
			header("Location:" . HTTPROOT . "login.php");
			die();
		}
	}
	static function GetDatabaseProjectNames() {
		if ( !session_is_registered("projects") ) {
				if ( empty($_SESSION['databases'])) {
					return null;
				}
				foreach ($_SESSION['databases'] as $db) {
					$query = "SELECT projectname AS value FROM all_databases__view WHERE databasename = '$db'";
					$projects[] = DB::getDB()->singleValue($query);
				}
			$_SESSION['projects'] = $projects;
		}
		return $_SESSION['projects'];
	}

	/**
	* @desc Get the name for an ID (when we're too lazy to do joins)
	*/
	static function PersonIDtoName($id)
	{
		$query = "SELECT username AS value FROM person WHERE serial=$id";
		return DB::getDB()->singleValue($query);
	}

	/**
	* @desc Format an asset version row like:
	*
	* m-d-Y, Name in Version Number
	*/
	static function FormatAssetVersionRow($row)
	{
		$db = DB::getDB()->activeDatabase();

		// find our changeset id
		$query = "SELECT changeset AS value FROM changesetcontents WHERE assetversion=$row->serial";
		$cid = DB::getDB()->singleValue($query);

		// get the time and user for this changeset
		$query = "SELECT extract(epoch from commit_time) as time, creator FROM changeset WHERE serial=$cid";
		$row2 = DB::getDB()->singleQuery($query);

		$s = date("m-d-Y H:i:s", $row2->time) . " by "
			. "<a href=\"person.php?db=$db&serial=$row2->creator\">" . self::PersonIDtoName($row2->creator) . "</a> in "
			. "<a href=\"changeset.php?db=$db&serial=$cid\">" . $cid . "</a>";

		return $s;
	}

	/**
	* @desc Return a nice link
	*/
	static function FormatPersonID($id)
	{
		$db = DB::getDB()->activeDatabase();

		return "<a href=\"person.php?db=$db&serial=$id\">" . self::PersonIDtoName($id) . "</a>";
	}

	/**
	* @desc Count the number of times a person has edited an asset
	*/
	static function CountAssetEdits($asset, $person = 0, $debug = false)
	{
		if($person)
			$personSQL = "AND changeset.creator = $person";

		$query = "
		SELECT COUNT(*) AS value
		FROM changeset, changesetcontents, assetversion
		WHERE changeset.serial = changesetcontents.changeset
			AND changesetcontents.assetversion = assetversion.serial
			AND assetversion.asset = $asset
			$personSQL
		";

		if($debug)
			die(nl2br($query));

		return DB::getDB()->singleValue($query);
	}

	/**
	* @desc What's the most recent name for an asset serial?
	*/
	static function AssetName($serial)
	{
		$query = "SELECT name AS value FROM assetversion WHERE asset = $serial ORDER BY revision DESC";
		return DB::getDB()->singleValue($query);
	}

	/**
	* @desc What's our latest assetversion for a particular asset?
	*/
	static function AssetLatest($serial)
	{
		$query = "SELECT serial AS value FROM assetversion WHERE asset = $serial ORDER BY revision DESC";
		return DB::getDB()->singleValue($query);
	}
}