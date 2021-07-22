<?php
/**
 * Takes a CSV file containing users and inserts the users into a MySQL database
 *
 * Author: Peter Sistrom, July 2021
 *
 */
include ('help.php');
include ('CSVParser.php');
include ('DB.php');

//Change to your database name
$dbName = "php_challenge";

$shortopts  = "u::"; //username
$shortopts .= "p::"; //password
$shortopts .= "h::"; //database host

$options  = array(
    "file:",
    "dry_run",
    "create_table",
    "help",
);

$options = getopt($shortopts, $options);

//if help is an argument show help and do nothing else
if (array_key_exists("help", $options)){
  die(showHelp());
}

//a file always needs to be passed as an argument
if(!array_key_exists("file", $options)){
  fwrite(STDOUT, "ERROR - No file, please include a CSV file as an argument using the --file flag".PHP_EOL);
  die(showHelp());
}elseif(!file_exists($options['file'])){
  fwrite(STDOUT,  "ERROR - ".$options['file'].' does not exist'.PHP_EOL);
  die;
}

//Check if dry_run is enabled if not check for database credentails
if(array_key_exists("dry_run", $options)){
  parseCSV($options['file']);
  fwrite(STDOUT, "Dry run mode - database was not altered".PHP_EOL);
}
elseif(array_key_exists("u", $options) && array_key_exists("p", $options) && array_key_exists("h", $options)){
  
  $db = new DB($options["h"], $options["u"], $options["p"], $dbName);

  //create table and nothing else
  if(array_key_exists("create_table", $options)){
    createTable($db);
  //Live run - parse users and insert into database
  }else{
    //check table exists  
    if(!$db->tableExists("users")){
      fwrite(STDOUT, "ERROR - table 'users' does not exist. Please run --create_table first".PHP_EOL);
      die;
    };
    $parsedCSV = parseCSV($options['file']);
    insertUsersintoDB($parsedCSV->getUsers(), $db);
  }   
}else{
  fwrite(STDOUT, "ERROR - Database configuration required - username, password, host".PHP_EOL);
  showHelp();
}

function parseCSV($file){
  $csvParser = new CSVParser();
  $csv = file($file);
  $csvParser->parseCSV($csv);
  return $csvParser;
}

function createTable($db){
  $db->connect();
  $db->dropTableIfExists('users');
  $sql = "CREATE TABLE users(
        id INT PRIMARY KEY AUTO_INCREMENT,
        email VARCHAR(255) UNIQUE,
        name VARCHAR(255),
        surname VARCHAR(255)
      );";
  if($db->executeSQL($sql)){;
    fwrite(STDOUT, "Successfully created table - users".PHP_EOL);                           
  }else{
    fwrite(STDOUT, "ERROR creating table - Users".PHP_EOL);
  }
  $db->close();
}

function insertUsersintoDB($users, $db){
  $db->connect();
  foreach($users as $user){
    $table = "users";
    $cols = ['email', 'name', 'surname'];
    $vals = [$user->getEmail(), $user->getFirstname(), $user->getLastname()];
    $db->insertPreparedSQL($table, $cols, $vals);
  }
  $db->close();
}
?>