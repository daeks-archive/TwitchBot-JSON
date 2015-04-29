<?php

  $cmd = array('level' => 'admin owner',
               'count' => false,
               'help' => 'Delete plugin');
  
  if($execute) {
    if($this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4])) {
        $this->data[4] = strtolower($this->data[4]);
        if(array_key_exists($this->data[4], $this->db[$this->target]['config']['plugins'])) {
          unset($this->db[$this->target]['config']['plugins'][$this->data[4]]);
          $this->save();
          $this->say($this->target, true, '@'.$this->username.' plugin removed');
        }
      }
    }
  }
  
?>