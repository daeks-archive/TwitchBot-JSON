<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Saves the current state');

  if($execute) {
    if($this->isowner()) {
      $this->save();
      $this->say(null, true, '@'.$this->username.' - saved');
    }
  }

?>