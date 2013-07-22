<?php

$db = $_GET["db"];
$serial = $_GET["serial"];

include("_inc.php");
$project = AServer::GetDatabaseProjectName($db)

DB::getDB()->connect($db);

$render = new Render("index");

$render->setPageTitle($project);
$render->setHeaderLine($project, "/database.php?db=" . $db);
$render->setMetaDescription("Viewing user $serial on " . $project);

$render->addContent(new W_PersonDetails($db, $serial));
$render->addContent(new W_DatabaseUpdates($db, $serial, 0));

$render->display();

?>