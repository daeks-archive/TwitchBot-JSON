<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Reload configuration');

  if($execute) {
    if($this->isowner()) {
      $this->save();
      $this->say(null, true, '@'.$this->username.' - reloading');
      $this->log('Reloading');
      exit(1);
    }
  }

?>