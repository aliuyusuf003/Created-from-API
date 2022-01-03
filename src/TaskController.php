<?php  

class TaskController{

    private $gateway;
    public function __construct(TaskGateway $gateway){
        $this->gateway = $gateway;
    }
    public function processRequest(string $method, ?string $id):void 
    {
        if($id == null){
            if($method == 'GET'){
                // echo "index";

            echo json_encode($this->gateway->getAll());
            }elseif($method == "POST"){
                echo "created";

            }else{
                // http_response_code(405);
                // header("Allow: GET, POST");
                $this->respondMethodNotAllowed("GET","POST");
            }   

        }else{
            $task = $this->gateway->get($id);
            if($task == false){
                $this->respondNotFound($id);
                return;
                 
            } 
            switch($method){
                case "GET":
                    // echo "show $id";
                    echo json_encode($task);
                    break;
                    
                case "PATCH":
                    echo "update $id";
                    break;
                case "DELETE":
                    echo "delete $id";
                    break;
                default:
                    $this->respondMethodNotAllowed("GET,PATCH,DELETE");
                    break; 
            }
        }


    }

    private function respondMethodNotAllowed(string $allowedMethods):void
    {
        http_response_code(405);
        header("Allow: $allowedMethods");
    }
    private function respondNotFound(string $id):void 
    {
        http_response_code(404);
        echo json_encode([
            "message" => "Task with the ID $id not found"
        ]);
    }
}