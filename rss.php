<?php

// Requirements
require_once('_php/config.php');
require_once('_php/Debug.class.php');
require_once('_php/DB.class.php');
require_once('_php/AServer.class.php');

if ( (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']))) {
	$test = @pg_connect("host=" . PG_HOST . " port=" . PG_PORT . " dbname=postgres user=" . addslashes($_SERVER['PHP_AUTH_USER']) . " password=" . addslashes($_SERVER['PHP_AUTH_PW']));
	if ( $test ) {

	} else {
		header("WWW-Authenticate: Basic realm=\"Unity Asset Server Browser\"");
		header("HTTP/1.0 401 Unauthorized");
		die("Authorization Required");
	}
}


// Grab URL Settings
$username = addslashes($_SERVER['PHP_AUTH_USER']);
$password = addslashes($_SERVER['PHP_AUTH_PW']);
$db = addslashes($_GET['db']);
$item_count = addslashes($_GET['i']);
if ( empty ($item_count)) { $item_count = 15; }

// Check Permission
$database = new DB;
$database->connect($db);
$role = $database->singleValue("SELECT role.name AS value FROM role WHERE role.name = 'just_" . $username . "'");
$project = $database->singleValue("SELECT projectname  AS value FROM all_databases__view WHERE databasename ='" . $db . "'");


if ( !$role ) {
	die("Access Denied");
}


// Get Updates
$query =
"SELECT 	p.username,
			c.serial,
			c.description,
			c.creator,
			extract(epoch from c.commit_time) as time
FROM 		changeset c,
			person p
WHERE 		c.creator = p.serial
ORDER BY 	commit_time DESC
LIMIT 		" . $item_count;

$result = $database->query($query);

while($row = pg_fetch_array($result)) {
	if($person && $person != $row["creator"]) {
		continue;
	}

	$new = $row;
	$new["time"] = date("D, d M Y H:i:s T", $new["time"]);

// get the changeset contents
$query =
"SELECT 	a.name,
			a.serial,
			a.created_in,
			a.revision,
			a.asset
FROM 		assetversion a,
			changesetcontents c
WHERE		a.serial = c.assetversion AND
			c.changeset = " . $new[serial];

	$asset_contains = false;

	$assets = array();
	$result2 = $database->query($query);
	while($row2 = pg_fetch_array($result2))
	{
		// was it a delete?
		if(preg_match("/DEL_/", $row2["name"]))
		{
			$row2["name"] = substr($row2["name"], 0, -39);
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

$output =
"<?xml version=\"1.0\"?>
	<rss version=\"2.0\">
		<channel>
			<title>" . $project . " Update Feed</title>
			<link>" . HTTPROOT . "?db=" . $db . "</link>
            <pubDate>" . date("D, d M Y H:i:s T") . "</pubDate>
            <lastBuildDate>" . $updates[0]['time'] . "</lastBuildDate>";


foreach ($updates as $update) {
	$output .= "<item><pubDate>" . htmlentities($update['time']) . "</pubDate><title>" . htmlentities($update['description']) . "</title><author>" . htmlentities($update['username']) . "</author><link>" . htmlentities(HTTPROOT . "changeset.php?db=" . $db . "&serial=" . $update['serial']) . "</link><description>";
	foreach ($update['assets'] as $asset) {
		$output .= $asset['name'] . ", ";
	}
	$output = substr($output, 0, strlen($output) - 2);
	$output .= "</description></item>";
}


$output .= "</channel></rss>";
header("Content-Type: application/rss+xml");
echo $output;
?>