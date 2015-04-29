<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => false,
               'help' => 'Displays list of permitted users');
  
  if($execute) {
    $this->say($this->target, true, trim('- Permits of '.$this->target.': '.implode(' ', $this->db[$this->target]['config']['permits'])));
  }
  
?>