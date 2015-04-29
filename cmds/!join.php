<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Joins <channel>');

  if($execute) {
    if($this->isowner()) {
      if(isset($this->data[4])) {
        $this->data[4] = strtolower($this->data[4]);
        $this->say(null, true, '@'.$this->username.' - Joining '.$this->data[4]);
        $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->add($this->db['#'.strtolower(BOTNAME)]['channels'], '#'.$this->data[4]);
        $this->init('#'.$this->data[4]);
        $this->send('JOIN #'.$this->data[4]);
        $this->save();
      }
    }
  }

?>