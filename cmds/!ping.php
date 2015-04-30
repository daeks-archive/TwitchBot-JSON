<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => false,
               'help' => 'Pings the bot');

  if($execute) {
    $this->say($this->target, false, '@'.$this->username.' pong');
  }

?>