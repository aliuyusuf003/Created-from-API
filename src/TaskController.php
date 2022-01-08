<?php  

class TaskController{

    private $gateway;
    private $user_id;

    public function __construct(TaskGateway $gateway, int $user_id){
        $this->gateway = $gateway;
        $this->user_id = $user_id;
    }

    public function processRequest(string $method, ?string $id):void 
    {
        if($id == null){
            if($method == 'GET'){
                // echo "index";

            echo json_encode($this->gateway->getAllUserTask($this->user_id));
            }elseif($method == "POST"){
                // echo "created";
               

            //   echo file_get_contents("php://input");// php://input allow us to get content from the request body
           $data = (array)json_decode(file_get_contents("php://input"));// php://input allow us to get content from the request body
           $errors = $this->getValidationErrors($data);
                
           if ( ! empty($errors)) {
               
               $this->respondUnprocessableEntity($errors);
               return;
               
           }
           
           $id = $this->gateway->createTask($data,$this->user_id);
           $this->respondCreated($id);



            }else{

                // http_response_code(405);
                // header("Allow: GET, POST");
                $this->respondMethodNotAllowed("GET","POST");
            }   

        }else{
            $task = $this->gateway->getUserTask($this->user_id,$id);
            if($task == false){
                $this->respondNotFound($id);
                return;
                 
            } 
            switch($method){
                case "GET":
                    // echo "show $id";
                    echo json_encode($task);
                    break;
                
                //   "PUT" is idempotent while "PATCH" is not. You have to use "PATCH"
                // PUT will expect all attributes be filled before updating resource

                    /*
                Idempotence is the property of certain operations in mathematics and computer science whereby they can 
                be applied multiple times without changing the result beyond the initial application. 
                The concept of idempotence arises in a number of places in abstract algebra and functional programming.
                
                */
                case "PATCH":              
                    $data = (array)json_decode(file_get_contents("php://input"),true);// php://input allow us to get content from the request body
                    $errors = $this->getValidationErrors($data,false);
                         
                    if ( ! empty($errors)) {
                        
                        $this->respondUnprocessableEntity($errors);
                        return;
                        
                    }
                    
                    $rows_updated = $this->gateway->updateTask($this->user_id,$id, $data);
                    echo json_encode(["message" => "Task updated", "rows_updated" => $rows_updated]);
                    break;
                case "DELETE":
                    $rows_deleted = $this->gateway->deleteTask($this->user_id,$id);
                    echo json_encode(["message" => "Task deleted", "rows_deleted" => $rows_deleted]);
                    break;
                default:
                    $this->respondMethodNotAllowed("GET,PATCH,DELETE");
                    break; 
            }
        }


    }

    private function respondUnprocessableEntity(array $errors): void
    {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
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
    private function respondCreated(string $id):void 
    {
        http_response_code(201);
        echo json_encode([
            "id"=> $id,
            "message" => "Task Created"
        ]);
    }
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        
        if ($is_new && empty($data["name"])) {
            
            $errors[] = "name is required";
            
        }
        
        if ( ! empty($data["priority"])) {
            
            if (filter_var($data["priority"], FILTER_VALIDATE_INT) === false) {
                
                $errors[] = "priority must be an integer";
                
            }
        }
        
        return $errors;
    }
}