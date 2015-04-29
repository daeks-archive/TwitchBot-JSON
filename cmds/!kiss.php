<?php

  $cmd = array('level' => 'none',
               'count' => true,
               'help' => 'Kiss for the user');
  
  if($execute) {
    if(isset($this->data[4])) {
      $this->say($this->target, false, ''.$this->username.' sends '.$this->data[4].' an air-kiss <3 An amount of '.(isset($this->db[$this->target]['stats']['cmds'][$this->command]) ? $this->db[$this->target]['stats']['cmds'][$this->command] : 0).' kisses have already been send in this channel');
    } else {
      $this->say($this->target, false, ''.BOTNAME.' kisses '.$this->username.' - You will not stay alone. B)');
    }
  }
  
?>