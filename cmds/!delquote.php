<?php

  $cmd = array('level' => 'op admin owner',
               'count' => false,
               'help' => 'Delete command');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4]) && (substr($this->data[4], 0, 1) == '!') && ltrim($this->data[4], '#') > 0) {
        if(array_key_exists($this->data[4], $this->db[$this->target]['data']['quotes'])) {
          unset($this->db[$this->target]['data']['quotes'][ltrim($this->data[4], '#')]);
          $this->save();
          $this->say($this->target, true, '@'.$this->username.' quote removed');
        }
      }
    }
  }
  
?>