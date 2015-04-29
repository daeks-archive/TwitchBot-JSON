<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => false,
               'help' => 'Displays list of moderators');
  
  if($execute) {
    $this->say($this->target, true, trim('- Mods of '.$this->target.': '.implode(' ', $this->db[$this->target]['config']['mods'])));
  }
  
?>