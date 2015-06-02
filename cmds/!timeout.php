<?php

  $cmd = array('level' => 'op admin owner',
               'count' => false,
               'help' => 'Timeouts an user');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[5]) && is_numeric($this->data[5]) && $this->data[5] > 0) {
        $this->say($this->target, true, '/timeout '.$this->data[4].' '.$this->data[5]);
      }
    }
  }
  
?>