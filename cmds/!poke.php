<?php

  $cmd = array('level' => 'none',
               'count' => true,
               'help' => 'Pokes the user');
  
  if($execute) {
    if(isset($this->data[4])) {
      $this->say($this->target, true, '/color BlueViolet');
      $this->say($this->target, false, '/me pokes '.$this->data[4].' <3');
      $this->say($this->target, true, '/color '.COLOR);
    } else {
      $this->say($this->target, true, '/color BlueViolet');
      $this->say($this->target, false, '/me pokes '.$this->username.' <3');
      $this->say($this->target, true, '/color '.COLOR);
    }
  }
  
?>