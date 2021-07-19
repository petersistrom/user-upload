<?php
include ('User.php');

class CSVParser {
  private $users = array();
  private $firstnameColIndex;
  private $lastnameColIndex;
  private $emailColIndex;

  public function parseCSV($csv){
    if(file_exists($csv)){
      $file = file($csv);
        foreach ($file as $key =>  $line) {
          
          $thisUser = new User();
          
          if ($key === array_key_first($file)){ //first line will be column names
            $this->getColumnIndices(str_getcsv($line));
          }else{

            $row = str_getcsv($line);
            $email =  $row[$this->emailColIndex];
            $firstname = $row[$this->firstnameColIndex];
            $lastname = $row[$this->lastnameColIndex];

            if($this->isValidEmail($email)){//Add user to User array
              $thisUser->setFirstname($this->capatiliseName($firstname));
              $thisUser->setLastname($this->capatiliseName($lastname));
              $thisUser->setEmail($email);
              array_push($this->users, $thisUser);
            }else{//Email not valid skip user
              fwrite(STDOUT, $row[$this->emailColIndex].' is not a valid Email - user not added') ;
              echo "\n";
            }
          }
        }
    }else{
      fwrite(STDOUT, $csv.' does not exist');
      echo "\n";
      die;
    }
  }
  
  //Assumption - the CSV has to contain columns 'name', 'surname' and 'email'. It may contain extra columns but they will not be parsed.
  private function getColumnIndices($columnRow){//checks that name, surname and email exists and sets the column indexe
    foreach($columnRow as $key => $column){
      if(trim($column) == "name"){
        $this->firstnameColIndex = $key;
      }elseif(trim($column) == "surname"){
        $this->lastnameColIndex = $key;
      }elseif(trim($column) == "email"){
        $this->emailColIndex = $key;
      }
    }
    if(!isset($this->firstnameColIndex) || !isset($this->lastnameColIndex) || !isset($this->emailColIndex)){
      fwrite(STDOUT, $filename.' not a valid CSV please include `name`, `surname` and `email` columns');
      echo "\n";
      die;
    }
  }
  //Assumption - Names are allowwed to conatin special characters
  private function capatiliseName($name){
    $lowercaseName = strtolower(trim($name));
    return ucfirst($lowercaseName);
  }

  private function isValidEmail($email){
    if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
      return true;
    }else{
      return false;
    }
  }

  public function getUsers(){  return $this->users;}

}


?>