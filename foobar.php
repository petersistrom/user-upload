<?php
/**
 * foobar.php
 * Output the numbers from 1 to 100
 *  • Where the number is divisible by three (3) output the word “foo”
 *  • Where the number is divisible by five (5) output the word “bar”
 *  • Where the number is divisible by three (3) and (5) output the word “foobar”
 *
 * Author: Peter Sistrom, July 2021
 *
 */

for($i = 1; $i <= 100; $i++){
  if(($i%3==0)&& ($i%5==0)){
    echo "foobar";  
  }
  elseif($i%3==0){
    echo "foo";  
  }
  elseif($i%5==0){
    echo "bar";  
  }
  else{
    echo $i;
  }
  if($i!=100){
    echo ", ";
  }
}
?>