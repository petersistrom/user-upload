<?php
/**
 * MySQL Database class for user_upload script
 *
 * Author: Peter Sistrom, July 2021
 *
 */
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
    $this->connection = new mysqli($this->dbhost, $this->username, $this->password, $this->dbname);
    if( mysqli_connect_errno() ){
      die("Connection failed: " . $this->connection->connect_error);
    }
  }
  
  public function close() {
    return $this->connection->close();
  }

  public function dropTableIfExists($table){
    $sql = "DROP TABLE IF EXISTS ".$table;
    $result = $this->connection->query($sql);
    if($result == true){
        return $result;
    }else{
      fwrite(STDOUT,"Error description: " . $this->connection -> error.PHP_EOL);
      return false;
    }
  }
  
  public function executeSQL($sql){
    $result = $this->connection->query($sql);
    if($result == true){
        return $result;
    }else{
      fwrite(STDOUT,"Error description: " . $this->connection -> error.PHP_EOL);
      return false;
    }
  }
  public function insertRawSQL($table, $columns, $values){
    $sql = "INSERT INTO ".$table." ".$columns." VALUES ".$values;        
    $result = $this->connection->query($sql);
    if($result == true){
        return $result;
    }else{
      fwrite(STDOUT, "Error description: " . $this->connection -> error.PHP_EOL);
      return false;
    }
  }
  public function insertPreparedSQL($table, $columns, $values){
    $sqlCols = implode(',', $columns);
    $sqlPlaceholders =  implode(',', array_map(function($val) { return "?"; }, $columns));

    $stmt = $this->connection->prepare("INSERT INTO ".$table." (".$sqlCols.") VALUES (".$sqlPlaceholders.")");
    $stmt->bind_param('sss', ...$values);
    $result = $stmt->execute();
    if($result == true){
        return $result;
    }else{
      fwrite(STDOUT, "Error description: " . $this->connection -> error.PHP_EOL);
      return false;
    }
  }
  public function tableExists($table){
    //Code to see if Table Exists
    $this->connect();
    $result = $this->connection->query("select 1 from ".$table);
    if($result == true){
        return $result;
    }else{
      return false;
    }
    $this->close();
  }
}
?>