<?php

include("_inc.php");

$render = new Render("index");

$render->setPageTitle("Home");
$render->setHeaderLine("Welcome");
$render->setMetaDescription("Unity Asset Server Browser");

// and an overview on each
foreach(AServer::GetDatabases() as $db)
	$render->addContent(new W_DatabaseOverview($db));

$render->display();

?>