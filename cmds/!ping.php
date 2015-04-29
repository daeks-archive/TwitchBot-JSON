<?php

  $cmd = array('level' => 'op admin owner',
               'count' => false,
               'help' => 'Pings the bot');

  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      $this->say($this->target, true, '@'.$this->username.' pong');
    }
  }

?>