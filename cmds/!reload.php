<?php

  $cmd = array('level' => 'admin',
               'help' => 'Reload configuration');

  if($this->isadmin()) {
    $this->say(null, true, '@'.$this->username.' - reloading');
    $this->log('Reloading');
    exit(0);
  }

?>