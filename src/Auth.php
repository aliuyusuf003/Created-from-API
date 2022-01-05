<?php

class Auth
{
    // public function __construct(private UserGateway $user_gateway)// used in php8 constructor promotion
    private $user_gateway;
    public function __construct(UserGateway $user_gateway)
    {
        $this->user_gateway = $user_gateway;
    }
        
    public function authenticateAPIKey(): bool
    {
        if (empty($_SERVER["HTTP_X_API_KEY"])) {
    
            http_response_code(400);
            echo json_encode(["message" => "missing API key"]);
            return false;
        }
        
        $api_key = $_SERVER["HTTP_X_API_KEY"]; // you must pass X-API-Key value in your request header if you are not using param
        // eter api key 

        if($this->user_gateway->getUserAPIKey($api_key) === false){

            http_response_code(401);
            echo json_encode(["message" => "Invalid API key"]);
            return false;        
        }          
        return true;    
    }
}










