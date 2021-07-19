<?php
// turn off for production
error_reporting(-1);
ini_set('display_errors', 'On');
//
include ('help.php');

$shortopts  = "u::"; //username
$shortopts .= "p::"; //password
$shortopts .= "h::"; //database host

$longopts  = array(
    "file:",
    "dry_run",
    "create_table",
    "help",
);

$options = getopt($shortopts, $longopts);
$dry_run = false;

if (array_key_exists("help", $options)){
  showHelp();
  die;
}

if(!array_key_exists("file", $options)){
  echo "ERROR - No file, please include a CSV file as an argument using the --file flag\n";
  showHelp();
  die;
}

if(array_key_exists("dry_run", $options)){
  $dry_run = true;
}



?>