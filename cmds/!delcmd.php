<?php

  $cmd = array('level' => 'op admin owner',
               'count' => false,
               'help' => 'Delete command');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4]) && in_array(substr($this->data[4], 0, 1), $this->cmdtriggers)) {
        $this->data[4] = strtolower($this->data[4]);
        if(array_key_exists($this->data[4], $this->db[$this->target]['data']['cmds'])) {
          unset($this->db[$this->target]['data']['cmds'][$this->data[4]]);
          $this->save();
          $this->say($this->target, true, '@'.$this->username.' command removed');
        }
      }
    }
  }
  
?>