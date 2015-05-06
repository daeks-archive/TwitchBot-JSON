<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Pizza counter');
  
  if($execute) {
    $this->say($this->target, false, 'Another pizza dies! - An amount of '.(isset($this->db[$this->target]['stats']['cmds'][$this->command]) ? $this->db[$this->target]['stats']['cmds'][$this->command] : 0).' pizzas have already been eaten in this channel');
  }
  
?>