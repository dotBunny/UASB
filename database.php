<?php

$db = $_GET["db"];

include("_inc.php");

DB::getDB()->connect($db);

$render = new Render("index");

$project = AServer::GetDatabaseProjectName($db);
$render->setPageTitle($project);
$render->setHeaderLine($project, "/database.php?db=" . $db);
$render->setMetaDescription("Viewing details for asset server database " . $project);

$render->addContent(new W_DatabaseUsers($db));
$render->addContent(new W_ScriptsTodo($db));
$render->addContent(new W_DatabaseUpdates($db));

$render->display();

?>