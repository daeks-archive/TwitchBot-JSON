<?php

  $cmd = array('level' => 'mod',
               'help' => 'Bot will shutdown and leave the channel');

  if($this->isop($this->target)) {
    if($this->target == '#'.strtolower(BOTNAME)) {
      $this->say(null, true, '@'.$this->username.' - shutting down');
      $this->log('Good Bye');
      exit(1);
    } else {
      $this->say(null, true, $this->username.' removed me from '.$this->target);
      $this->send('PART '.$this->target);
    }
  }

?>