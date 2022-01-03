<?php 

// We use url rewriting to prevent direct mapping between the request url and the file and folder on the webserver
// Using rewrite rules, we can associate the url with whatever script we want
//.htaccess file 

// Restful api resource has two urls, one for collection and one for resource.
/*
GET /tasks
GET /task/123
POST /tasks 
PUT or PATCH /task/123
DELETE /task/21
*/

// Apart from browser, you can use curl,postman or httpie to make url request
// curl url 
// curl url --request PATCH
// curl url --X DELETE

// loading class automatically entails creating composer.json file and running composer dump-autoload, 
/*
your json file should have the following script 
{
    "autoload": {
        "psr-4":{
            "":"src/"
        }
    }
}
*/
// You can now include your autoload class in all the script you required to include any file.

// type declaration allows us to debug easily, :void implies no return value
//declare(strict_types=1); has to be at the top of the entry point
// ?string $id meaning id is nullable
// this makes code readable and debugging easy.


// always handle error response as json because its an api



