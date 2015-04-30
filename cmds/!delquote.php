<?php

  $cmd = array('level' => 'op admin owner',
               'count' => false,
               'help' => 'Delete quote');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4]) && is_numeric(ltrim($this->data[4], '#')) && ltrim($this->data[4], '#') > 0) {
        if(array_key_exists((ltrim($this->data[4], '#')-1), $this->db[$this->target]['data']['quotes'])) {
          unset($this->db[$this->target]['data']['quotes'][(ltrim($this->data[4], '#')-1)]);
          $this->save();
          $this->say($this->target, true, '@'.$this->username.' quote removed');
        }
      }
    }
  }
  
?>