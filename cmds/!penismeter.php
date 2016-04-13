<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays the length of...');
  
  if($execute) {
    if(isset($this->data[4])) {
      $penis = '<';
      $i=0;
      if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
        while($i<rand(5,25)) {
          $penis .= '=';
          $i++;
        }
      } else {
        while($i<rand(0,15)) {
          $penis .= '=';
          $i++;
        }
      }
      $penis .= '3';
      if($penis == '<3') {
        $this->say($this->target, false, ' - '.$this->data[4].' seems to be not a man Kappa');
      } else {
        $this->say($this->target, false, ' - '.$this->data[4].' has a '.$penis);
      }	
    } else {
      $penis = '<';
      $i=0;
      while($i<rand(0,15)) {
        $penis .= '=';
        $i++;
      }
      $penis .= '3';
      $this->say($this->target, false, ' - '.$this->username.' has a '.$penis);
    }
  }
  
?>