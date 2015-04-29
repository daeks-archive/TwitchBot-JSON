<?php

  $cmd = array('level' => 'op admin owner',
               'count' => false,
               'help' => 'Remove username from permit commands');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4])) {
        if(in_array($this->data[4], $this->db[$this->target]['config']['permits'])) {
          $this->db[$this->target]['config']['permits'] = $this->remove($this->db[$this->target]['config']['permits'], $this->data[4]);
          $this->save();
          $this->say($this->target, true, '@'.$this->username.' permit removed');
        }
      } 
    }
  }
  
?>