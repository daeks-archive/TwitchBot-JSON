<?php

  $cmd = array('level' => 'none',
               'count' => true,
               'help' => 'Displays the amount of the girlfriend queue');
  
  if($execute) {
    $subcommand = rtrim($this->command, 's');
    $this->say($this->target, false, ucfirst(ltrim($this->target, '#')).' could choose between '.(isset($this->db[$this->target]['stats']['cmds'][$subcommand]) ? $this->db[$this->target]['stats']['cmds'][$subcommand] : 0).' possible girlfriends. This takes some time to evaluate. Please be patient. Kappa');
  }
  
?>