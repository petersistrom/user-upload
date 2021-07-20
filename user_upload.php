<?php
// turn off for production
error_reporting(-1);
ini_set('display_errors', 'On');
//
include ('help.php');
include ('CSVParser.php');

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
  var_dump($csvParser->getUsers());
}

?>