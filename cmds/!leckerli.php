<?php

  $cmd = array('level' => 'none',
               'count' => true,
               'help' => 'Gives Charly a goodie');
  
  if($execute) {
    $this->say($this->target, false, ''.$this->username.' gives Charly a goodie - Charly already got an amount of '.(isset($this->db[$this->target]['stats']['cmds'][$this->command]) ? $this->db[$this->target]['stats']['cmds'][$this->command] : 0).' goodies in this channel');
  }
  
?>