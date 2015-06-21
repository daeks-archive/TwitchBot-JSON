<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Saves DB');

  if($execute) {
    if($this->isowner()) {
      
      $this->save();
      $this->say($this->target, true, ' - Force saved DB');
    }
  }

?>