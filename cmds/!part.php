<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Leaves <channel>');

  if($execute) {
    if($this->isowner()) {
      if(isset($this->data[4])) {
        $this->data[4] = strtolower($this->data[4]);
        $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->remove($this->db['#'.strtolower(BOTNAME)]['channels'], '#'.$this->data[4]);
        $this->send('PART #'.$this->data[4]);
        $this->save();
      }
    }
  }

?>