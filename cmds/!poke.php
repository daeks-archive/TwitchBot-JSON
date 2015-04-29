<?php

  $cmd = array('level' => 'none',
               'count' => true,
               'help' => 'Pokes the user');
  
  if($execute) {
    $this->say($this->target, true, '/color BlueViolet');
    $this->say($this->target, false, '/me pokes '.$this->username.' <3');
    $this->say($this->target, true, '/color '.COLOR);
  }
  
?>