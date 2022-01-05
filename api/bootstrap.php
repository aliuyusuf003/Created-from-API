<?php 


// ini_set('display_errors','ON');
require dirname(__DIR__). '/vendor/autoload.php';// calling a parent folder

set_exception_handler("ErrorHandler::handleException");// calling ErrorHandler class
set_error_handler("ErrorHandler::handleError");// generic ErrorHandler class
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header("Content-type: application/json; charset=UTF-8");
