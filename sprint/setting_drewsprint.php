<?php
/* Error Handling */
error_reporting(E_ALL);
ini_set("error_log", "../trello-api.log");
ini_set('display_errors',0);
ini_set("log_errors", 1);
set_error_handler("json_error_handler");
date_default_timezone_set("Europe/London");

function json_error_handler($errno ,$errstr,$errfile, $errline){
	error_log("[".Date('d/m/Y H:i:s')."]".$errstr." in ".$errfile.":".$errline);
	exit();
}

/**
 * Required files
**/

spl_autoload_register(function($class){
	$class = str_replace('\\', '/', $class);
	require_once __DIR__.'/Class/'.$class.'.php';
});

require_once 'helpers.php';

/*
 * Global variable
*/

const APP_KEY = '';
const TOKEN = '';
const DSN  = '';
const USERNAME = '';
const PASSWORD = '';
$database = new PDO(DSN,USERNAME,PASSWORD);
$database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$trello = new DrewSprint\TrelloData(APP_KEY,TOKEN);
