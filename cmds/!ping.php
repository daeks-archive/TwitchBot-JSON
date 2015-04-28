<?php

  $cmd = array('level' => 'op',
               'help' => 'Pings the bot');

  if($this->isop($this->target)) {
    $this->say($this->target, true, '@'.$this->username.' pong');
  }

?>