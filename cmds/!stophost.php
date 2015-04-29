<?php

  $cmd = array('level' => 'admin owner',
               'count' => true,
               'help' => 'Disable hosting');
  
  if($execute) {
    if($this->isadmin($this->target) || $this->isowner()) {
      $this->say(null, true, '/unhost');
      $this->say($this->target, true, '@'.$this->username.' - I am going back to normal mode');
    }
  }
  
?>