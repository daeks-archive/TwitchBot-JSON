<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => false,
               'help' => 'Displays custom commands');
  
  if($execute) {
    $this->say($this->target, true, trim('- Commands of '.$this->target.': '.implode(' ', array_keys($this->db[$this->target]['data']['cmds']))));
  }
  
?>