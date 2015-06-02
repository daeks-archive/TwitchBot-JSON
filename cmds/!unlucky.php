<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Unlucky game counter');
  
  if($execute) {
    $this->say($this->target, false, 'Another unlucky play! - An amount of '.(isset($this->db[$this->target]['stats']['cmds'][$this->command]) ? $this->db[$this->target]['stats']['cmds'][$this->command] : 0).' plays have already been made in this channel');
  }
  
?>