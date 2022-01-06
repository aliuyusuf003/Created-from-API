<?php
declare(strict_types=1); // global

require __DIR__."/bootstrap.php";


$path = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ; // this removes the query string

$parts = explode('/',$path);


// print_r($parts);


$resource  = $parts[3];  // get resource
$id = $parts[4] ?? null; // get optional id


// echo $resource ,", ", $id; // comma is used with echo and not for variable assignment.

// echo $_SERVER['REQUEST_METHOD']; // get method


// validate request uri
if($resource != "tasks" ||$resource == "src" ){

    // header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");// or use http_response_code(3digits code here)
    // header("HTTP/1.0 418 I'm A Teapot");// free style
    http_response_code(404);// standard 
    exit;

}

// require dirname(__DIR__). '/src/TaskController.php';// calling a parent folder
// require __DIR__.'/src/TaskController.php';

// print_r($_SERVER);





$database = new Database($_ENV['DB_HOST'],$_ENV['DB_NAME'],$_ENV['DB_USER'],$_ENV['DB_PASS']);
// $database->getConnection();// this is removed so database connection should not be global
$user_gateway = new UserGateway($database);

$auth = new Auth($user_gateway);

if ( ! $auth->authenticateAPIKey()) {
    exit;
}

$user_id = $auth->getUserId();

$task_gateway = new TaskGateway($database);
$controller = new TaskController($task_gateway, $user_id);

$controller->processRequest($_SERVER['REQUEST_METHOD'],$id);


