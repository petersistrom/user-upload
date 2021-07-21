<?php
// turn off for production
// error_reporting(-1);
// ini_set('display_errors', 'On');
//
include ('help.php');
include ('CSVParser.php');
include ('DB.php');

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

if (array_key_exists("help", $options)){//if help is an argument show help and do nothing else
  showHelp();
  die;
}

if(!array_key_exists("file", $options)){//a file always needs to be passed as an argument
  fwrite(STDOUT, "ERROR - No file, please include a CSV file as an argument using the --file flag\n");
  showHelp();
  die;
}elseif(!file_exists($options['file'])){
    fwrite(STDOUT, $options['file'].' does not exist');
    echo "\n";
    die;
}

if(array_key_exists("dry_run", $options)){//dry run no database calls
  $csvParser = new CSVParser();
  $csv = file($options['file']);
  $csvParser->parseCSV($csv);
  fwrite(STDOUT, "CSV succefully parsed in Dry run mode - database was not altered\n");
  var_dump($csvParser->getUsers()); 
  die;
}
if(array_key_exists("u", $options) || array_key_exists("p", $options) || array_key_exists("h", $options)){ 
  //construct database connection DB("host", "username", "password", "database")
  $db = new DB($options["h"], $options["u"], $options["p"], "php_challenge");
  
  if(array_key_exists("create_table", $options)){//create table and nothing else
    $db->dropTableIfExists('users');
        $sql = "CREATE TABLE users(
              id INT PRIMARY KEY AUTO_INCREMENT,
              email VARCHAR(255) UNIQUE,
              firstname VARCHAR(255),
              lastname VARCHAR(255)
            );";
    $db->executeSQL($sql);
  }else{//Live run parse users and insert into database
    $csvParser = new CSVParser();
    $csv = file($options['file']);
    $csvParser->parseCSV($csv);
    insertUsersintoDB($csvParser->getUsers(), $db);
  }   
   
}else{
  fwrite(STDOUT, "ERROR - Database configuration required - username, password, host".PHP_EOL);
  showHelp();
}


function insertUsersintoDB($users, $db){
  foreach($users as $user){
    $db->insert("users", "(email, firstname, lastname)",'("'.$user->getEmail().'","'.$user->getFirstname().'","'.$user->getLastname().'")');
  }
 }
?>