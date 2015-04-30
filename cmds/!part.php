<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Leaves <channel>',
               'syntax' => '!part [-purge] <channel>');

  if($execute) {
    if($this->isowner()) {
      if(isset($this->data[4])) {
        $this->data[4] = strtolower($this->data[4]);
        if($this->data[4] == '-purge') {
          $this->data[5] = strtolower($this->data[5]);
          $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->remove($this->db['#'.strtolower(BOTNAME)]['channels'], '#'.$this->data[5]);
          $this->destroy('#'.$this->data[5], true);
          $this->send('PART #'.$this->data[5]);
          $this->save();
        } else {  
          $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->remove($this->db['#'.strtolower(BOTNAME)]['channels'], '#'.$this->data[4]);
          $this->destroy('#'.$this->data[4], false);
          $this->send('PART #'.$this->data[4]);
          $this->save();
        }
      }
    }
  }

?>