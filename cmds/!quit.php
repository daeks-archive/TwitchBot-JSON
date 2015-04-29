<?php

  $cmd = array('level' => 'admin owner',
               'count' => false,
               'help' => 'Bot will shutdown and leave the channel');

  if($execute) {
    if($this->isadmin($this->target) || $this->isowner()) {
      if($this->target == '#'.strtolower(BOTNAME)) {
        $this->save();
        $this->say(null, true, '@'.$this->username.' - shutting down');
        $this->log('Good Bye');
        exit(0);
      } else {
        $this->say(null, true, $this->username.' removed me from '.$this->target);
        $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->remove($this->db['#'.strtolower(BOTNAME)]['channels'], $this->target);
        $this->save();
        $this->send('PART '.$this->target);
      }
    }
  }

?>