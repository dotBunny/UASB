<?php

$db = $_GET["db"];
$serial = $_GET["serial"];
include("_inc.php");
$project = AServer::GetDatabaseProjectName($db);

DB::getDB()->connect($db);

$render = new Render("index");

$render->setPageTitle($project);
$render->setHeaderLine($project, "/database.php?db=" . $db);
$render->setMetaDescription("Viewing details for asset server database " . $project);

$render->addContent(new W_AssetDetails($db, $serial));

$render->addContent(new W_DatabaseUpdates($db, 0, $serial));

$render->addContent(new W_ShowScript($db, $serial));

$render->display();

?>