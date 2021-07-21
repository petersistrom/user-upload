<?php
include ('User.php');

class CSVParser {
  private $users = array();
  private $firstnameColIndex;
  private $lastnameColIndex;
  private $emailColIndex;

  public function parseCSV($csv){
        foreach ($csv as $key =>  $line) {
          
          $thisUser = new User();
          
          if ($key === array_key_first($csv)){ //first row will be column names
            $this->setColumnIndices(str_getcsv($line));
          }else{

            $row = str_getcsv($line);
            $email =  trim($row[$this->emailColIndex]);
            $firstname = trim($row[$this->firstnameColIndex]);
            $lastname = trim($row[$this->lastnameColIndex]);

            if($this->isValidEmail($email)){//Add user to User array
              $thisUser->setFirstname($this->capatiliseName($firstname));
              $thisUser->setLastname($this->capatiliseName($lastname));
              $thisUser->setEmail($email);
              array_push($this->users, $thisUser);
            }else{//Email not valid skip user
              fwrite(STDOUT, $row[$this->emailColIndex].' is not a valid email - user not added') ;
              echo "\n";
            }
          }
        }
      fwrite(STDOUT, "CSV succesfully parsed - ".count($this->users)." valid users in CSV\n");
    }
  
  //Assumption - the CSV has to contain column headings 'name', 'surname' and 'email'. It may contain extra columns but they will not be parsed.
  private function setColumnIndices($columnRow){//checks that name, surname and email exists and sets the column index
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
  //Assumption - Names are allowwed to contain special characters
  private function capatiliseName($name){
    $lowercaseName = strtolower($name);
    return ucfirst($lowercaseName);
  }

  private function isValidEmail($email){
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    }else{
      return false;
    }
  }

  public function getUsers(){return $this->users;}

}


?>