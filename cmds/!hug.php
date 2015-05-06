<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Hugs the user');
  
  if($execute) {
    if(isset($this->data[4])) {
      $this->say($this->target, false, ''.$this->username.' hugs '.$this->data[4].' :B) An amount of '.(isset($this->db[$this->target]['stats']['cmds'][$this->command]) ? $this->db[$this->target]['stats']['cmds'][$this->command] : 0).' hugs have already been send in this channel');
    } else {
      $this->say($this->target, false, ''.BOTNAME.' hugs '.$this->username.' - You will not stay alone. B)');
    }
  }
  
?>