<?php

if ( file_exists('_php/config.php') ) {
	require_once('_php/config.php');
} else {
	$config_template = file_get_contents('_tpl/config.tpl');
	file_put_contents('_php/config.php', $config_template);
	if ( file_exists('_php/config.php') ) {
		die('Configuration file created, please edit it on your web server.');
	} else {
		die('Unable to create config.php, please make sure directory is writable.');
	}
}

//create array to temporarily grab variables
$input_arr = array();
//grabs the $_POST variables and adds slashes
foreach ($_POST as $key => $input_arr) {
    $_POST[$key] = addslashes($input_arr);
}

session_start();


if ( (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']))) {
	$test = @pg_connect("host=" . PG_HOST . " port=" . PG_PORT . " dbname=postgres user=" . $_SERVER['PHP_AUTH_USER'] . " password=" . $_SERVER['PHP_AUTH_PW']);
	if ( $test ) {
		$_SESSION['databases'] = AServer::GetDatabases();
		$_SESSION['projects'] = AServer::GetDatabaseProjectNames();
	} else {
		header("WWW-Authenticate: Basic realm=\"Unity Asset Server Browser\"");
		header("HTTP/1.0 401 Unauthorized");
		die("Authorization Required");
	}
}


// autoload our classes (whew!)s
function __autoload($class) {
	if(substr($class, 0, 2) == "W_")
		require_once(ROOT . "_php_widgets/" . substr($class, 2) . ".php");
	else
		require_once(ROOT . "_php/" . $class . ".class.php");
}
?>
