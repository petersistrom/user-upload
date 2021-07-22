<?php
/**
 * User class for user_upload script
 *
 * Author: Peter Sistrom, July 2021
 *
 */

class User {
  private $firstname;
  private $lastname;
  private $email;
  
  public function setFirstname($firstname) { $this->firstname = $firstname; }
  
  public function setLastname($lastname) { $this->lastname = $lastname; }
  
  public function setEmail($email) { $this->email = $email; }
  
  public function getFirstname() { return $this->firstname; }

  public function getLastname() { return $this->lastname; }
	
	public function getEmail() { return $this->email; }
  
}


?>