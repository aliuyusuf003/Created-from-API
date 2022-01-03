<?php
declare(strict_types=1); // global
// ini_set('display_errors','ON');
require dirname(__DIR__). '/vendor/autoload.php';// calling a parent folder

set_exception_handler("ErrorHandler::handleException");

$path = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ; // this removes the query string

$parts = explode('/',$path);


// print_r($parts);

// exit;
$resource  = $parts[3];  // get resource

$id = $parts[4] ?? null; // get optional id


// echo $resource ,", ", $id; // comma is used with echo and not for variable assignment.

// echo $_SERVER['REQUEST_METHOD']; // get method

// validate request uri
if($resource != "tasks"){

    // header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");// or use http_response_code(3digits code here)
    // header("HTTP/1.0 418 I'm A Teapot");// free style
    http_response_code(404);// standard 
    exit;

}

// require dirname(__DIR__). '/src/TaskController.php';// calling a parent folder
// require __DIR__.'/src/TaskController.php';

header("Content-type: application/json; charset=UTF-8");

$controller = new TaskController;

$controller->processRequest($_SERVER['REQUEST_METHOD'],$id);


