<?php
class DB{

    private $dbhost;
    private $username;
    private $password;
    private $dbname;
    private $connection;
  
    public function __construct( $dbhost,$username, $password, $dbname){
      $this -> dbhost = $dbhost;
      $this -> username = $username;
      $this -> password = $password;
      $this -> dbname = $dbname;
    }
  
    public function connect(){
      try{

          $this->connection = new mysqli($this->dbhost, $this->username, $this->password, $this->dbname);

          if( mysqli_connect_errno() ){
              throw new Exception("Could not connect to database.");   
          }

      }catch(Exception $e){
          throw new Exception($e->getMessage());   

      }
  }
  
 public function close() {
		return $this->connection->close();
	}
  
  public function dropTableIfExists($table){
     $this->connect();
    $sql = "DROP TABLE IF EXISTS ".$table;
    
    $result = $this->connection->query($sql);
    if($result == true){
        return $result;
    }else{
      echo("Error description: " . $this->connection -> error);
      return false;
    }
  }
  
  public function createTable(){
    $this->connect();
    $sql = "CREATE TABLE users(
              id INT PRIMARY KEY AUTO_INCREMENT,
              email VARCHAR(255) UNIQUE,
              firstname VARCHAR(255),
              lastname VARCHAR(255)
            );";
    
    $result = $this->connection->query($sql);
    if($result == true){
        return $result;
    }else{
      echo("Error description: " . $this->connection -> error);
      return false;
    }
  }
}

// public function insert($)

?>
