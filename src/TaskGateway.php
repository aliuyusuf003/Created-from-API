<?php 

class TaskGateway{
    private PDO $conn;
    public function __construct(Database $database){
        $this->conn = $database->getConnection();
    }
    public function getAll():array
    {
        $sql = "SELECT *
                 FROM task
                 ORDER BY name";
        $stmt = $this->conn->query($sql);

        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
        // converting integer to booleans

        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $row['is_completed'] = (bool)$row['is_completed'];
            $data[] = $row;

        }
        return $data;
    }

    public function get(string $id)
    {
        $sql = "SELECT *
        FROM task where id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data !== false){
            $data['is_completed'] = (bool)$data['is_completed'];

        }
        return $data;

    }

    public function create(array $data):string
    {
        $sql = "INSERT INTO task(name,priority,is_completed)
                VALUES(:name, :priority,:is_completed)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $data['name'], PDO::PARAM_STR);
        if(empty($data['priority'])){
        $stmt->bindValue(":priority", null, PDO::PARAM_NULL);
        }else{
            $stmt->bindValue(":priority", $data['priority'], PDO::PARAM_INT);
        }

        $stmt->bindValue(":is_completed", $data['is_completed'] ?? false, PDO::PARAM_BOOL);
        $stmt->execute();
        return $this->conn->lastInsertId();

    }

    public function update(string $id, array $data):int
    {
        $fields = [];
        
        if ( ! empty($data["name"])) {
            
            $fields["name"] = [
                $data["name"],
                PDO::PARAM_STR
            ];
        }
        
        // we use array_key_exists rather than empty() so we are checking for only keys and not value.
        if (array_key_exists("priority", $data)) {
            
            $fields["priority"] = [
                $data["priority"],
                $data["priority"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            ];
        } 
        
        if (array_key_exists("is_completed", $data)) {
            
            $fields["is_completed"] = [
                $data["is_completed"],
                PDO::PARAM_BOOL
            ];
        }
        
        if (empty($fields)) {
            
            return 0;
            
        } else {
        
            $sets = array_map(function($keys_in_array) {
                
                return "$keys_in_array = :$keys_in_array";
                
            }, array_keys($fields)); // array_map allows you to perform same task on all the items in an array.

            // print_r($fields);exit;
            // print_r($sets);// this generates an array of bind names
            // print_r(array_keys($fields));// this array holds list of keys in fields array
            
            
            $sql = "UPDATE task"
                 . " SET " . implode(", ", $sets)
                 . " WHERE id = :id";// implode will join array by its delimiters
            
            
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            
            // binding collectively
            foreach ($fields as $name => $values) {
                
                $stmt->bindValue(":$name", $values[0], $values[1]);           
                     // echo $name.":".$values[0].":".$values[1]."\n";
                
            }
           
            $stmt->execute();
            
            return $stmt->rowCount();
        }
        
        
    }
    public function delete(string $id):int
    {
        $sql = "DELETE FROM task"                
                 . " WHERE id = :id";            
            
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->rowCount();

    }
}