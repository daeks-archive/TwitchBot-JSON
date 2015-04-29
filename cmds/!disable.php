<?php

  $cmd = array('level' => 'admin owner',
               'count' => false,
               'help' => 'Disable <command> from channel');
  
  if($execute) {
    if($this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4]) && substr($this->data[4], 0, 1) == '!') {
        $this->data[4] = strtolower($this->data[4]);
        if(!in_array($this->data[4], $this->db[$this->target]['config']['banned_cmds'])) {
          $this->db[$this->target]['config']['banned_cmds'] = $this->add($this->db[$this->target]['config']['banned_cmds'], $this->data[4]);
          $this->save();
          $this->say($this->target, true, '@'.$this->username.' command disabled');
        }
      } 
    }
  }
  
?>