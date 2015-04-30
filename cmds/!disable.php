<?php

  $cmd = array('level' => 'admin owner',
               'count' => false,
               'help' => 'Disable <command> from channel');
  
  if($execute) {
    if($this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4])) {
        $this->data[4] = strtolower($this->data[4]);
        if(in_array(substr($this->data[4], 0, 1), $this->cmdtriggers)) {
          if(!in_array($this->data[4], $this->db[$this->target]['config']['banned_cmds'])) {
            $this->db[$this->target]['config']['banned_cmds'] = $this->add($this->db[$this->target]['config']['banned_cmds'], $this->data[4]);
            $this->save();
            $this->say($this->target, true, '@'.$this->username.' command disabled');
          }
        } else if($this->data[4] == '*') {
          foreach (scandir(CMDS_PATH) as $include){
            if(is_file(CMDS_PATH.DIRECTORY_SEPARATOR.$include) && strpos($include, '..') == 0 && strtoupper(pathinfo($include, PATHINFO_EXTENSION)) == 'PHP'){
              $name = basename(CMDS_PATH.DIRECTORY_SEPARATOR.$include, '.'.pathinfo(CMDS_PATH.DIRECTORY_SEPARATOR.$include, PATHINFO_EXTENSION));
              try {
                $execute = false;
                include(CMDS_PATH.DIRECTORY_SEPARATOR.$include);
                if(in_array('none', explode(' ', $cmd['level'])) || in_array('permit', explode(' ', $cmd['level'])) || in_array('op', explode(' ', $cmd['level'])) ) {
                  if(!in_array($name, $this->db[$this->target]['config']['banned_cmds'])) {
                    $this->db[$this->target]['config']['banned_cmds'] = $this->add($this->db[$this->target]['config']['banned_cmds'], $name);
                  }
                }  
              } catch (Exception $e) {
                $this->error($e);
              } 
            }
          }
          $this->save();
          $this->say($this->target, true, '@'.$this->username.' all commands below '.$this->levels['admin'].' disabled');
        }
      } 
    }
  }
  
?>