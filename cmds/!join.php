<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Joins <channel>',
               'syntax' => '!join [-silent] <channel>');

  if($execute) {
    if($this->isowner()) {
      if(isset($this->data[4])) {
        $this->data[4] = strtolower($this->data[4]);
        if($this->data[4] == '-silent') {
          $this->data[5] = strtolower($this->data[5]);
          $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->add($this->db['#'.strtolower(BOTNAME)]['channels'], '#'.$this->data[5]);
          $this->init('#'.$this->data[5]);
          foreach (scandir(CMDS_PATH) as $include){
            if(is_file(CMDS_PATH.DIRECTORY_SEPARATOR.$include) && strpos($include, '..') == 0 && strtoupper(pathinfo($include, PATHINFO_EXTENSION)) == 'PHP'){
              $name = basename(CMDS_PATH.DIRECTORY_SEPARATOR.$include, '.'.pathinfo(CMDS_PATH.DIRECTORY_SEPARATOR.$include, PATHINFO_EXTENSION));
              $this->db['#'.$this->data[5]]['config']['banned_cmds'] = $this->add($this->db['#'.$this->data[5]]['config']['banned_cmds'], $name);
            }
          }
          $this->send('JOIN #'.$this->data[5]);
          $this->save();
        } else {
          $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->add($this->db['#'.strtolower(BOTNAME)]['channels'], '#'.$this->data[4]);
          $this->init('#'.$this->data[4]);
          $this->send('JOIN #'.$this->data[4]);
          $this->save();
        }
      }
    }
  }

?>