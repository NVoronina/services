<?php
session_start();
include_once "config.php";
// include_once "function.php";
// include_once "model/db.php";
include_once "route.php";
function __autoload($class) {
	$class = str_replace("_", "/", $class . ".php");
	if (file_exists($class)) {
		include_once $class;
	}
}

$obj = Route::start($_SERVER["REQUEST_URI"]);
