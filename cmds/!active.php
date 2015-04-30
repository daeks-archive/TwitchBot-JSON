<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Shows all active channels');
  
  if($execute) {
    if($this->isowner()) {
      if(sizeof($this->tmp['active']) > 0) {
        $this->say($this->target, true, ' - Active channels: '.implode(' ', $this->tmp['active']));
      } else {
        $this->say($this->target, true, ' - No active channels found');
      }
    }
  }
  
?>