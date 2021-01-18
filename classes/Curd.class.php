<?php
class Curd{
	// Database informations
	private $servername = "localhost";
	private $dbusername = "root";
	private $dbpassword = "";
	private $dbname = "test";
    	protected $conn;

	// Creating connection on class call
	function __construct(){
		$this->conn = new mysqli($this->servername, $this->dbusername, $this->dbpassword, $this->dbname);
		if($this->conn->connect_error){
			header("Location: index.php?error=Connection_error");
			exit();
		}
	}

	// Prepare statment execution
    function stmt_execute($sql, $data){
        $type = str_repeat("s", count($data));
        $values = array_values($data);

        $stmt = $this->conn->prepare($sql);
        if(count($data) > 0){
            $stmt->bind_param($type, ...$values);
        }
        $stmt->execute();
        $result = $stmt->get_result();
		return $result;
    }

	// CURD | create
    function create($table, $data){
        $sql = "INSERT INTO $table SET ";
        $i = 0;
        foreach($data as $key => $value){
            if($i == 0){
                $sql .= "$key=?";
            } else {
                $sql .= ", $key=?";
            }
            $i++;
        }
        $this->stmt_execute($sql, $data);
    }

	// CURD | update
    function update($table, $id_type, $id, $data){
        $sql = "UPDATE $table SET ";
        $i = 0;
        foreach($data as $key => $value){
            if($i == 0){
                $sql .= "$key=?";
            } else {
                $sql .= ", $key=?";
            }
            $i++;
        }
        $sql .= " WHERE " . $id_type ."=?";
        $data['id'] = $id;
        $this->stmt_execute($sql, $data);
    }

	// CURD | read
    function read($table, $conditions=[]){
        if(sizeof($conditions) == 0){
            $sql = "SELECT * FROM $table";
            return $this->stmt_execute($sql, $conditions);
        } else {
            $sql = "SELECT * FROM $table WHERE ";
            $i = 0;
            foreach($conditions as $key => $value){
                if($i == 0){
                    $sql .= "$key=?";
                } else {
                    $sql .= ", $key=?";
                }
				$i++;
            }
            return $this->stmt_execute($sql, $conditions);
        }
    }

	// CURD | delete
    function delete($table, $conditions = []){
		if(sizeof($conditions) > 0){
			$sql = "DELETE FROM $table WHERE ";
			$i = 0;
			foreach ($conditions as $key => $value) {
				if($i == 0){
					$sql .= "$key=?";
				} else {
					$sql .= " AND $key=?";
				}
				$i++;
			}
	        $this->stmt_execute($sql, $conditions);
		}
    }
}
